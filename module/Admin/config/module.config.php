<?php
return  array
        (
            'controllers'       =>  array
                                    (
                                        'invokables'    =>  array
                                                            (
                                                                 'Admin\Controller\Admin' => 'Admin\Controller\AdminController',
                                                            ),
                                    ),

            'router'            =>  array
                                    (
                                        'routes'    =>  array
                                                        (
                                                            'admin'                 =>  array
                                                                                        (
                                                                                            'type'      =>  'segment',
                                                                                            'options'   =>  array
                                                                                                            (
                                                                                                                'route'         =>  '/administration',
                                                                                                                'defaults'      =>  array
                                                                                                                                    (
                                                                                                                                        'controller' => 'Admin\Controller\Admin',
                                                                                                                                        'action'     => 'index',
                                                                                                                                    ),
                                                                                                            ),
                                                                                        ),
                                                            'admin-login'           =>  array
                                                                                        (
                                                                                            'type'      =>  'segment',
                                                                                            'options'   =>  array
                                                                                                            (
                                                                                                                'route'         =>  '/employee-login',
                                                                                                                'defaults'      =>  array
                                                                                                                                    (
                                                                                                                                        'controller' => 'Admin\Controller\Admin',
                                                                                                                                        'action'     => 'login',
                                                                                                                                    ),
                                                                                                            ),
                                                                                        ),
                                                            'admin-logout'          =>  array
                                                                                        (
                                                                                            'type'      =>  'segment',
                                                                                            'options'   =>  array
                                                                                                            (
                                                                                                                'route'         =>  '/EmployeeLogout',
                                                                                                                'defaults'      =>  array
                                                                                                                                    (
                                                                                                                                        'controller' => 'Admin\Controller\Admin',
                                                                                                                                        'action'     => 'employee-logout',
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
                                                                        'layout/admin'          =>  __DIR__ . '/../view/layout/cloud-layout.phtml',
                                                                        'layout/admin/empty'    =>  __DIR__ . '/../view/layout/empty-layout.phtml',
                                                                        'admin/admin/index'     =>  __DIR__ . '/../view/admin/admin/index.phtml',
                                                                        'error/404'             =>  __DIR__ . '/../view/error/404.phtml',
                                                                        'error/index'           =>  __DIR__ . '/../view/error/index.phtml',
                                                                    ),
                                       'template_path_stack'   =>  array
                                                                   (
                                                                       'admin'                     =>  __DIR__ . '/../view',
                                                                   ),
                                   ),
        );