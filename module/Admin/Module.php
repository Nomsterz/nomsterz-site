<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Tables (& Databases) Used in this Module
 */

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager           =   $e->getApplication()->getEventManager();
        $serviceManager         =   $e->getApplication()->getServiceManager();
        $sharedManager          =   $e->getApplication()->getEventManager()->getSharedManager();
        $moduleRouteListener    =   new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /**
         * Setup Logging for this Module
         */
        $writer     =   new Stream(__DIR__ . '/../../data/logs/' . __NAMESPACE__ . '/admin-'.date('Y-m-d').'.log');
        $logger     =   new Logger();
        $logger->addWriter($writer);
        Logger::registerErrorHandler($logger);
        $serviceManager->setService('Zend\Log\Admin', $logger);

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
                                 * Setup Custom Layouts - Given the Module, the Controller, and the Action
                                 */
                                #echo "<pre>" . print_r($controller->params('action'),1) . "</pre>";
                                if (isset($config['module_layouts'][$controllerTopNamespace]))
                                {
                                    switch($action)
                                    {
                                        #case 'login'        :   $controller->layout('layout/admin/empty');
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
                                        $serviceManager->get('Zend\Log\Admin')->crit
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
                                                                                            __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                                                                                        ),
                                                                ),
                );
    }
}
