<?php
/**
 * Class AbstractNotaryController
 *
 * filename:   AbstractNotaryController.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       4/3/14 3:19 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Business\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mail;
use Zend\Session\Container;
use Zend\EventManager\EventManagerInterface;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
 
class AbstractNotaryController extends AbstractActionController
{
    protected $PageHit  	=   null;
    protected $SiteUser 	=   null;
    protected $Config   	=   null;

	protected $actionSpecificJSFilesArray;
	protected $actionSpecificCSSFilesArray;

	protected $ajaxForcePageRefresh		=	FALSE;

    protected $notaryID   				=   null;
    protected $notaryNamePrefix   		=   null;
    protected $notaryFirstName   		=   null;
    protected $notaryMidName1  			=   null;
    protected $notaryMidName2  			=   null;
    protected $notaryLastName   		=   null;
    protected $notaryFullName   		=   null;
    protected $notaryDisplayName   		=   null;
    protected $notaryNameSuffix   		=   null;

    protected $notaryGender   			=   null;
    protected $notaryGenderRaw 			=   null;
    protected $notaryBirthDate 			=   null;

	protected $notaryPersonalSummary   	=   null;

	protected $notaryLargeProfilePicUrl 	=	null;
	protected $notaryMediumProfilePicUrl 	=	null;
	protected $notarySmallProfilePicUrl 	=	null;
	protected $notaryXSmallProfilePicUrl 	=	null;

	protected $notaryPersonalWebsiteLink 	=	null;
	protected $notarySocialLinkLinkedIn 	=	null;
	protected $notarySocialLinkGooglePlus 	=	null;
	protected $notarySocialLinkTwitter 		=	null;
	protected $notarySocialLinkFacebook 	=	null;

    protected $notaryHomeLink   	=   null;
    protected $notaryProfileLink   	=   null;

    /**
     * This method will attempt to get a user with:
     *  - site cookies
     *
     * @return object User
     */
    protected function getUser()
    {
        $userIDCookie   =   (int) $this->getRequest()->getHeaders()->get('Cookie')->nomsterz_uid;
        if($userIDCookie && $userIDCookie > 0)
        {
            $siteUser     =   $this->getUserTable()->getUser($userIDCookie);

            if(FALSE != $siteUser && is_object($siteUser))
            {
                return $siteUser;
            }
        }

        return $this->createNewUser();
    }

    /**
     * @return object User | bool
     */
    public function createNewUser()
    {
        $newUser    =   new User;
        $newUser->setUserHash('');
        $newUser->setUserType('Default');
        $newUser->setUserMemberID(0);
        $newUser->setUserBrowserAgent();
        $newUser->setUserIPAddress();
        $newUser->setUserStatus('Open');
        $newUser->setUserCreationTime();
        $newUser->setUserLastUpdateTime();

        $newUserID      =   $this->getUserTable()->saveUser($newUser);
        $siteUser       =   $this->getUserTable()->getUser($newUserID);

        // Set user cookie
        $timeCookie     =   time() + (60*60*24*self::POLICY_UserIDCookieDuration);

        setcookie("nomsterz_uid", urlencode($newUserID), $timeCookie, "/", $_SERVER['SERVER_NAME'], 0, 0);

        if(is_object($siteUser))
        {
            return $siteUser;
        }
        else
        {
            return FALSE;
        }
    }





    /**
     * Tables Used in this controller
     */
    protected $errorTable;
    protected $userTable;
    protected $memberTable;
    protected $memberEmailsTable;
    protected $memberDetailsTable;
    protected $memberStatusTable;
    protected $emailStatusTable;

    public function getErrorTable()
    {
        if (!$this->errorTable)
        {
            $sm                 =   $this->getServiceLocator();
            $this->errorTable    =   $sm->get('Application\Mapper\ErrorTable');
        }
        return $this->errorTable;
    }

    public function getUserTable()
    {
        if (!$this->userTable)
        {
            $ServiceManager                 =   $this->getServiceLocator();
            $this->userTable                =   $ServiceManager->get('Application\Mapper\UserTable');
        }
        return $this->userTable;
    }

    public function getMemberTable()
    {
        if (!$this->memberTable)
        {
            $ServiceManager                 =   $this->getServiceLocator();
            $this->memberTable              =   $ServiceManager->get('Auth\Mapper\MemberTable');
        }
        return $this->memberTable;
    }

    public function getMemberEmailsTable()
    {
        if (!$this->memberEmailsTable)
        {
            $ServiceManager                 =   $this->getServiceLocator();
            $this->memberEmailsTable        =   $ServiceManager->get('Auth\Mapper\MemberEmailsTable');
        }
        return $this->memberEmailsTable;
    }

    public function getMemberDetailsTable()
    {
        if (!$this->memberDetailsTable)
        {
            $ServiceManager                 =   $this->getServiceLocator();
            $this->memberDetailsTable        =   $ServiceManager->get('Auth\Mapper\MemberDetailsTable');
        }
        return $this->memberDetailsTable;
    }

    public function getMemberStatusTable()
    {
        if (!$this->memberStatusTable)
        {
            $ServiceManager                 =   $this->getServiceLocator();
            $this->memberStatusTable        =   $ServiceManager->get('Auth\Mapper\MemberStatusTable');
        }
        return $this->memberStatusTable;
    }

    public function getEmailStatusTable()
    {
        if (!$this->emailStatusTable)
        {
            $ServiceManager        		=   $this->getServiceLocator();
            $this->emailStatusTable     =   $ServiceManager->get('Auth\Mapper\EmailStatusTable');
        }
        return $this->emailStatusTable;
    }



    /**
     * Authentication
     */
    protected $storage;
    protected $authservice;

    public function getAuthService()
    {
        if (! $this->authservice)
        {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (! $this->storage)
        {
            $this->storage = $this->getServiceLocator()->get('Auth\AuthMemberStorage');
        }

        return $this->storage;
    }



	/**
	 * @param string $priority
	 * @param        $message
	 */
	public function _writeLog($priority='info', $message)
    {
        $this->getServiceLocator()->get('Zend\Log\Business')->$priority($message);
    }

    protected function getConfig()
    {
        if (!$this->Config)
        {
            $this->Config   =   $this->getServiceLocator()->get('config');
        }

        return $this->Config;
    }


    /**
     * setEventManager
     *
     * @author  Chukky Nze
     * @since   12-23-2013
     *
     * Inject an EventManager instance
     *
     * @param EventManagerInterface $events
     *
     * @return $this|\Zend\Mvc\Controller\AbstractController
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);

        $controller = $this;

        $events->attach('dispatch',
						function ($e) use ($controller)
						{
							$request 	= 	$controller->getRequest();

							/**
							 * Check if the Member session is valid and usable
							 *
							 * START
							 */
							if(!$controller->getAuthService()->hasIdentity())
							{
								if ($request->isXmlHttpRequest())
								{
									$controller->ajaxForcePageRefresh	=	TRUE;
								}
								else
								{
									return $controller->redirect()->toRoute('member-logout-expired-session');
								}
							}

							$identity 	= 	$controller->getAuthService()->getIdentity();

							if(!is_object($identity) || $identity->id == 0 || empty($identity->member_type))
							{
								if ($request->isXmlHttpRequest())
								{
									$controller->ajaxForcePageRefresh	=	TRUE;
								}
								else
								{
									return $controller->redirect()->toRoute('member-logout-expired-session');
								}
							}

							$MemberObject 	=	$controller->getMemberTable()->getMember($identity->id);
							if(isset($MemberObject) && is_object($MemberObject) && $MemberObject->id > 0)
							{
								$controller->setNotaryID($MemberObject->id);
								/**
								 * Update Layout Variables
								 */
								$controller->updateLayoutVariables();
							}
							else
							{
								return $controller->redirect()->toRoute('member-logout-expired-session');
							}
							/**
							 * END
							 */





						}, 100); // execute before executing action logic

        return $this;
    }



	/**
	 * Sets default values for the cloud layout.
	 * Subsequent values are defined by regular AJAX calls
	 */
	public function updateLayoutVariables()
	{
		$MemberDetailsObject	=	$this->getMemberDetailsTable()->getMemberDetailsByMemberID($this->notaryID);

		/**
		 * Update Class Properties
		 */
		$this->notaryNamePrefix   			=   $MemberDetailsObject->getMemberDetailsPrefix();
		$this->notaryFirstName   			=   $MemberDetailsObject->getMemberDetailsFirstName();
		$this->notaryMidName1   			=   $MemberDetailsObject->getMemberDetailsMidName1();
		$this->notaryMidName2  				=   $MemberDetailsObject->getMemberDetailsMidName2();
    	$this->notaryLastName   			=   $MemberDetailsObject->getMemberDetailsLastName();
    	$this->notaryFullName				=	$MemberDetailsObject->getMemberDetailsFullName();
    	$this->notaryDisplayName			=	$MemberDetailsObject->getMemberDetailsDisplayName();
    	$this->notaryNameSuffix				=	$MemberDetailsObject->getMemberDetailsSuffix();

    	$this->notaryGender					=	$MemberDetailsObject->getMemberDetailsGender('text');
    	$this->notaryGenderRaw				=	$MemberDetailsObject->getMemberDetailsGender('raw');
    	$this->notaryBirthDate				=	$MemberDetailsObject->getMemberDetailsBirthDate();

		$this->notaryPersonalSummary		=	$MemberDetailsObject->getMemberDetailsPersonalSummary();

		$this->notaryLargeProfilePicUrl 	=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
		$this->notaryMediumProfilePicUrl 	=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
		$this->notarySmallProfilePicUrl 	=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();
		$this->notaryXSmallProfilePicUrl 	=	$MemberDetailsObject->getMemberDetailsProfilePicUrl();

		$this->notaryPersonalWebsiteLink 	=	$MemberDetailsObject->getMemberDetailsPersonalSiteUrl();
		$this->notarySocialLinkLinkedIn 	=	$MemberDetailsObject->getMemberDetailsLinkedInUrl();
		$this->notarySocialLinkGooglePlus 	=	$MemberDetailsObject->getMemberDetailsGooglePlusUrl();
		$this->notarySocialLinkTwitter 		=	$MemberDetailsObject->getMemberDetailsTwitterUrl();
		$this->notarySocialLinkFacebook		=	$MemberDetailsObject->getMemberDetailsFacebookUrl();

    	$this->notaryHomeLink				=	'/NotaryHome';
    	$this->notaryProfileLink			=	'/NotaryProfile';

		/**
		 * ALERT Dropdown Variables
		 */
		$ALERT_listItemsArray	=	array
									(
										array
										(
											'alertLink' 			=>	'/notaryAlert/notice/',
											'alertLinkID'			=>	'1',
											'alertLabelClass'		=>	'label label-success',
											'alertIconClass'		=>	'fa fa-user',
											'alertContent'			=>	'5 users online.',
											'alertFuzzyTime'		=>	'Just Now',
											'alertExactTime'		=>	'1234567890',
										),
										array
										(
											'alertLink' 			=>	'/notaryAlert/notice/',
											'alertLinkID'			=>	'1',
											'alertLabelClass'		=>	'label label-primary',
											'alertIconClass'		=>	'fa fa-comment',
											'alertContent'			=>	'5 users online.',
											'alertFuzzyTime'		=>	'Just Now',
											'alertExactTime'		=>	'1234567890',
										),
										array
										(
											'alertLink' 			=>	'/notaryAlert/notice/',
											'alertLinkID'			=>	'1',
											'alertLabelClass'		=>	'label label-warning',
											'alertIconClass'		=>	'fa fa-lock',
											'alertContent'			=>	'5 users online.',
											'alertFuzzyTime'		=>	'Just Now',
											'alertExactTime'		=>	'1234567890',
										),
									);
		$ALERT_listItemsCount 	=	count($ALERT_listItemsArray) > 0 ? count($ALERT_listItemsArray) : 0;

		/**
		 * INBOX Dropdown Variables
		 */
		$INBOX_listItemsArray	=	array
									(
										array
										(
											'messageLink' 			=>	'/notaryInbox/message/',
											'messageLinkID'			=>	'1',
											'messageAvatar'			=>	'notary/img/avatars/avatar8.jpg',
											'messageAvatarAltText'	=>	'Jane Doe',
											'messageFromMemberType'	=>	'Signing Agency',
											'messageFrom'			=>	'Jane Doe',
											'messageFromShort'		=>	'Jane Doe',
											'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageFuzzyTime'		=>	'Just Now',
											'messageExactTime'		=>	'1234567890',
										),
										array
										(
											'messageLink' 			=>	'/notaryInbox/message/',
											'messageLinkID'			=>	'2',
											'messageAvatar'			=>	'notary/img/avatars/avatar7.jpg',
											'messageAvatarAltText'	=>	'Jane Doe',
											'messageFromMemberType'	=>	'Business',
											'messageFrom'			=>	'Jane Doe',
											'messageFromShort'		=>	'Jane Doe',
											'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageFuzzyTime'		=>	'Just Now',
											'messageExactTime'		=>	'1234567890',
										),
										array
										(
											'messageLink' 			=>	'/notaryInbox/message/',
											'messageLinkID'			=>	'3',
											'messageAvatar'			=>	'notary/img/avatars/avatar6.jpg',
											'messageAvatarAltText'	=>	'Jane Doe',
											'messageFromMemberType'	=>	'Signing Source',
											'messageFrom'			=>	'Jane Doe',
											'messageFromShort'		=>	'Jane Doe',
											'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageFuzzyTime'		=>	'Just Now',
											'messageExactTime'		=>	'1234567890',
										),
										array
										(
											'messageLink' 			=>	'/notaryInbox/message/',
											'messageLinkID'			=>	'3',
											'messageAvatar'			=>	'notary/img/avatars/default-male.jpg',
											'messageAvatarAltText'	=>	'Jane Doe',
											'messageFromMemberType'	=>	'Client',
											'messageFrom'			=>	'Jane Doe',
											'messageFromShort'		=>	'Jane Doe',
											'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageFuzzyTime'		=>	'4 hours ago',
											'messageExactTime'		=>	'1234567890',
										),
										array
										(
											'messageLink' 			=>	'/notaryInbox/message/',
											'messageLinkID'			=>	'3',
											'messageAvatar'			=>	'notary/img/avatars/default-male.jpg',
											'messageAvatarAltText'	=>	'Jane Doe',
											'messageFromMemberType'	=>	'Guest',
											'messageFrom'			=>	'Jane Doe',
											'messageFromShort'		=>	'Jane Doe',
											'messageContent'		=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageContentShort'	=>	'Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse mole ...',
											'messageFuzzyTime'		=>	'4 hours ago',
											'messageExactTime'		=>	'1234567890',
										),
									);
		$INBOX_listItemsCount 	=	count($INBOX_listItemsArray) > 0 ? count($INBOX_listItemsArray) : 0;


		/**
		 * TODO Dropdown Variables
		 *
		 * 'TODO_footerLink'  			=> 	'/notaryTasks',
		'TODO_listTotalNumber'  	=> 	'14',
		'TODO_listItemsArray'  		=> 	array
										(
											array
											(
												'taskID'							=>	'1',
												'taskHeading'						=>	'Enter Signing Information', // Max 25 Characters
												'taskOverallCompletionLevel'		=>	'60',
												'taskProgressBarIsStriped'			=>	FALSE,
												'taskProgressBarIsStripedActive' 	=>	FALSE,

												'taskProgressBarIsComposite'		=>	FALSE, // The DISPLAY of this one task is broken into bits that add up to taskOverallCompletionLevel
												'taskBits'							=>	array
																						(
																							array
																							(
																								'taskBitProgressBarType' 	=>	'success', // success | info | warning | danger
																								'taskBitCompletionLevel'	=>	'60', // Out of 100%. Do not add the percent sign
																							),
																						),
											),
											array
											(
												'taskID'							=>	'2',
												'taskHeading'						=>	'Enter Signing Agency Information',
												'taskOverallCompletionLevel'		=>	'60',
												'taskProgressBarIsStriped'			=>	FALSE,
												'taskProgressBarIsStripedActive' 	=>	FALSE,

												'taskProgressBarIsComposite'		=>	FALSE, // The DISPLAY of this one task is broken into bits that add up to taskOverallCompletionLevel
												'taskBits'							=>	array
																						(
																							array
																							(
																								'taskBitProgressBarType' 	=>	'success', // Cannot be empty. success | info | warning | danger
																								'taskBitCompletionLevel'	=>	'40', // Out of 100%. Do not add the percent sign
																							),
																							array
																							(
																								'taskBitProgressBarType' 	=>	'warning', // Cannot be empty. success | info | warning | danger
																								'taskBitCompletionLevel'	=>	'20', // Out of 100%. Do not add the percent sign
																							),
																						),
											),
										),
		 *
		 */
		$TODO_listItemsArray 	=	array
									(
										array
										(
											'taskID'							=>	'1',
											'taskHeading'						=>	'Enter Signing Information', // Max 25 Characters
											'taskOverallCompletionLevel'		=>	'60',
											'taskProgressBarIsStriped'			=>	FALSE,
											'taskProgressBarIsStripedActive' 	=>	FALSE,

											'taskProgressBarIsComposite'		=>	FALSE, // The DISPLAY of this one task is broken into bits that add up to taskOverallCompletionLevel
											'taskBits'							=>	array
																					(
																						array
																						(
																							'taskBitProgressBarType' 	=>	'success', // success | info | warning | danger
																							'taskBitCompletionLevel'	=>	'60', // Out of 100%. Do not add the percent sign
																						),
																					),
										),
										array
										(
											'taskID'							=>	'2',
											'taskHeading'						=>	'Enter Signing Agency Info',
											'taskOverallCompletionLevel'		=>	'60',
											'taskProgressBarIsStriped'			=>	FALSE,
											'taskProgressBarIsStripedActive' 	=>	FALSE,

											'taskProgressBarIsComposite'		=>	FALSE, // The DISPLAY of this one task is broken into bits that add up to taskOverallCompletionLevel
											'taskBits'							=>	array
																					(
																						array
																						(
																							'taskBitProgressBarType' 	=>	'success', // Cannot be empty. Choose success | info | warning | danger
																							'taskBitCompletionLevel'	=>	'40', // Out of 100%. Do not add the percent sign
																						),
																						array
																						(
																							'taskBitProgressBarType' 	=>	'warning', // success | info | warning | danger
																							'taskBitCompletionLevel'	=>	'20', // Out of 100%. Do not add the percent sign
																						),
																					),
										),
										array
										(
											'taskID'							=>	'2',
											'taskHeading'						=>	'Order # 12345678901234567',
											'taskOverallCompletionLevel'		=>	'40',
											'taskProgressBarIsStriped'			=>	TRUE,
											'taskProgressBarIsStripedActive' 	=>	TRUE,
											'taskBits'							=>	array
																					(
																						array
																						(
																							'taskBitProgressBarType' 	=>	'danger', // Cannot be empty. Choose success | info | warning | danger
																							'taskBitCompletionLevel'	=>	'40', // Out of 100%. Do not add the percent sign
																						),
																					),
										),
									);

		$defaultLargeProfilePicUrl  	=	isset($this->notaryGender) ? 'notary/img/avatars/default-male-large.jpg' : 'notary/img/avatars/default-female-large.jpg';
		$defaultMediumProfilePicUrl  	=	isset($this->notaryGender) ? 'notary/img/avatars/default-male.jpg' : 'notary/img/avatars/default-female.jpg';
		$defaultSmallProfilePicUrl  	=	isset($this->notaryGender) ? 'notary/img/avatars/default-male.jpg' : 'notary/img/avatars/default-female.jpg';
		$defaultXSmallProfilePicUrl  	=	isset($this->notaryGender) ? 'notary/img/avatars/default-male.jpg' : 'notary/img/avatars/default-female.jpg';

		$memberPicUrlLarge				=	'';
		$memberPicUrlMedium				=	'';
		$memberPicUrlSmall				=	'';
		$memberPicUrlXSmall				=	'';


		/**
		 * User Menu Dropdown
		 *
		 * link - The link the option should go to
		 * iconClass - the class based icon to display
		 * sectionName - The name to display in the menu section
		 * labelClass - The label class used to highlight the icon and section. Default is no highlighting.
		 *
		 * Additional sections and highlighting can be added to the array given any logic you need
		 */
		$memberUserMenuArray	=	array
									(
										/**
										 * Standard Sections
										 */
										array
										(
											'link'			=>	'/NotaryProfile',
											'iconClass'		=>	'fa fa-user',
											'sectionName'	=>	'My Profile',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/NotaryAccountSettings',
											'iconClass'		=>	'fa fa-cog',
											'sectionName'	=>	'Account Settings',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/NotaryAddressBook',
											'iconClass'		=>	'fa fa-book',
											'sectionName'	=>	'Address Book',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/NotaryPrivacySettings',
											'iconClass'		=>	'fa fa-eye',
											'sectionName'	=>	'Privacy Settings',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/notaryChangePassword',
											'iconClass'		=>	'fa fa-lock',
											'sectionName'	=>	'Change Password',
											'labelClass'	=>	'',
										),
										array
										(
											'link'			=>	'/NotaryLogout',
											'iconClass'		=>	'fa fa-power-off',
											'sectionName'	=>	'Log Out',
											'labelClass'	=>	'',
										),
									);


		$this->layout()->setVariables
						(
							array
							(
								/**
								 * Custom Nomsterz JSS & CSS Files
								 */
								'turnOnFlotCharts' 						=> 	FALSE,
								'ModuleDirectoryReference' 				=>	'notary/',
								'cloudLayoutJSPageName'					=>	'NotaryHome',
								'actionSpecificCSSFilesArray'			=>	array(),
								'actionSpecificJSFilesTopArray'			=>	array(),
								'actionSpecificJSFilesBottomArray'		=>	array(),

								/**
								 * NOTIFICATION/Alerts Dropdown Variables
								 */
								'ALERT_footerLink'  					=> 	'/notaryAlerts',
								'ALERT_totalMessageCount'  				=> 	(string) $ALERT_listItemsCount > 0 ? $ALERT_listItemsCount : '0',
								'ALERT_title'  							=> 	'' . $ALERT_listItemsCount . ' Notification' . ($ALERT_listItemsCount == 1 ? '' : 's'),
								'ALERT_listItemsArray'  				=> 	$ALERT_listItemsArray,

								/**
								 * INBOX Dropdown Variables
								 */
								'INBOX_sidebarLink'  					=> 	'/notaryInbox',
								'INBOX_sidebarLink_all'  				=> 	'/notaryInbox/all',
								'INBOX_sidebarLink_new'  				=> 	'/notaryInbox/new',
								'INBOX_sidebarLink_favorites'  			=> 	'/notaryInbox/favorites',
								'INBOX_footerLink'  					=> 	'/notaryInbox',
								'INBOX_composeNewLink'  				=> 	'/notaryInbox/ComposeNewMessage',
								'INBOX_totalMessageCount'  				=> 	(string) $INBOX_listItemsCount > 0 ? $INBOX_listItemsCount : '0',
								'INBOX_title'  							=> 	'' . $INBOX_listItemsCount . ' Message' . ($INBOX_listItemsCount == 1 ? '' : 's'),
								'INBOX_listItemsArray'  				=> 	$INBOX_listItemsArray,


								/**
								 * TODO_ Dropdown Variables
								 */
								'TODO_footerLink'  						=> 	'/notaryTasks',
								'TODO_listTotalNumber'  				=> 	(string) count($TODO_listItemsArray) > 0 ? count($TODO_listItemsArray) : '0',
								'TODO_listItemsArray'  					=> 	$TODO_listItemsArray,

								/**
								 * User Login Dropdown Variables
								 */
								'memberLoginDropDownDisplayName' 		=> 	$MemberDetailsObject->getMemberDetailsFirstName(),
								'memberFullName' 						=> 	$MemberDetailsObject->getMemberDetailsFullName(),
								'memberHomeLink' 						=> 	'/NotaryHome',
								'memberUserMenuArray' 					=> 	$memberUserMenuArray,

								/**
								 * Picture & Icon urls
								 */
								'notaryLargeProfilePicUrl' 				=> 	isset($memberPicUrlLarge[0])  ? $memberPicUrlLarge[0]  : $defaultLargeProfilePicUrl,
								'notaryMediumProfilePicUrl' 			=> 	isset($memberPicUrlMedium[0]) ? $memberPicUrlMedium[0] : $defaultMediumProfilePicUrl,
								'notarySmallProfilePicUrl' 				=> 	isset($memberPicUrlSmall[0])  ? $memberPicUrlSmall[0]  : $defaultSmallProfilePicUrl,
								'notaryXSmallProfilePicUrl' 			=> 	isset($memberPicUrlXSmall[0]) ? $memberPicUrlXSmall[0] : $defaultXSmallProfilePicUrl,
							)
						);

	}


	/**
	 * Setters and Getters
	 */
    public function setNotaryID($value)
    {
        $this->notaryID = $value;
    }
    public function getNotaryID()
    {
        return $this->notaryID;
    }




	public function setPageHit($value)
    {
        $this->PageHit = $value;
    }
	public function getPageHit()
    {
        return $this->PageHit;
    }



    public function setSiteUser($value)
    {
        $this->SiteUser = $value;
    }
    public function getSiteUser()
    {
        return $this->SiteUser;
    }



	public function setActionSpecificJSFilesArray($value)
    {
        $this->actionSpecificJSFilesArray = $value;
    }
	public function getActionSpecificJSFilesArray()
    {
        return $this->actionSpecificJSFilesArray;
    }




	public function setActionSpecificCSSFilesArray($value)
    {
        $this->actionSpecificCSSFilesArray = $value;
    }
	public function getActionSpecificCSSFilesArray()
    {
        return $this->actionSpecificCSSFilesArray;
    }




	public function setAjaxForcePageRefresh($value)
    {
        $this->ajaxForcePageRefresh = $value;
    }
	public function getAjaxForcePageRefresh()
    {
        return $this->ajaxForcePageRefresh;
    }




    public function setNotaryNamePrefix($value)
    {
        $this->notaryNamePrefix = $value;
    }
    public function getNotaryNamePrefix()
    {
        return $this->notaryNamePrefix;
    }




    public function setNotaryFirstName($value)
    {
        $this->notaryFirstName = $value;
    }
    public function getNotaryFirstName()
    {
        return $this->notaryFirstName;
    }




    public function setNotaryMidName1($value)
    {
        $this->notaryMidName1 = $value;
    }
    public function getNotaryMidName1()
    {
        return $this->notaryMidName1;
    }




    public function setNotaryMidName2($value)
    {
        $this->notaryMidName2 = $value;
    }
    public function getNotaryMidName2()
    {
        return $this->notaryMidName2;
    }




    public function setNotaryLastName($value)
    {
        $this->notaryLastName = $value;
    }
    public function getNotaryLastName()
    {
        return $this->notaryLastName;
    }




    public function setNotaryFullName($value)
    {
        $this->notaryFullName = $value;
    }
    public function getNotaryFullName()
    {
        return $this->notaryFullName;
    }




    public function setNotaryDisplayName($value)
    {
        $this->notaryDisplayName = $value;
    }
    public function getNotaryDisplayName()
    {
        return $this->notaryDisplayName;
    }




    public function setNotaryNameSuffix($value)
    {
        $this->notaryNameSuffix = $value;
    }
    public function getNotaryNameSuffix()
    {
        return $this->notaryNameSuffix;
    }




    public function setNotaryGender($value)
    {
        $this->notaryGender = $value;
    }
    public function getNotaryGender()
    {
        return $this->notaryGender;
    }




    public function setNotaryGenderRaw($value)
    {
        $this->notaryGenderRaw = $value;
    }
    public function getNotaryGenderRaw()
    {
        return $this->notaryGenderRaw;
    }




    public function setNotaryBirthDate($value)
    {
        $this->notaryBirthDate = $value;
    }
    public function getNotaryBirthDate()
    {
        return $this->notaryBirthDate;
    }




	public function setNotaryPersonalSummary($value)
    {
        $this->notaryPersonalSummary = $value;
    }
	public function getNotaryPersonalSummary()
    {
        return $this->notaryPersonalSummary;
    }




	public function setNotaryLargeProfilePicUrl($value)
    {
        $this->notaryLargeProfilePicUrl = $value;
    }
	public function getNotaryLargeProfilePicUrl()
    {
        return $this->notaryLargeProfilePicUrl;
    }




	public function setNotaryMediumProfilePicUrl($value)
    {
        $this->notaryMediumProfilePicUrl = $value;
    }
	public function getNotaryMediumProfilePicUrl()
    {
        return $this->notaryMediumProfilePicUrl;
    }




	public function setNotarySmallProfilePicUrl($value)
    {
        $this->notarySmallProfilePicUrl = $value;
    }
	public function getNotarySmallProfilePicUrl()
    {
        return $this->notarySmallProfilePicUrl;
    }




	public function setNotaryXSmallProfilePicUrl($value)
    {
        $this->notaryXSmallProfilePicUrl = $value;
    }
	public function getNotaryXSmallProfilePicUrl()
    {
        return $this->notaryXSmallProfilePicUrl;
    }




	public function setNotaryPersonalWebsiteLink($value)
    {
        $this->notaryPersonalWebsiteLink = $value;
    }
	public function getNotaryPersonalWebsiteLink()
    {
        return $this->notaryPersonalWebsiteLink;
    }




	public function setNotarySocialLinkLinkedIn($value)
    {
        $this->notarySocialLinkLinkedIn = $value;
    }
	public function getNotarySocialLinkLinkedIn()
    {
        return $this->notarySocialLinkLinkedIn;
    }




	public function setNotarySocialLinkGooglePlus($value)
    {
        $this->notarySocialLinkGooglePlus = $value;
    }
	public function getNotarySocialLinkGooglePlus()
    {
        return $this->notarySocialLinkGooglePlus;
    }




	public function setNotarySocialLinkTwitter($value)
    {
        $this->notarySocialLinkTwitter = $value;
    }
	public function getNotarySocialLinkTwitter()
    {
        return $this->notarySocialLinkTwitter;
    }




	public function setNotarySocialLinkFacebook($value)
    {
        $this->notarySocialLinkFacebook = $value;
    }
	public function getNotarySocialLinkFacebook()
    {
        return $this->notarySocialLinkFacebook;
    }




    public function setNotaryHomeLink($value)
    {
        $this->notaryHomeLink = $value;
    }
    public function getNotaryHomeLink()
    {
        return $this->notaryHomeLink;
    }




    public function setNotaryProfileLink($value)
    {
        $this->notaryProfileLink = $value;
    }
    public function getNotaryProfileLink()
    {
        return $this->notaryProfileLink;
    }
}
