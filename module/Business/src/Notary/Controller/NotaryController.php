<?php
/**
 * Class NotaryController
 *
 * filename:   NotaryController.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       3/11/14 4:52 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Business\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Mail;

use Business\Form\ProfileForm;
 
class NotaryController extends AbstractNotaryController
{
	public function indexAction()
	{
		$totalCompletedSignings 	=	1;
		$totalRevenue 				=	123;
		$totalWebsiteViews 			=	123;
		$avgSigningRevenue 			=	123;
		$totalDocsGenerated 		=	123;
		$totalResourceUnits 		=	123;
		$totalHelpRequests 			=	123;

		/**
		 * Dynamic Text must have four variations:
		 * Large
		 * Medium
		 * Small
		 * Extra-Small
		 */

		/**
		 * Signings Button
		 */
		$displaySigningsButton	=	TRUE;
		if($displaySigningsButton)
		{
			$signingsSectionButtonText_xs 	=	($totalCompletedSignings > 0
													? (string) $totalCompletedSignings
													: '0') .
												' Signing';
			$signingsSectionButtonText_sm 	=	($totalCompletedSignings > 0
													? (string) $totalCompletedSignings
													: '0') .
												' Signing' .
												($totalCompletedSignings == 1
													? ''
													: 's') .
												' Complete.';
			$signingsSectionButtonText_md 	=	'You have completed ' .
												($totalCompletedSignings > 0
													? (string) $totalCompletedSignings
													: '0') .
												' Signing' .
												($totalCompletedSignings == 1
													? ''
													: 's') .
												'.';
			$signingsSectionButtonText_lg 	=	'You have completed ' .
												($totalCompletedSignings > 0
													? (string) $totalCompletedSignings
													: '0') .
												' Signing' .
												($totalCompletedSignings == 1
													? ''
													: 's') .
												'. Add more.';
		}
		else
		{
			$signingsSectionButtonText_xs 	=	'';
			$signingsSectionButtonText_sm 	=	'';
			$signingsSectionButtonText_md 	=	'';
			$signingsSectionButtonText_lg 	=	'';
		}

		/**
		 * Business Button
		 */
		$displayBusinessButton	=	TRUE;
		if($displayBusinessButton)
		{
			$businessSectionButtonText_xs 	=	'Revenues: $' .
												($totalRevenue > 0
													? (string) $totalRevenue
													: '0');
			$businessSectionButtonText_sm 	=	'Revenues: $' .
												($totalRevenue > 0
													? (string) $totalRevenue
													: '0') .
												' Earned.';
			$businessSectionButtonText_md 	=	'You earned $' .
												($totalRevenue > 0
													? (string) $totalRevenue
													: '0') .
												'  in Revenues.';
			$businessSectionButtonText_lg 	=	'You made $' .
												($totalRevenue > 0
													? (string) $totalRevenue
													: '0') .
												'  in Total Revenues. Make more.';
		}
		else
		{
			$businessSectionButtonText_xs 	=	'';
			$businessSectionButtonText_sm 	=	'';
			$businessSectionButtonText_md 	=	'';
			$businessSectionButtonText_lg 	=	'';
		}

		/**
		 * Website Button
		 */
		$displayWebsiteButton	=	TRUE;
		if( $displayWebsiteButton )
		{
			$websiteSectionButtonText_xs	= 	'Views: ' .
												($totalWebsiteViews > 0
													? (string) $totalWebsiteViews
													: '0');
			$websiteSectionButtonText_sm	= 	'Views: ' .
												($totalWebsiteViews > 0
													? (string) $totalWebsiteViews
													: '0') .
												' time' .
												($totalWebsiteViews == 1
													? ''
													: 's');
			$websiteSectionButtonText_md	= 	'Site Views: ' .
												($totalWebsiteViews > 0
													? (string) $totalWebsiteViews
													: '0') .
												' time' .
												($totalWebsiteViews == 1
													? ''
													: 's') .
												'.';
			$websiteSectionButtonText_lg	= 	'Your site has been viewed ' .
												($totalWebsiteViews > 0
													? (string) $totalWebsiteViews
													: '0') .
												' time' .
												($totalWebsiteViews == 1
													? ''
													: 's') .
												'. Tweak it!';
		}
		else
		{
			$websiteSectionButtonText_xs 	=	'';
			$websiteSectionButtonText_sm 	=	'';
			$websiteSectionButtonText_md 	=	'';
			$websiteSectionButtonText_lg 	=	'';
		}

		/**
		 * Numbers Button
		 */
		$displayNumbersButton	=	TRUE;
		if( $displayNumbersButton )
		{
			$dataSectionButtonText_xs	= 	'Fee Avg: $' . 
											($avgSigningRevenue > 0 
												? (string) $avgSigningRevenue
												: '0');
			$dataSectionButtonText_sm	= 	'Fee Average $' . 
											($avgSigningRevenue > 0 
												? (string) $avgSigningRevenue
												: '0');
			$dataSectionButtonText_md	= 	'You average $' . 
											($avgSigningRevenue > 0 
												? (string) $avgSigningRevenue
												: '0') . 
											' per signing.';
			$dataSectionButtonText_lg	= 	'You average $' . 
											($avgSigningRevenue > 0 
												? (string) $avgSigningRevenue
												: '0') . 
											' per signing. Build on this.';
		}
		else
		{
			$dataSectionButtonText_xs 	=	'';
			$dataSectionButtonText_sm 	=	'';
			$dataSectionButtonText_md 	=	'';
			$dataSectionButtonText_lg 	=	'';
		}

		/**
		 * Document Button
		 */
		$displayDocumentButton	=	TRUE;
		if( $displayDocumentButton )
		{
			$documentSectionButtonText_xs	= 	'Documents: ' .
												($totalDocsGenerated > 0
													? (string) $totalDocsGenerated
													: '0');
			$documentSectionButtonText_sm	= 	'Documents: ' .
												($totalDocsGenerated > 0
													? (string) $totalDocsGenerated
													: '0') .
												' Generated';
			$documentSectionButtonText_md	= 	'Docs Generated: ' .
												($totalDocsGenerated > 0
													? (string) $totalDocsGenerated
													: '0');
			$documentSectionButtonText_lg	= 	'You have generated ' .
												($totalDocsGenerated > 0
													? (string) $totalDocsGenerated
													: '0') .
												' ' .
												($totalDocsGenerated == 1 ? 'doc or report' : 'docs and reports') .
												'. Get more.';
		}
		else
		{
			$documentSectionButtonText_xs 	=	'';
			$documentSectionButtonText_sm 	=	'';
			$documentSectionButtonText_md 	=	'';
			$documentSectionButtonText_lg 	=	'';
		}

		/**
		 * Resources Button
		 */
		$displayResourcesButton	=	TRUE;
		if( $displayResourcesButton )
		{
			$resourceSectionButtonText_xs	= 	'Resources: ' .
												($totalResourceUnits > 0
													? (string) $totalResourceUnits
													: '0') .
												' Unit' .
												($totalResourceUnits == 1
													? ''
													: 's') .
												'.';
			$resourceSectionButtonText_sm	= 	'Resources: ' .
												($totalResourceUnits > 0 
													? (string) $totalResourceUnits
													: '0') . 
												' Unit' .
												($totalResourceUnits == 1 
													? '' 
													: 's') . 
												' Tracked.';
			$resourceSectionButtonText_md	= 	'You are tracking ' .
												($totalResourceUnits > 0 
													? (string) $totalResourceUnits
													: '0') . 
												' resource unit' . 
												($totalResourceUnits == 1 
													? '' 
													: 's') . 
												'.';
			$resourceSectionButtonText_lg	= 	'You are tracking ' .
												($totalResourceUnits > 0 
													? (string) $totalResourceUnits
													: '0') . 
												' resource unit' . 
												($totalResourceUnits == 1 
													? '' 
													: 's') . 
												'. Track more.';
		}
		else
		{
			$resourceSectionButtonText_xs 	=	'';
			$resourceSectionButtonText_sm 	=	'';
			$resourceSectionButtonText_md 	=	'';
			$resourceSectionButtonText_lg 	=	'';
		}

		/**
		 * Help Button
		 */
		$displayHelpButton	=	TRUE;
		if( $displayHelpButton )
		{
			$helpSectionButtonText_xs	= 	'Assisted ' .
											($totalHelpRequests > 0
												? (string) $totalHelpRequests
												: '0') .
											' time' .
											($totalHelpRequests == 1
												? ''
												: 's');
			$helpSectionButtonText_sm	= 	'Assisted ' .
											($totalHelpRequests > 0
												? (string) $totalHelpRequests
												: '0') .
											' time' .
											($totalHelpRequests == 1
												? ''
												: 's');
			$helpSectionButtonText_md	= 	'You have been helped ' .
											($totalHelpRequests > 0
												? (string) $totalHelpRequests
												: '0') .
											' time' .
											($totalHelpRequests == 1
												? ''
												: 's') .
											'.';
			$helpSectionButtonText_lg	= 	'You have been helped ' .
											($totalHelpRequests > 0
												? (string) $totalHelpRequests
												: '0') .
											' time' .
											($totalHelpRequests == 1
												? ''
												: 's') .
											'. We are glad to help.';
		}
		else
		{
			$helpSectionButtonText_xs 	=	'';
			$helpSectionButtonText_sm 	=	'';
			$helpSectionButtonText_md 	=	'';
			$helpSectionButtonText_lg 	=	'';
		}

		$this->layout()->actionSpecificCSSFilesArray 		= 	array
																(
																	'/nomsterz/notary-view/notary/notary/css/index.css',
																	'/nomsterz/notary-view/notary-module.css',
																);
		$this->layout()->actionSpecificJSFilesTopArray 		= 	array();
		$this->layout()->actionSpecificJSFilesBottomArray 	= 	array
																(
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/notary/css/index.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																);

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

								'signingsSectionButtonText_xs'		=>	$signingsSectionButtonText_xs,
								'signingsSectionButtonText_sm'		=>	$signingsSectionButtonText_sm,
								'signingsSectionButtonText_md'		=>	$signingsSectionButtonText_md,
								'signingsSectionButtonText_lg'		=>	$signingsSectionButtonText_lg,

								'businessSectionButtonText_xs'		=>	$businessSectionButtonText_xs,
								'businessSectionButtonText_sm'		=>	$businessSectionButtonText_sm,
								'businessSectionButtonText_md'		=>	$businessSectionButtonText_md,
								'businessSectionButtonText_lg'		=>	$businessSectionButtonText_lg,

								'websiteSectionButtonText_xs'		=>	$websiteSectionButtonText_xs,
								'websiteSectionButtonText_sm'		=>	$websiteSectionButtonText_sm,
								'websiteSectionButtonText_md'		=>	$websiteSectionButtonText_md,
								'websiteSectionButtonText_lg'		=>	$websiteSectionButtonText_lg,

								'dataSectionButtonText_xs'			=>	$dataSectionButtonText_xs,
								'dataSectionButtonText_sm'			=>	$dataSectionButtonText_sm,
								'dataSectionButtonText_md'			=>	$dataSectionButtonText_md,
								'dataSectionButtonText_lg'			=>	$dataSectionButtonText_lg,

								'documentSectionButtonText_xs'		=>	$documentSectionButtonText_xs,
								'documentSectionButtonText_sm'		=>	$documentSectionButtonText_sm,
								'documentSectionButtonText_md'		=>	$documentSectionButtonText_md,
								'documentSectionButtonText_lg'		=>	$documentSectionButtonText_lg,

								'resourceSectionButtonText_xs'		=>	$resourceSectionButtonText_xs,
								'resourceSectionButtonText_sm'		=>	$resourceSectionButtonText_sm,
								'resourceSectionButtonText_md'		=>	$resourceSectionButtonText_md,
								'resourceSectionButtonText_lg'		=>	$resourceSectionButtonText_lg,

								'helpSectionButtonText_xs'			=>	$helpSectionButtonText_xs,
								'helpSectionButtonText_sm'			=>	$helpSectionButtonText_sm,
								'helpSectionButtonText_md'			=>	$helpSectionButtonText_md,
								'helpSectionButtonText_lg'			=>	$helpSectionButtonText_lg,
                            )
                        );

        return $viewModel;
	}

	public function profileAction()
	{
		$ProfileForm          		=   new ProfileForm();
		$ProfileFormMessages      	=   '';
        $ProfileFormAttemptMessage  =   '';

		/**
		 * Default Profile Form Values
		 */
		$ProfileForm->get('prefix')				->setAttribute('value', $this->notaryNamePrefix);
		$ProfileForm->get('first_name')			->setAttribute('value', $this->notaryFirstName);
		$ProfileForm->get('mid_name1')			->setAttribute('value', $this->notaryMidName1);
		$ProfileForm->get('mid_name2')			->setAttribute('value', $this->notaryMidName2);
		$ProfileForm->get('last_name')			->setAttribute('value', $this->notaryLastName);
		$ProfileForm->get('display_name')		->setAttribute('value', $this->notaryDisplayName);
		$ProfileForm->get('suffix')				->setAttribute('value', $this->notaryNameSuffix);
		$ProfileForm->get('birth_date')			->setAttribute('value', $this->notaryFirstName);
		$ProfileForm->get('gender')				->setAttribute('value', $this->notaryGenderRaw);
		$ProfileForm->get('personal_summary')	->setAttribute('value', $this->notaryPersonalSummary);
		$ProfileForm->get('website')			->setAttribute('value', $this->notaryPersonalWebsiteLink);
		$ProfileForm->get('linkedin')			->setAttribute('value', $this->notarySocialLinkLinkedIn);
		$ProfileForm->get('google_plus')		->setAttribute('value', $this->notarySocialLinkGooglePlus);
		$ProfileForm->get('twitter')			->setAttribute('value', $this->notarySocialLinkTwitter);
		$ProfileForm->get('facebook')			->setAttribute('value', $this->notarySocialLinkFacebook);


		$this->layout()->actionSpecificCSSFilesArray 		= 	array
																(
																	'/nomsterz/notary-view/notary/notary/css/profile.css',
																	'/nomsterz/notary-view/notary-module.css',
																);
		$this->layout()->actionSpecificJSFilesTopArray 		= 	array();
		$this->layout()->actionSpecificJSFilesBottomArray 	= 	array
																(
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/notary/js/profile.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary-module.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),
																	array
																	(
																		'url'			=>	'/notary/js/countable/jquery.simplyCountable.min.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																);

		$this->layout()->turnOnFlotCharts 					= 	FALSE;
		$this->layout()->cloudLayoutJSPageName 				= 	'NotaryProfilePage';
		$this->layout()->ModuleDirectoryReference			= 	'notary/';

		$viewModel  =   new ViewModel
                        (
                            array
                            (
                                'identity'     					=>  $this->notaryID,
                                'notaryNamePrefix'				=>  $this->notaryNamePrefix,
                                'notaryFirstName'				=>  $this->notaryFirstName,
                                'notaryMidName1'				=>  $this->notaryMidName1,
                                'notaryMidName2'				=>  $this->notaryMidName2,
                                'notaryLastName'    			=>  $this->notaryLastName,
                                'notaryFullName'    			=>  $this->notaryFullName,
                                'notaryDisplayName'    			=>  $this->notaryDisplayName,
                                'notaryNameSuffix'    			=>  $this->notaryNameSuffix,

								'notaryGender'    				=>  $this->notaryGender,
								'notaryBirthDate'    			=>  $this->notaryBirthDate,

								'notaryPersonalSummary'    		=>  $this->notaryPersonalSummary,

								'notaryLargeProfilePicUrl'    	=>  $this->layout()->getVariable('notaryLargeProfilePicUrl'),
								'notaryMediumProfilePicUrl'     =>  $this->layout()->getVariable('notaryMediumProfilePicUrl'),
								'notarySmallProfilePicUrl'    	=>  $this->layout()->getVariable('notarySmallProfilePicUrl'),
								'notaryXSmallProfilePicUrl'     =>  $this->layout()->getVariable('notaryXSmallProfilePicUrl'),

								'notaryPersonalWebsiteLink'    	=>  $this->notaryPersonalWebsiteLink,
								'notarySocialLinkLinkedIn'    	=>  $this->notarySocialLinkLinkedIn,
								'notarySocialLinkGooglePlus'    =>  $this->notarySocialLinkGooglePlus,
								'notarySocialLinkTwitter'    	=>  $this->notarySocialLinkTwitter,
								'notarySocialLinkFacebook'    	=>  $this->notarySocialLinkFacebook,

                                'notaryHomeLink'    			=>  $this->notaryHomeLink,
                                'notaryProfileLink'    			=>  $this->notaryProfileLink,

								'ProfileForm'                 	=>  $ProfileForm,
								'ProfileFormMessages'         	=>  $ProfileFormMessages,
								'ProfileFormAttemptMessage'   	=>  $ProfileFormAttemptMessage,
                            )
                        );

        return $viewModel;
	}

	public function profileFormAction()
	{
		$Form					=	new ProfileForm();
		$FormMessages			=	"";
		$AjaxSubmissionSuccess	=	FALSE;

		if ($this->getRequest()->isXmlHttpRequest())
		{
			if ($this->getRequest()->isPost())
			{
				$FormValues 	=   $this->getRequest()->getPost();
				$Form->setData($FormValues);

				if( $Form->isValid($FormValues) )
				{
					$validatedData 	= 	$Form->getData();
				}
				else
				{
					$validatedData 	= 	'xxxxx';
					$FormMessages 	= 	$Form->getMessages();
				}

				echo "validatedData<pre>" . print_r($validatedData,1). "</pre>\n\n";
				echo "FormValues<pre>" . print_r($FormValues,1). "</pre>\n\n";
				echo "<pre>" . print_r($FormMessages,1). "</pre>\n\n";
				echo "_POST<pre>" . print_r($_POST,1). "</pre>\n\n";



				$jsonModel	= 	new JsonModel
									(
										array
										(
											'forcePageRefresh'	=>	$this->ajaxForcePageRefresh,
											'errorArray'		=>	array
																	(
																		'status'	=>	FALSE,
																		'message'	=>	FALSE,
																	),
											'returnedData' 		=> 	$validatedData,
											'success'			=>	$AjaxSubmissionSuccess,
										)
									);

				return $jsonModel;
			}
			else
			{
				$this->_writeLog('crit','Expected a POST from this Ajax request');
				return FALSE;
			}
		}
		else
		{
			$this->_writeLog('crit','Expected an Ajax request');
			return FALSE;
		}
	}

	public function addressBookAction()
	{


		$this->layout()->actionSpecificCSSFilesArray 		= 	array
																(
																	'/nomsterz/notary-view/notary/notary/css/address-book.css',
																	'/nomsterz/notary-view/notary-module.css',
																);
		$this->layout()->actionSpecificJSFilesTopArray 		= 	array();
		$this->layout()->actionSpecificJSFilesBottomArray 	= 	array
																(
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/notary/js/address-book.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																);

		$this->layout()->cloudLayoutJSPageName 				= 	'NotaryProfilePage';
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

                            )
                        );

        return $viewModel;
	}

	public function accountSettingsAction()
	{


		$this->layout()->actionSpecificCSSFilesArray 		= 	array
																(
																	'/nomsterz/notary-view/notary/notary/css/account-settings.css',
																	'/nomsterz/notary-view/notary-module.css',
																);
		$this->layout()->actionSpecificJSFilesTopArray 		= 	array();
		$this->layout()->actionSpecificJSFilesBottomArray 	= 	array
																(
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/notary/js/account-settings.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																);

		$this->layout()->cloudLayoutJSPageName 				= 	'NotaryProfilePage';
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

                            )
                        );

        return $viewModel;
	}

	public function privacySettingsAction()
	{


		$this->layout()->actionSpecificCSSFilesArray 		= 	array
																(
																	'/nomsterz/notary-view/notary/notary/css/privacy-settings.css',
																	'/nomsterz/notary-view/notary-module.css',
																);
		$this->layout()->actionSpecificJSFilesTopArray 		= 	array();
		$this->layout()->actionSpecificJSFilesBottomArray 	= 	array
																(
																	array
																	(
																		'url'			=>	'/nomsterz/notary-view/notary/notary/js/privacy-settings.js',
																		'fileType'		=>	'text/javascript',
																		'scriptOptions'	=>	array(),
																	),

																);

		$this->layout()->cloudLayoutJSPageName 				= 	'NotaryProfilePage';
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

                            )
                        );

        return $viewModel;
	}


    public function notaryLogoutAction()
    {
		// perform notary specific cleanup actions

		// redirect to generic member logout
        return $this->redirect()->toRoute('member-logout');
    }
}
