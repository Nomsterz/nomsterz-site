<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Business;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;

use Auth\Model\MyAuthStorage;

/**
 * Tables (& Databases) Used in this Module
 */

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
        $writer     =   new Stream(__DIR__ . '/../../data/logs/' . __NAMESPACE__ . '/notary-'.date('Y-m-d').'.log');
        $logger     =   new Logger();
        $logger->addWriter($writer);
        Logger::registerErrorHandler($logger);
        $serviceManager->setService('Zend\Log\Business', $logger);

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
                                        #case 'login'        :   $controller->layout('layout/admin/empty');
                                                                #break;

                                        default             :   $controller->layout($config['module_layouts'][$controllerTopNamespace]);
                                    }
                                }

                            },
							100
                        );

		/**
		 * Handle all errors within this module
         * Log all exceptions in the stack
         */
        $sharedManager->attach
                        (
                            'Zend\Mvc\Application',
                            'dispatch.error',
                            function($e) use ($serviceManager)
                            {
								//$controller                 =   $e->getTarget();
								if ($e->getParam('exception'))
                                {
                                    $ex     =   $e->getParam('exception');
                                    do
                                    {
                                        $serviceManager->get('Zend\Log\Business')->crit
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

								Module::onDispatchError($e);
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

										),
				);
	}

	public function onDispatchError(MvcEvent $event)
	{
		$serviceManager = 	$event->getApplication()->getServiceManager();
		if ($event->getParam('exception'))
		{
			$ex     =   $event->getParam('exception');
			do
			{
				$serviceManager->get('Zend\Log\Business')->crit
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
        $response = $event->getResponse();

		switch($response->getStatusCode())
		{
			case '400'	:	$errorTitle 	=	'Oops!';
							$mainHeading 	=	'Are you lost in the wild?';
							$messageLines 	=	array
												(
													"Sorry, but the page you're looking for has not been found",
													"Try checking the URL for errors, <a href=\"/BusinessHome\">goto home</a> or try to search below.",
												);
							break;

			case '404'	:	$errorTitle 	=	'Oops!';
							$mainHeading 	=	'Are you lost in the wild?';
							$messageLines 	=	array
												(
													"Sorry, but the page you're looking for has not been found",
													"Try checking the URL for errors, <a href=\"/BusinessHome\">goto home</a> or try to search below.",
												);
							break;

			default		:	$errorTitle 	=	'Oops!';
							$mainHeading 	=	'Are you lost in the wild?';
							$messageLines 	=	array
												(
													"Sorry, but the page you're looking for has not been found",
													"Try checking the URL for errors, <a href=\"/BusinessHome\">goto home</a> or try to search below.",
												);
		}

        $layout 		= 	$serviceManager->get( 'viewManager' )->getViewModel();
        $viewModel 		= 	$event->getResult();
        $layout->setTemplate( 'layout/business/empty' );
        $viewModel->setVariables
					(
				  		array
						(
							'errorTitle' 	=> 	$errorTitle,
							'mainHeading' 	=> 	$mainHeading,
							'messageLines' 	=> 	$messageLines,
							'homeLink' 		=> 	'/BusinessHome',
						)
					)
                  ->setTemplate( 'error/custom');
	}
}
