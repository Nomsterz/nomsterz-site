<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Session\SaveHandler\DbTableGateway;
use Zend\Session\SaveHandler\DbTableGatewayOptions;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Session\Config\SessionConfig;

/**
 * Libs
 */

/**
 * Tables (& Databases) Used in this Module
 */
use Application\Model\User;
use Application\Mapper\UserTable;
use Application\Model\Error;
use Application\Mapper\ErrorTable;
use Application\Model\Pagehit;
use Application\Mapper\PagehitTable;

class Module implements ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager           =   $e->getApplication()->getEventManager();
        $serviceManager         =   $e->getApplication()->getServiceManager();
        $sharedManager          =   $e->getApplication()->getEventManager()->getSharedManager();
        $moduleRouteListener    =   new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

		/**
		 * Setup Sessions
		 */
        $sessionManager         =   $serviceManager->get('session_manager');
        $sessionManager->start();
        Container::setDefaultManager($sessionManager);

        /**
         * Setup Logging for this Module
         */
        $writer     =   new Stream(__DIR__ . '/../../data/logs/' . __NAMESPACE__ . '/application-'.date('Y-m-d').'.log');
        $logger     =   new Logger();
        $logger->addWriter($writer);
        Logger::registerErrorHandler($logger);
        $serviceManager->setService('Zend\Log', $logger);

        /**
         * Anything that needs to be setup before the Module is initialized
         * goes in here....I guess. Don't quote me.
         */
        $sharedManager->attach
                        (
                            'Zend\Mvc\Controller\AbstractController',
                            'dispatch',
                            function($e)
                            {
                                $controller                 =   $e->getTarget();
                                $controllerClass            =   get_class($controller);
                                $controllerTopNamespace     =   substr($controllerClass, 0, strpos($controllerClass, '\\'));
                                $action                     =   $controller->params('action');
                                $config                     =   $e->getApplication()->getServiceManager()->get('config');

                                /**
                                 * Setup Custom Layouts
                                 */
                                #echo "<pre>" . print_r($controller->params('action'),1) . "</pre>";
                                if (isset($config['module_layouts'][$controllerTopNamespace]))
                                {
                                    switch($action)
                                    {
                                        #case 'login'        :   $controller->layout('layout/layout/empty');
                                                                #break;

                                        default             :   $controller->layout($config['module_layouts'][$controllerTopNamespace]);
                                    }
                                }

                            }, 100
                        );

        /**
         * Log all exceptions in the stack
         */
        $sharedManager->attach
                        (
                            'Zend\Mvc\Application',
                            'dispatch.error',
                            function($e) use ($serviceManager)
                            {
                                if ($e->getParam('exception'))
                                {
                                    $ex     =   $e->getParam('exception');
                                    do
                                    {
                                        $serviceManager->get('Zend\Log')->crit
                                                                            (
                                                                                sprintf
                                                                                (
                                                                                   "%s:%d %s (%d) [%s]\n",
                                                                                    $ex->getFile(),
                                                                                    $ex->getLine(),
                                                                                    $ex->getMessage(),
                                                                                    $ex->getCode(),
                                                                                    get_class($ex)
                                                                                )
                                                                            );
                                    }
                                    while($ex = $ex->getPrevious());
                                }
                            }
                        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return  array
                (
                    'Zend\Loader\ClassMapAutoloader'        =>  array
                                                                (
                                                                    include __DIR__ . '/autoload_classmap.php',
                                                                ),
                    'Zend\Loader\StandardAutoloader'        =>  array
                                                                (
                                                                    'namespaces'    =>  array
                                                                                        (
                                                                                            __NAMESPACE__                       =>  __DIR__ . '/src/' . __NAMESPACE__,
                                                                                            'Nomsterz\Library'                  =>  __DIR__ . '/../../library/Nomsterz',
                                                                                            'Nomsterz\Library\Utilities'        =>  __DIR__ . '/../../library/Nomsterz/Utilities',
                                                                                        ),
                                                                ),
                );
    }

    public function getServiceConfig()
    {
        return  array
                (
                    'factories'     =>  array
                                        (
                                            // Sessions
                                            'session_manager'                   =>  function($serviceManager)
                                                                                    {
                                                                                        $sessionOptions         =   new DbTableGatewayOptions();
                                                                                        $dbAdapter              =   $serviceManager->get('utils-db');
                                                                                        $resultSetPrototype     =   new ResultSet();
                                                                                        $sessionTableGateway    =   new TableGateway('session', $dbAdapter, null, $resultSetPrototype);
                                                                                        $sessionGateway         =   new DbTableGateway($sessionTableGateway, $sessionOptions);
                                                                                        $config                 =   $serviceManager->get('config');
                                                                                        $sessionConfig          =   new SessionConfig();
                                                                                        $sessionConfig->setOptions($config['session_config']);
                                                                                        $sessionManager         =   new SessionManager($sessionConfig);
                                                                                        $sessionManager->setSaveHandler($sessionGateway);

                                                                                        return $sessionManager;
                                                                                    },

                                            /**
                                             * Libraries
                                             */

                                            /**
                                             * Tables Used in this Module
                                             */
                                            'Application\Mapper\UserTable'      =>  function($sm)
                                                                                    {
                                                                                        $tableGateway   =   $sm->get('UserTableGateway');
                                                                                        $table          =   new UserTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'UserTableGateway'                  =>  function($sm)
                                                                                    {
                                                                                        $dbAdapter          =   $sm->get('main-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new User());
                                                                                        return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                            'Application\Mapper\PagehitTable'   =>  function($sm)
                                                                                    {
                                                                                        $tableGateway   =   $sm->get('PagehitTableGateway');
                                                                                        $table          =   new PagehitTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'PagehitTableGateway'               =>  function($sm)
                                                                                    {
                                                                                        $dbAdapter          =   $sm->get('main-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new Pagehit());
                                                                                        return new TableGateway('pagehit', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                            'Application\Mapper\ErrorTable'     =>  function($sm)
                                                                                    {
                                                                                        $tableGateway   =   $sm->get('ErrorTableGateway');
                                                                                        $table          =   new ErrorTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'ErrorTableGateway'                 =>  function($sm)
                                                                                    {
                                                                                        $dbAdapter          =   $sm->get('utils-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new Error());
                                                                                        return new TableGateway('error', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                        ),
                );
    }
}
