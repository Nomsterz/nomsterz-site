<?php
return  array
        (
            'controllers'       =>  array
                                    (
                                        'invokables'    =>  array
                                                            (
                                                                 'Auth\Controller\Auth' => 'Auth\Controller\AuthController',
                                                            ),
                                    ),

            'router'            =>  array
                                    (
                                        'routes'    =>  array
                                                        (
                                                            'member-login'           				=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/login',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Auth\Controller\Auth',
																																						'action'     => 'index',
																																						'activity'   => 'login',
																																					),
																															),
																										),
                                                            'member-login-after-expired-session'	=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/loginAgain',
																																'defaults'      =>  array
																																					(
																																						'controller' 	=> 'Auth\Controller\Auth',
																																						'action'     	=> 'index',
																																						'activity'   	=> 'login',
																																						'reason'   		=> 'expired-session',
																																					),
																															),
																										),
                                                            'member-login-after-intentional-logout'	=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/YouHaveSuccessfullyLoggedOut',
																																'defaults'      =>  array
																																					(
																																						'controller' 	=> 'Auth\Controller\Auth',
																																						'action'     	=> 'index',
																																						'activity'   	=> 'login',
																																						'reason'   		=> 'intentional-logout',
																																					),
																															),
																										),
                                                            'member-login-after-changed-password'	=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/YouHaveSuccessfullyChangedYourAccessCreds',
																																'defaults'      =>  array
																																					(
																																						'controller' 	=> 'Auth\Controller\Auth',
																																						'action'     	=> 'index',
																																						'activity'   	=> 'login',
																																						'reason'   		=> 'changed-password',
																																					),
																															),
																										),
                                                            'member-login-captcha'  				=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/LoginWithCaptcha',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Auth\Controller\Auth',
																																						'action'     => 'index',
																																						'activity'   => 'login-captcha',
																																					),
																															),
																										),
                                                            'member-logout-expired-session'  		=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/YourMemberSessionHasExpired',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Auth\Controller\Auth',
																																						'action'     => 'member-logout-expired-session',
																																					),
																															),
																										),
                                                            'member-logout'         				=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/MemberLogout',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Auth\Controller\Auth',
																																						'action'     => 'member-logout',
																																					),
																															),
																										),
                                                            'member-signup'         				=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/signup',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Auth\Controller\Auth',
																																						'action'     => 'index',
																																						'activity'   => 'signup',
																																					),
																														),
																										),
                                                            'member-forgot'         				=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/forgot',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Auth\Controller\Auth',
																																						'action'     => 'index',
																																						'activity'   => 'forgot',
																																					),
																															),
																										),
                                                            'member-reset-password'         		=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/reset-password',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Auth\Controller\Auth',
																																						'action'     => 'index',
																																						'activity'   => 'forgot',
																																					),
																															),
																										),
                                                            'force-change-password-2'         		=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/password-change',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Auth\Controller\Auth',
																																						'action'     => 'change-password-with-old-password',
																																					),
																															),
																										),
                                                            'email-verification'    				=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/email-verification/:vcode',
																																'defaults'      =>  array
																																					(
																																						'controller'    => 'Auth\Controller\Auth',
																																						'action'        => 'verify-email',
																																						'vcode'         => 'default',
																																					),
																															),
																										),
                                                            'change-password-verification'    		=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/change-password-verification/:vcode',
																																'defaults'      =>  array
																																					(
																																						'controller'    => 'Auth\Controller\Auth',
																																						'action'     	=> 'change-password-with-verify-email-link',
																																						'vcode'         => 'default',
																																					),
																															),
																										),
                                                            'verified-email-already-exists'  		=>  array
																										(
																											'type'      =>  'Zend\Mvc\Router\Http\Literal',
																											'options'   =>  array
																															(
																																'route'     =>  '/Verification-Details',
																																'defaults'  =>  array
																																				(
																																					'controller'    =>  'Auth\Controller\Auth',
																																					'action'        =>  'process-verification-details',
																																				),
																															),
																										),
                                                            'resend-signup-confirmation'  			=>  array
																										(
																											'type'      =>  'Zend\Mvc\Router\Http\Literal',
																											'options'   =>  array
																															(
																																'route'     =>  '/resendSignupConfirmation',
																																'defaults'  =>  array
																																				(
																																					'controller'    =>  'Auth\Controller\Auth',
																																					'action'        =>  'lost-signup-verification',
																																				),
																															),
																										),
                                                        ),
                                    ),

            'view_manager'     =>   array
                                    (
										'base_path' 			=>  BASE_PATH,
                                        'template_map'          =>  array
                                                                    (
                                                                        'layout/auth'       =>  __DIR__ . '/../view/layout/empty-layout.phtml',
                                                                        'auth/auth/login'   =>  __DIR__ . '/../view/auth/auth/login.phtml',
                                                                        'error/404'         =>  __DIR__ . '/../view/error/404.phtml',
                                                                        'error/index'       =>  __DIR__ . '/../view/error/index.phtml',

																		/**
																		 * Email Templates
																		 */
																		'auth/email/template/verify-new-member/html'       								=>  __DIR__ . '/../view/email_templates/verify-new-member-html.phtml',
                                                                        'auth/email/template/verify-new-member/text'       								=>  __DIR__ . '/../view/email_templates/verify-new-member-text.phtml',

                                                                        'auth/email/template/verify-new-member-again/html'       						=>  __DIR__ . '/../view/email_templates/verify-new-member-again-html.phtml',
                                                                        'auth/email/template/verify-new-member-again/text'       						=>  __DIR__ . '/../view/email_templates/verify-new-member-again-text.phtml',

                                                                        'auth/email/template/excessive-logins/html'       								=>  __DIR__ . '/../view/email_templates/excessive-logins-html.phtml',
                                                                        'auth/email/template/excessive-logins/text'       								=>  __DIR__ . '/../view/email_templates/excessive-logins-text.phtml',

                                                                        'auth/email/template/excessive-signups/html'       								=>  __DIR__ . '/../view/email_templates/excessive-signups-html.phtml',
                                                                        'auth/email/template/excessive-signups/text'       								=>  __DIR__ . '/../view/email_templates/excessive-signups-text.phtml',

                                                                        'auth/email/template/excessive-forgot-logins/html'  							=>  __DIR__ . '/../view/email_templates/excessive-forgot-logins-html.phtml',
                                                                        'auth/email/template/excessive-forgot-logins/text'								=>  __DIR__ . '/../view/email_templates/excessive-forgot-logins-text.phtml',

                                                                        'auth/email/template/excessive-change-verified-member-password/html'  			=>  __DIR__ . '/../view/email_templates/excessive-change-verified-member-password-html.phtml',
                                                                        'auth/email/template/excessive-change-verified-member-password/text'			=>  __DIR__ . '/../view/email_templates/excessive-change-verified-member-password-text.phtml',

                                                                        'auth/email/template/excessive-change-old-member-password/html'  				=>  __DIR__ . '/../view/email_templates/excessive-change-old-member-password-html.phtml',
                                                                        'auth/email/template/excessive-change-old-member-password/text'					=>  __DIR__ . '/../view/email_templates/excessive-change-old-member-password-text.phtml',

                                                                        'auth/email/template/excessive-lost-signup-verification/html'  					=>  __DIR__ . '/../view/email_templates/excessive-lost-signup-verification-html.phtml',
                                                                        'auth/email/template/excessive-lost-signup-verification/text'					=>  __DIR__ . '/../view/email_templates/excessive-lost-signup-verification-text.phtml',

                                                                        'auth/email/template/forgot-logins-success/html'  								=>  __DIR__ . '/../view/email_templates/forgot-logins-success-html.phtml',
                                                                        'auth/email/template/forgot-logins-success/text'								=>  __DIR__ . '/../view/email_templates/forgot-logins-success-text.phtml',

                                                                        'auth/email/template/generic-profile-information-change/html'  					=>  __DIR__ . '/../view/email_templates/generic-profile-information-change-html.phtml',
                                                                        'auth/email/template/generic-profile-information-change/text'					=>  __DIR__ . '/../view/email_templates/generic-profile-information-change-text.phtml',

                                                                        'auth/email/template/generic-password-change/html'  							=>  __DIR__ . '/../view/email_templates/generic-password-change-html.phtml',
                                                                        'auth/email/template/generic-password-change/text'								=>  __DIR__ . '/../view/email_templates/generic-password-change-text.phtml',
                                                                    ),
                                        'template_path_stack'   =>  array
                                                                    (
                                                                        'auth'              =>  __DIR__ . '/../view',
                                                                    ),
                                    ),
        );