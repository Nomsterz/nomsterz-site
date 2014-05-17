<?php
return  array
        (
            'controllers'       =>  array
                                    (
                                        'invokables'    =>  array
                                                            (
                                                                 'Business\Controller\Business' 	=> 'Business\Controller\NotaryController',
                                                                 'Business\Controller\Signings' 	=> 'Business\Controller\SigningsController',
                                                            ),
                                    ),

            'router'            =>  array
                                    (
                                        'routes'    	=>  array
															(
																'notary-home' 				=>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotaryHome',
																														'defaults'      =>  array
																																			(
																																				'controller' => 'Business\Controller\Business',
																																				'action'     => 'index',
																																			),
																													),
																								),
																'notary-logout'         	=>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotaryLogout',
																														'defaults'      =>  array
																																			(
																																				'controller' => 'Business\Controller\Business',
																																				'action'     => 'notary-logout',
																																			),
																													),
																								),
																'notary-change-password'  	=>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/notaryChangePassword',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 	'Auth\Controller\Auth',
																																				'action'     	=> 	'change-password-with-old-password',
																																				'member-type'	=>	'notary',
																																			),
																													),
																								),
																'notary-signings-section'   =>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotarySignings',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 'Business\Controller\Signings',
																																				'action'     	=> 	'index',
																																			),
																													),
																								),
																'notary-signing-orders'     =>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotarySigningOrders',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 'Business\Controller\Signings',
																																				'action'     	=> 	'signing-orders',
																																			),
																													),
																								),
																'notary-signing-sources'   	=>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotarySigningSources',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 'Business\Controller\Signings',
																																				'action'     	=> 	'signing-sources',
																																			),
																													),
																								),
																'notary-data-section'       =>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/dashboard',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 'Business\Controller\Data',
																																				'action'     	=> 	'signings',
																																			),
																													),
																								),
																'notary-profile'         	=>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotaryProfile',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 'Business\Controller\Business',
																																				'action'     	=> 	'profile',
																																			),
																													),
																								),

																'notary-profile-ajaxForm-Profile'         	=>  array
																												(
																													'type'      =>  'segment',
																													'options'   =>  array
																																	(
																																		'route'         =>  '/ajax-profile-forms/profile',
																																		'defaults'      =>  array
																																							(
																																								'controller' 	=> 	'Business\Controller\Business',
																																								'action'     	=> 	'profile-form',
																																							),
																																	),
																												),
																'notary-address-book'       =>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotaryAddressBook',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 'Business\Controller\Business',
																																				'action'     	=> 	'address-book',
																																			),
																													),
																								),
																'notary-account-settings'   =>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotaryAccountSettings',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 'Business\Controller\Business',
																																				'action'     	=> 	'account-settings',
																																			),
																													),
																								),
																'notary-privacy-settings'   =>  array
																								(
																									'type'      =>  'segment',
																									'options'   =>  array
																													(
																														'route'         =>  '/NotaryPrivacy Settings',
																														'defaults'      =>  array
																																			(
																																				'controller' 	=> 'Business\Controller\Business',
																																				'action'     	=> 	'privacy-settings',
																																			),
																													),
																								),
															),
                                    ),

            'view_manager'     =>  array
                                   (
										'base_path' 			=>  BASE_PATH,
                                        'template_map'          =>  array
                                                                    (
                                                                        'layout/notary'         =>  __DIR__ . '/../view/layout/cloud-layout.phtml',
                                                                        'layout/notary/empty'   =>  __DIR__ . '/../view/layout/empty-layout.phtml',
                                                                        'notary/notary/index'   =>  __DIR__ . '/../view/notary/notary/index.phtml',
                                                                        'layout/error'          =>  __DIR__ . '/../view/layout/cloud-layout.phtml',
                                                                        'error/custom'          =>  __DIR__ . '/../view/error/custom.phtml',
                                                                        'error/400'             =>  __DIR__ . '/../view/error/404.phtml',
                                                                        'error/404'             =>  __DIR__ . '/../view/error/404.phtml',
                                                                        'error/index'           =>  __DIR__ . '/../view/error/index.phtml',
                                                                    ),
                                       	'template_path_stack'  =>  	array
                                                                   	(
                                                                       	'notary'                 =>  __DIR__ . '/../view',
                                                                   	),
										'strategies' 			=> 	array
																	(
																		'ViewJsonStrategy',
																	),
                                   ),
        );