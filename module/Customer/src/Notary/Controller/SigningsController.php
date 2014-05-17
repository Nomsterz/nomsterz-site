<?php
/**
 * Class SigningsController
 *
 * filename:   SigningsController.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       3/28/14 11:18 AM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Customer\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mail;
 
class SigningsController extends AbstractNotaryController
{
	public function indexAction()
	{


		$this->layout()->actionSpecificCSSFilesArray 		= 	array
																(
																	'/nomsterz/notary-view/notary/signings/css/index.css',
																	'/nomsterz/notary-view/notary-module.css',

																	/**
																	 * Data Tables
																	 */
																	'/notary/js/datatables/media/css/jquery.dataTables.min.css',
																	'/notary/js/datatables/media/assets/css/datatables.min.css',
																	'/notary/js/datatables/extras/TableTools/media/css/TableTools.min.css',

																	/**
																	 * Charts
																	 */


																);
		$this->layout()->actionSpecificJSFilesTopArray 		= 	array
																(
																	/*
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/signings/js/index.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
			 														*/

																	/**
																	 * Charts
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/flot/excanvas.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array
																							(
																								'conditional' 	=> 	'lt IE 9',
																							),
																	),
																);
		$this->layout()->actionSpecificJSFilesBottomArray 	= 	array
																(
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/signings/js/index.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																	/**
																	 * Data Tables
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/datatables/extras/TableTools/media/js/ZeroClipboard.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/extras/TableTools/media/js/TableTools.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/media/assets/js/datatables.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/media/js/jquery.dataTables.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																	/**
																	 * Charts
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/flot/jquery.flot.categories.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/flot/jquery.flot.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																);

		$this->layout()->turnOnFlotCharts 					= 	TRUE;
		$this->layout()->cloudLayoutJSPageName 				= 	'SigningsSectionStartPage';
		$this->layout()->ModuleDirectoryReference			= 	'notary/';

		$viewModel  =   new ViewModel
                        (
                            array
                            (
                                'identity'     				=>  $this->notaryID,
                                'notaryFirstName'			=>  $this->notaryFirstName,
                                'notaryLastName'    		=>  $this->notaryLastName,
                                'notaryFullName'    		=>  $this->notaryFullName,
                                'notaryHomeLink'    		=>  $this->notaryHomeLink,
                                'notaryProfileLink'    		=>  $this->notaryProfileLink,

								'totalCompletedSignings'	=>	'123 Signings',
								'totalRevenue'				=>	'123 Signings',
								'totalWebsiteViews'			=>	'123 Signings',
								'avgSigningRevenue'			=>	'123 Signings',
								'totalDocsGenerated'		=>	'123 Signings',
								'totalResourceUnits'		=>	'123 Signings',
								'totalHelpRequests'			=>	'123 Signings',
                            )
                        );

        return $viewModel;
	}


	public function signingOrdersAction()
	{

		$this->layout()->actionSpecificCSSFilesArray 		= 	array
																(
																	'/nomsterz/notary-view/notary/signings/css/index.css',
																	'/nomsterz/notary-view/notary-module.css',

																	/**
																	 * Data Tables
																	 */
																	'/notary/js/datatables/media/css/jquery.dataTables.min.css',
																	'/notary/js/datatables/media/assets/css/datatables.min.css',
																	'/notary/js/datatables/extras/TableTools/media/css/TableTools.min.css',
																);
		$this->layout()->actionSpecificJSFilesTopArray 		= 	array
																(
																	/*
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/signings/js/index.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
			 														*/

																	/**
																	 * Charts
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/flot/excanvas.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array
																							(
																								'conditional' 	=> 	'lt IE 9',
																							),
																	),
																);
		$this->layout()->actionSpecificJSFilesBottomArray 	= 	array
																(
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/signings/js/index.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																	/**
																	 * Data Tables
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/datatables/extras/TableTools/media/js/ZeroClipboard.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/extras/TableTools/media/js/TableTools.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/media/assets/js/datatables.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/media/js/jquery.dataTables.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																	/**
																	 * Charts
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/flot/jquery.flot.categories.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/flot/jquery.flot.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																);

		$this->layout()->cloudLayoutJSPageName 				= 	'SigningsSectionSigningOrdersPage';
		$this->layout()->ModuleDirectoryReference			= 	'notary/';

		$viewModel  =   new ViewModel
                        (
                            array
                            (
                                'identity'     				=>  $this->notaryID,
                                'notaryFirstName'			=>  $this->notaryFirstName,
                                'notaryLastName'    		=>  $this->notaryLastName,
                                'notaryFullName'    		=>  $this->notaryFullName,
                                'notaryHomeLink'    		=>  $this->notaryHomeLink,
                                'notaryProfileLink'    		=>  $this->notaryProfileLink,

								'totalCompletedSignings'	=>	'123 Signings',
								'totalRevenue'				=>	'123 Signings',
								'totalWebsiteViews'			=>	'123 Signings',
								'avgSigningRevenue'			=>	'123 Signings',
								'totalDocsGenerated'		=>	'123 Signings',
								'totalResourceUnits'		=>	'123 Signings',
								'totalHelpRequests'			=>	'123 Signings',
                            )
                        );

        return $viewModel;

	}


	public function signingSourcesAction()
	{

		$this->layout()->actionSpecificCSSFilesArray 		= 	array
																(
																	'/nomsterz/notary-view/notary/signings/css/index.css',
																	'/nomsterz/notary-view/notary-module.css',

																	/**
																	 * Data Tables
																	 */
																	'/notary/js/datatables/media/css/jquery.dataTables.min.css',
																	'/notary/js/datatables/media/assets/css/datatables.min.css',
																	'/notary/js/datatables/extras/TableTools/media/css/TableTools.min.css',
																);
		$this->layout()->actionSpecificJSFilesTopArray 		= 	array
																(
																	/*
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/signings/js/index.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
			 														*/

																	/**
																	 * Charts
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/flot/excanvas.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array
																							(
																								'conditional' 	=> 	'lt IE 9',
																							),
																	),
																);
		$this->layout()->actionSpecificJSFilesBottomArray 	= 	array
																(
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/signings/js/index.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																	/**
																	 * Data Tables
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/datatables/extras/TableTools/media/js/ZeroClipboard.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/extras/TableTools/media/js/TableTools.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/media/assets/js/datatables.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/datatables/media/js/jquery.dataTables.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																	/**
																	 * Charts
																	 */
																	array
																	(
																		'url'			=>	'/notary/js/flot/jquery.flot.categories.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/flot/jquery.flot.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																);
		$this->layout()->cloudLayoutJSPageName 				= 	'SigningsSectionSigningSourcesPage';
		$this->layout()->ModuleDirectoryReference			= 	'notary/';

		$viewModel  =   new ViewModel
                        (
                            array
                            (
                                'identity'     				=>  $this->notaryID,
                                'notaryFirstName'			=>  $this->notaryFirstName,
                                'notaryLastName'    		=>  $this->notaryLastName,
                                'notaryFullName'    		=>  $this->notaryFullName,
                                'notaryHomeLink'    		=>  $this->notaryHomeLink,
                                'notaryProfileLink'    		=>  $this->notaryProfileLink,

								'totalCompletedSignings'	=>	'123 Signings',
								'totalRevenue'				=>	'123 Signings',
								'totalWebsiteViews'			=>	'123 Signings',
								'avgSigningRevenue'			=>	'123 Signings',
								'totalDocsGenerated'		=>	'123 Signings',
								'totalResourceUnits'		=>	'123 Signings',
								'totalHelpRequests'			=>	'123 Signings',
                            )
                        );

        return $viewModel;

	}
}
