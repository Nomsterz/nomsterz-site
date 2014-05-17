<?php
return  array
        (
            'controllers'       =>  array
                                    (
                                        'invokables'    =>  array
                                                            (
                                                                 'Member\Controller\Member' => 'Member\Controller\MemberController',
                                                            ),
                                    ),

            'router'            =>  array
                                    (
                                        'routes'    =>  array
                                                        (
                                                            'get-member-home'       				=>  array
																										(
																											'type'      =>  'segment',
																											'options'   =>  array
																															(
																																'route'         =>  '/MemberHome',
																																'defaults'      =>  array
																																					(
																																						'controller' => 'Member\Controller\Member',
																																						'action'     => 'get-member-home',
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
                                                                        'layout/member'         =>  __DIR__ . '/../view/layout/cloud-layout.phtml',
                                                                        'layout/member/empty'   =>  __DIR__ . '/../view/layout/empty-layout.phtml',
                                                                        'member/member/index'   =>  __DIR__ . '/../view/member/member/index.phtml',
                                                                        'error/404'             =>  __DIR__ . '/../view/error/404.phtml',
                                                                        'error/index'           =>  __DIR__ . '/../view/error/index.phtml',
                                                                    ),
                                       'template_path_stack'   =>  array
                                                                   (
                                                                       'member'                 =>  __DIR__ . '/../view',
                                                                   ),
                                   ),
        );