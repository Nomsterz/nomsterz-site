<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return  array
        (
            'router'            =>  array
                                    (
                                        'routes'    =>  array
                                                        (
                                                            'home'              =>  array
                                                                                    (
                                                                                        'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                        'options'   =>  array
                                                                                                        (
                                                                                                            'route'     =>  '/',
                                                                                                            'defaults'  =>  array
                                                                                                                            (
                                                                                                                                'controller'    =>  'Application\Controller\Index',
                                                                                                                                'action'        =>  'index',
                                                                                                                            ),
                                                                                                        ),
                                                                                    ),

                                                            /**
                                                             * Errors
                                                             */
                                                            'custom-error-1'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/1',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '1',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-2'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/2',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '2',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-3'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/3',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '3',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-4'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/4',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '4',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-5'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/5',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '5',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-6'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/6',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '6',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-7'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/7',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '7',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-8'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/8',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '8',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-9'                        =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/9',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '9',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-10'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/10',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '10',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-11'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/11',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '11',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-12'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/12',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '12',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-13'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/13',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '13',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-14'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/14',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '14',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-15'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/15',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '15',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-16'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/16',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '16',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-17'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/17',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '17',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-18'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/18',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '18',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-19'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/19',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '19',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-20'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/20',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '20',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-21'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/21',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '21',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-22'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/22',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '22',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-23'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/23',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '23',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-24'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/24',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '24',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-25'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/25',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '25',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-26'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/26',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '26',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-27'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/27',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '27',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-28'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/28',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '28',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-29'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/29',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '29',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'custom-error-30'                       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/There-was-a-problem/30',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  '30',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'access-temp-disabled'  				=>  array
																										(
																											'type'      =>  'Zend\Mvc\Router\Http\Literal',
																											'options'   =>  array
																															(
																																'route'     =>  '/AccessTemporarilyDisabled',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  'accessTempDisabled',
                                                                                                                                                ),
																															),
																										),
                                                            'access-perm-disabled'  				=>  array
																										(
																											'type'      =>  'Zend\Mvc\Router\Http\Literal',
																											'options'   =>  array
																															(
																																'route'     =>  '/AccessPermanentlyDisabled',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'process-errors',
                                                                                                                                                    'errorNbr'      =>  'accessPermDisabled',
                                                                                                                                                ),
																															),
																										),


                                                            'what-we-do-for-notaries'               =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/what-we-do-for-notaries',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'whatwedo',
                                                                                                                                                    'customer'      =>  'notaries',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'what-we-do-for-signing-agencies'       =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/what-we-do-for-signing-agencies',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'whatwedo',
                                                                                                                                                    'customer'      =>  'signing-agencies',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'what-we-do-for-lenders'                =>  array
                                                                                                        (
                                                                                                            'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                            'options'   =>  array
                                                                                                                            (
                                                                                                                                'route'     =>  '/what-we-do-for-lenders',
                                                                                                                                'defaults'  =>  array
                                                                                                                                                (
                                                                                                                                                    'controller'    =>  'Application\Controller\Index',
                                                                                                                                                    'action'        =>  'whatwedo',
                                                                                                                                                    'customer'      =>  'lenders',
                                                                                                                                                ),
                                                                                                                            ),
                                                                                                        ),
                                                            'contact-us-faq'                            =>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/faq',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'faq',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),
                                                            'contact-us-suggestions'                    =>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/give-us-your-suggestions',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'suggestions',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),
                                                            'contact-us-customer-support'               =>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/customer-support',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'customersupport',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),
                                                            'member-already-exists'                     =>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/Check-Your-Inbox',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'member-already-exists',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),
                                                            'member-signup-success'                     =>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/Welcome-to-Nomsterz',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'signup-success',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),
                                                            'member-signup-again-success'               =>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/Welcome-Again-to-Nomsterz',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'signup-success',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),
                                                            'verification-details-success'              =>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/Get-Started-With-Your-90day-Free-Trial',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'verification-details-success',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),
                                                            'reset-verified-password-success'      		=>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/YourPasswordIsReset-WelcomeBack',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'reset-password-success',
																																						'activity'		=>	'verified-link',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),
                                                            'reset-old-password-success'      			=>  array
                                                                                                            (
                                                                                                                'type'      =>  'Zend\Mvc\Router\Http\Literal',
                                                                                                                'options'   =>  array
                                                                                                                                (
                                                                                                                                    'route'     =>  '/YourNewPasswordIsActive',
                                                                                                                                    'defaults'  =>  array
                                                                                                                                                    (
                                                                                                                                                        'controller'    =>  'Application\Controller\Index',
                                                                                                                                                        'action'        =>  'reset-password-success',
																																						'activity'		=>	'old-password',
                                                                                                                                                    ),
                                                                                                                                ),
                                                                                                            ),

                                                            // The following is a route to simplify getting started creating
                                                            // new controllers and actions without needing to create a new
                                                            // module. Simply drop new controllers in, and you can access them
                                                            // using the path /application/:controller/:action
                                                            'application'       =>  array
                                                                                    (
                                                                                        'type'              =>  'Literal',
                                                                                        'options'           =>  array
                                                                                                                (
                                                                                                                    'route'     =>  '/application',
                                                                                                                    'defaults'  =>  array
                                                                                                                                    (
                                                                                                                                        '__NAMESPACE__' => 'Application\Controller',
                                                                                                                                        'controller'    => 'Index',
                                                                                                                                        'action'        => 'index',
                                                                                                                                    ),
                                                                                                                ),
                                                                                        'may_terminate'     =>  true,
                                                                                        'child_routes'      =>  array
                                                                                                                (
                                                                                                                    'default'   =>  array
                                                                                                                                    (
                                                                                                                                        'type'      =>  'Segment',
                                                                                                                                        'options'   =>  array
                                                                                                                                                        (
                                                                                                                                                            'route'         =>  '/[:controller[/:action]]',
                                                                                                                                                            'constraints'   =>  array
                                                                                                                                                                                (
                                                                                                                                                                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                                                                                                                                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                                                                                                                                                ),
                                                                                                                                                            'defaults'      =>  array
                                                                                                                                                                                (
                                                                                                                                                                                ),
                                                                                                                                                        ),
                                                                                                                                    ),
                                                                                                                ),
                                                                                    ),
                                                        ),
                                    ),
            'service_manager'   =>  array
                                    (
                                        'abstract_factories'    =>  array
                                                                    (
                                                                        'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
                                                                        'Zend\Log\LoggerAbstractServiceFactory',
                                                                    ),
                                        'aliases'               =>  array
                                                                    (
                                                                        'translator'    =>  'MvcTranslator',
                                                                    ),
                                    ),
            'translator'        =>  array
                                    (
                                        'locale' => 'en_US',
                                        'translation_file_patterns' => array(
                                            array(
                                                'type'     => 'gettext',
                                                'base_dir' => __DIR__ . '/../language',
                                                'pattern'  => '%s.mo',
                                            ),
                                        ),
                                    ),
            'controllers'       =>  array
                                    (
                                        'invokables'    =>  array
                                                            (
                                                                'Application\Controller\Index'  =>  'Application\Controller\IndexController',
                                                            ),
                                    ),
            'view_manager'      =>  array
                                    (
										'base_path' 				=>  BASE_PATH,
                                        'display_not_found_reason'  =>  true,
                                        'display_exceptions'        =>  true,
                                        'doctype'                   =>  'HTML5',
                                        'not_found_template'        =>  'error/404',
                                        'exception_template'        =>  'error/index',
                                        'template_map'              =>  array
                                                                        (
                                                                            'layout/layout'             =>  __DIR__ . '/../view/layout/goodnex-layout.phtml',
                                                                            'layout/layout/empty'       =>  __DIR__ . '/../view/layout/empty-layout.phtml',
                                                                            'application/index/index'   =>  __DIR__ . '/../view/application/index/index.phtml',
                                                                            'error/404'                 =>  __DIR__ . '/../view/error/404.phtml',
                                                                            'error/index'               =>  __DIR__ . '/../view/error/index.phtml',
                                                                            'application/error/custom'  =>  __DIR__ . '/../view/error/custom-errors.phtml',
                                                                        ),
                                        'template_path_stack'       =>  array
                                                                        (
                                                                            __DIR__ . '/../view',
                                                                        ),
                                    ),
            // Placeholder for console routes
            'console'           =>  array
                                    (
                                        'router' => array(
                                            'routes' => array(
                                            ),
                                        ),
                                    ),
        );
