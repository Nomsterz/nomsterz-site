<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return  array
        (
            'module_layouts'    =>  array
                                    (
                                        'Application'   =>  'layout/layout',
                                        'Admin'         =>  'layout/admin',
                                        'Auth'          =>  'layout/auth',
                                        'Member'        =>  'layout/member',
                                        'Business'      =>  'layout/business',
                                        'Customer'      =>  'layout/customer',
                                    ),
            'service_manager'   =>  array
                                    (
                                        'abstract_factories'    =>  array
                                                                    (
                                                                        'Zend\Db\Adapter\AdapterAbstractServiceFactory',
                                                                    ),
                                        'factories'             =>  array
                                                                    (

                                                                    ),
                                    ),
            'session_config'    =>  array
                                    (
                                        'cache_expire'          =>  60*60*5,
                                        //'cookie_domain' => 'localhost',
                                        'name'                  =>  'nomsterzSession',
                                        'cookie_lifetime'       =>  60*60*5,
                                        'gc_maxlifetime'        =>  60*60*5,
                                        'cookie_path'           =>  '/',
                                        'cookie_secure'         =>  FALSE,
                                        'remember_me_seconds'   =>  60*60*5,
                                        'use_cookies'           =>  true,
                                    ),
        );
