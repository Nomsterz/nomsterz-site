<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use ZendService\ReCaptcha\ReCaptcha;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Auth\Model\MyAuthStorage;

/**
 * Tables (& Databases) Used in this Module
 */
use Auth\Model\IPBin;
use Auth\Model\Member;
use Auth\Model\MemberEmails;
use Auth\Model\MemberDetails;
use Auth\Model\MemberStatus;
use Auth\Model\EmailStatus;
use Auth\Model\AccessAttempt;

use Auth\Mapper\IPBinTable;
use Auth\Mapper\MemberTable;
use Auth\Mapper\MemberEmailsTable;
use Auth\Mapper\MemberDetailsTable;
use Auth\Mapper\MemberStatusTable;
use Auth\Mapper\EmailStatusTable;
use Auth\Mapper\AccessAttemptTable;

class Module implements AutoloaderProviderInterface
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
        $writer     =   new Stream(__DIR__ . '/../../data/logs/' . __NAMESPACE__ . '/auth-'.date('Y-m-d').'.log');
        $logger     =   new Logger();
        $logger->addWriter($writer);
        Logger::registerErrorHandler($logger);
        $serviceManager->setService('Zend\Log\Auth', $logger);

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
                                if (isset($config['module_layouts'][$controllerTopNamespace]))
                                {
                                    switch($action)
                                    {
                                        #case 'login'        :   $controller->layout('layout/auth');
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
                                        $serviceManager->get('Zend\Log\Auth')->crit
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

    public function getServiceConfig()
    {
        return  array
                (
                    'factories'     =>  array
                                        (
                                            /**
                                             * Authentication
                                             */
                                            'Auth\AuthMemberStorage'            =>  function($serviceManager)
                                                                                    {
                                                                                        return new Storage\Session('Nomsterz_AuthMember');
                                                                                    },

                                            'AuthService'                       =>  function($serviceManager)
                                                                                    {
                                                                                        $dbAdapter           =  $serviceManager->get('main-db');
                                                                                        $dbTableAuthAdapter  =  new DbTableAuthAdapter
                                                                                                                    (
                                                                                                                        $dbAdapter,
                                                                                                                        'member',
                                                                                                                        'id',
                                                                                                                        'login_credentials',
                                                                                                                        'member_type != "employee"'
                                                                                                                    );

                                                                                        $authService        =   new AuthenticationService();
                                                                                        $authService->setAdapter($dbTableAuthAdapter);
                                                                                        $authService->setStorage($serviceManager->get('Auth\AuthMemberStorage'));

                                                                                        return $authService;
                                                                                    },

                                            /**
                                             * ReCaptcha Service
                                             */
                                            'ReCaptchaService'                  =>  function($serviceManager)
                                                                                    {
                                                                                        $config     =   $serviceManager->get('config');
                                                                                        return  new ReCaptcha
                                                                                                    (
                                                                                                        $config['encryptionKeys']['reCaptcha']['publicKey'],
                                                                                                        $config['encryptionKeys']['reCaptcha']['privateKey'],
                                                                                                        null,
                                                                                                        array
                                                                                                        (
                                                                                                            'theme' =>  'white',
                                                                                                            'lang'  =>  'en',
                                                                                                        )
                                                                                                    );
                                                                                    },

                                            /**
                                             * Tables Used in this Module
                                             */
                                            'Auth\Mapper\MemberTable'           =>  function($serviceManager)
                                                                                    {
                                                                                        $tableGateway   =   $serviceManager->get('MemberTableGateway');
                                                                                        $table          =   new MemberTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'MemberTableGateway'                =>  function($serviceManager)
                                                                                    {
                                                                                        $dbAdapter          =   $serviceManager->get('main-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new Member());
                                                                                        return new TableGateway('member', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                            'Auth\Mapper\MemberEmailsTable'     =>  function($serviceManager)
                                                                                    {
                                                                                        $tableGateway   =   $serviceManager->get('MemberEmailsTableGateway');
                                                                                        $table          =   new MemberEmailsTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'MemberEmailsTableGateway'          =>  function($serviceManager)
                                                                                    {
                                                                                        $dbAdapter          =   $serviceManager->get('main-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new MemberEmails());
                                                                                        return new TableGateway('member_emails', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                            'Auth\Mapper\MemberDetailsTable'    =>  function($serviceManager)
                                                                                    {
                                                                                        $tableGateway   =   $serviceManager->get('MemberDetailsTableGateway');
                                                                                        $table          =   new MemberDetailsTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'MemberDetailsTableGateway'         =>  function($serviceManager)
                                                                                    {
                                                                                        $dbAdapter          =   $serviceManager->get('main-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new MemberDetails());
                                                                                        return new TableGateway('member_details', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                            'Auth\Mapper\MemberStatusTable'     =>  function($serviceManager)
                                                                                    {
                                                                                        $tableGateway   =   $serviceManager->get('MemberStatusTableGateway');
                                                                                        $table          =   new MemberStatusTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'MemberStatusTableGateway'          =>  function($serviceManager)
                                                                                    {
                                                                                        $dbAdapter          =   $serviceManager->get('main-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new MemberStatus());
                                                                                        return new TableGateway('member_status', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                            'Auth\Mapper\EmailStatusTable'     =>  	function($serviceManager)
                                                                                    {
                                                                                        $tableGateway   =   $serviceManager->get('EmailStatusTableGateway');
                                                                                        $table          =   new EmailStatusTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'EmailStatusTableGateway'          =>  	function($serviceManager)
                                                                                    {
                                                                                        $dbAdapter          =   $serviceManager->get('main-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new EmailStatus());
                                                                                        return new TableGateway('email_status', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                            'Auth\Mapper\AccessAttemptTable'  	=>  function($serviceManager)
                                                                                    {
                                                                                        $tableGateway   =   $serviceManager->get('AccessAttemptTableGateway');
                                                                                        $table          =   new AccessAttemptTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'AccessAttemptTableGateway'         =>  function($serviceManager)
                                                                                    {
                                                                                        $dbAdapter          =   $serviceManager->get('utils-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new AccessAttempt());
                                                                                        return new TableGateway('access_attempt', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                            'Auth\Mapper\IPBinTable'     		=>  function($serviceManager)
                                                                                    {
                                                                                        $tableGateway   =   $serviceManager->get('IPBinTableGateway');
                                                                                        $table          =   new IPBinTable($tableGateway);
                                                                                        return $table;
                                                                                    },
                                            'IPBinTableGateway'          		=>  function($serviceManager)
                                                                                    {
                                                                                        $dbAdapter          =   $serviceManager->get('utils-db');
                                                                                        $resultSetPrototype =   new ResultSet();
                                                                                        $resultSetPrototype->setArrayObjectPrototype(new IPBin());
                                                                                        return new TableGateway('ip_bin', $dbAdapter, null, $resultSetPrototype);
                                                                                    },
                                        ),

                );
    }
}
