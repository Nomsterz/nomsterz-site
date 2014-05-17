<?php
/**
 * Class AuthController
 *
 * filename:   AuthController.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/17/14 6:28 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Controller;

use Nomsterz\Library\Utilities\EmailUtility;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;

use Zend\Mail;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

use Zend\Session\Container;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;

use Auth\Form\LoginForm;
use Auth\Form\LoginCaptchaForm;
use Auth\Form\SignupForm;
use Auth\Form\ForgotForm;
use Auth\Form\LostSignupVerificationForm;
use Auth\Form\VerificationDetailsForm;
use Auth\Form\ChangePasswordWithOldPasswordForm;
use Auth\Form\ChangePasswordWithVerifyLinkForm;

use Application\Model\Error;
use Application\Model\User;

use Auth\Model\IPBin;
use Auth\Model\Member;
use Auth\Model\MemberEmails;
use Auth\Model\MemberDetails;
use Auth\Model\MemberStatus;
use Auth\Model\EmailStatus;
use Auth\Model\AccessAttempt;

 
class AuthController extends AbstractActionController
{
    const POLICY_UserIDCookieDuration       					=   365;

	const POLICY_AllowedVerificationSeconds_Signup				=   43200;
	const POLICY_AllowedVerificationSeconds_ChangePassword		=   10800;

	const POLICY_AllowedLoginAttempts       					=   3;
    const POLICY_AllowedLoginCaptchaAttempts    				=   3;
    const POLICY_AllowedSignupAttempts       					=   3;
    const POLICY_AllowedForgotAttempts       					=   3;
    const POLICY_AllowedChangeVerifiedMemberPasswordAttempts 	=   3;
    const POLICY_AllowedChangeOldMemberPasswordAttempts 		=   3;
    const POLICY_AllowedLostSignupVerificationAttempts 			=   3;
    const POLICY_AllowedAttemptsLookBackDuration  				=   'Last1Hour';

    protected $SiteUser     =   null;
    protected $SiteMember   =   null;
    protected $PageHit      =   null;
    protected $Config       =   null;

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
     * Tables Used in this controller
     */
    protected $errorTable;
    protected $userTable;
    protected $memberTable;
    protected $memberEmailsTable;
    protected $memberDetailsTable;
    protected $memberStatusTable;
    protected $emailStatusTable;
    protected $ipBinTable;
    protected $accessAttemptTable;

    public function getIPBinTable()
    {
        if (!$this->ipBinTable)
        {
            $sm               	=   $this->getServiceLocator();
            $this->ipBinTable   =   $sm->get('Auth\Mapper\IPBinTable');
        }
        return $this->ipBinTable;
    }

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

    public function getAccessAttemptTable()
    {
        if (!$this->accessAttemptTable)
        {
            $ServiceManager                 =   $this->getServiceLocator();
            $this->accessAttemptTable       =   $ServiceManager->get('Auth\Mapper\AccessAttemptTable');
        }
        return $this->accessAttemptTable;
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





    public function _writeLog($priority='info', $message)
    {
		// todo : add a variable that filters off debugs or others given a particular environment

        $this->SiteUser =   $this->getUser();
        $message        =   "
                Namespace   =>  " . __NAMESPACE__ . ";
                Controller  =>  " . $this->params('controller') . ";
                Action      =>  " . $this->params('action') . ";
                User        =>  " . ($this->SiteUser->id     ? $this->SiteUser->id   : 0) . ";
                Member      =>  " . (isset($this->SiteMember->id) ? $this->SiteMember->id : 0) . ";
                Message     =>  " . $message;
        $this->getServiceLocator()->get('Zend\Log\Auth')->$priority($message);
    }

    public function _writeError($message)
    {
        $error = new Error();
        $error->setDefaultErrorMessage
                (
                    $message,
                    (is_object($this->SiteUser) ? $this->SiteUser->id : 0),
                    __NAMESPACE__,
                    $this->params('controller'),
                    $this->params('action'),
                    $_SERVER['SCRIPT_NAME'],
                    $this->getRequest()->getUriString()
                );
        $this->getErrorTable()->saveError($error);
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
        $events->attach('dispatch', function ($e) use ($controller)
                                    {

                                    }, 100); // execute before executing action logic

        return $this;
    }


	/**
	 * Main application access point for members
	 * Login, Signup, Forgot Login
	 * Also has access to Lost Signup Verification
	 *
	 * @return array|\Zend\Http\Response|ViewModel
	 * @throws \Exception
	 */
	public function indexAction()
    {
		// todo : dummy vars for Signup, Forgot

		if ($this->getAuthService()->hasIdentity())
        {
            return $this->redirect()->toRoute('get-member-home');
        }

		// Check if Access is allowed
		if(!$this->isAccessAllowed())
		{
			return $this->redirect()->toRoute('access-temp-disabled');
		}

        $LoginForm          =   new LoginForm();
        $LoginCaptchaForm   =   new LoginCaptchaForm();
        $SignupForm         =   new SignupForm();
        $ForgotForm         =   new ForgotForm();

        $reCaptcha          =   $this->getServiceLocator()->get('ReCaptchaService');
        $reCaptchaError     =   FALSE;

        $myAuthSession      =   new Container('NomsterzAuthSession');

        $AttemptedLogins           =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('LoginForm',          array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);
        $AttemptedLoginCaptchas    =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('LoginCaptchaForm',   array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);
        $AttemptedSignups          =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('SignupForm',         array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);
        $AttemptedForgots          =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('ForgotForm',         array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);

		$LoginFormMessages          =   '';
        $LoginCaptchaFormMessages   =   '';
        $SignupFormMessages         =   '';
        $ForgotFormMessages         =   '';

        $LoginAttemptMessage        =   '';
        $LoginCaptchaAttemptMessage =   '';
        $SignupAttemptMessage       =   '';
        $ForgotAttemptMessage       =   '';

        if($this->params('activity') == 'login')
		{
			switch($this->params('reason'))
			{
				case 'expired-session' 		:	$LoginHeaderMessage 	=	1; break;
				case 'intentional-logout' 	:	$LoginHeaderMessage 	=	2; break;
				case 'changed-password' 	:	$LoginHeaderMessage 	=	3; break;

				default : $LoginHeaderMessage 	=	0;
			}
		}
		else
		{
			$LoginHeaderMessage     =   '';
		}

        $LoginCaptchaHeaderMessage 	=   '';
        $SignupHeaderMessage       	=   '';
        $ForgotHeaderMessage       	=   '';


        if($this->getRequest()->isPost())
        {
            if($this->getRequest()->getPost('login_csrf') != "")
            {
                if($AttemptedLogins['total'] > self::POLICY_AllowedLoginAttempts)
				{
					$activeForm     =   'loginCaptcha';
				}
				else
				{
					$activeForm     =   'login';
				}
            }
            elseif($this->getRequest()->getPost('login_captcha_csrf') != "")
            {
				if($AttemptedLoginCaptchas['total'] > self::POLICY_AllowedLoginCaptchaAttempts)
				{
					$this->applyLock('Locked:Excessive-Login-Attempts', $this->getRequest()->getPost('returning_member'), 'excessive-logins');
					return $this->redirect()->toRoute('access-temp-disabled');
				}
				else
				{
					$activeForm         =   'loginCaptcha';
				}
            }
            elseif($this->getRequest()->getPost('signup_csrf') != "")
            {
                $activeForm     =   'signup';
            }
            elseif($this->getRequest()->getPost('forgot_csrf') != "")
            {
                $activeForm     =   'forgot';
            }
            else
            {
                $activeForm     =   ($this->params('activity') ? $this->params('activity') : 'login');
            }

            if($activeForm == 'login' || $activeForm == 'loginCaptcha')
            {
                $SubmittedForm          =   ($activeForm == 'loginCaptcha'
                                                ?   $LoginCaptchaForm
                                                :   $LoginForm);
                $SubmittedFormValues    =   $this->getRequest()->getPost();
                $SubmittedFormName      =   ($activeForm == 'loginCaptcha'
                                                ?   'LoginCaptchaForm'
                                                :   'LoginForm');
                $SubmittedFormCSRF      =   ($activeForm == 'loginCaptcha'
                                                ?   'login_captcha_csrf'
                                                :   'login_csrf');

                /**
                 * Check for robot entries against dummy variables
                 */
                if(!$this->isFormClean($SubmittedFormName, $SubmittedFormValues))
                {
                    $this->registerAccessAttempt($SubmittedFormName, 0);

                    // todo : add an admin alert

                    $this->_writeLog('info', $SubmittedFormName . " has invalid dummy variables passed.");
                    $SubmittedFormValues[$SubmittedFormCSRF]    =   '!98475b8!#urwgfwitg2^347tg2%78rtg283*rg';
                }

                /**
                 * Check Login attempts in the last 24 hours
				 * -------------------------------------------------------------------------
                 * - There should not be a crazy amount of logins or logouts
                 * - The amount of logins and logouts should never be too far apart
                 * - There should be less than x signups ever
                 * - There are only 6 login attempts than the account is locked
                 * -- After 1st 3 attempts,
                 * ------ captcha is enabled
                 * ------ a login is paused gif is displayed for 30 secs
                 * ------ admin is silent alerted that customer failed login
                 * -- After 4th attempt,
                 * ------ captcha is enabled
                 * ------ a login is paused gif is displayed for 60 secs
                 * ------ admin alert is silent created comparing login attempts with threshold
                 * ------------ if threshold is passed, critical alert raised
                 * -- After 5th attempt,
                 * ------ captcha is enabled
                 * ------ a login is paused gif is displayed for 120 secs,
                 * ------ a warning saying this is a final attempt is displayed
                 * ------ admin alert is created comparing login attempts with threshold
                 * ------------ if threshold is passed, critical alert raised
                 * -- After 6th attempt
                 * ------ account is locked,
                 * ------ admin alerted,
                 * ------ account holder is alerted,
                 * ------ account holder password is changed to unknown random 256 length string
                 * ------ admin alert is created comparing login attempts with threshold
                 * ------------ if threshold is passed, critical alert raised
                 */

				$SubmittedForm->setData($SubmittedFormValues);

                if ($SubmittedForm->isValid($SubmittedFormValues))
                {
					$this->_writeLog('debug', "Submitted form (" . $SubmittedFormName . ") - is valid");
                    if(isset($reCaptcha) && $activeForm == 'loginCaptcha')
                    {
						$this->_writeLog('debug', "Submitted form (" . $SubmittedFormName . ") - recaptcha isset");
						if($_POST['recaptcha_challenge_field'] != '' && $_POST['recaptcha_response_field'] != '')
						{
							$this->_writeLog('debug', "Submitted form (" . $SubmittedFormName . ") - all relevant recapthca fields set");
							$reCaptchaResult    =   $reCaptcha->verify
                                                            (
                                                                $_POST['recaptcha_challenge_field'],
                                                                $_POST['recaptcha_response_field']
                                                            );

							if($reCaptchaResult->isValid())
							{
								$this->_writeLog('debug', "Submitted form (" . $SubmittedFormName . ") - recaptcha is valid");
								$LoginCaptchaAttempt     		=   $this->coreAccessAttemptProcess($SubmittedForm, $SubmittedFormName, 'returning_member', 'LoginCaptchaFormPasswordField', 'get-member-home');
								$this->_writeLog('debug', "LoginCaptchaAttempt coreAccessAttemptProcess<pre>" . print_r($LoginCaptchaAttempt, 1) . "</pre>");
								if(isset($LoginCaptchaAttempt['AttemptStatusRoute'][1]))
								{
									$this->_writeLog('debug', "LoginCaptchaAttempt AttemptStatusRoute");
									$toRouteCreated		=	TRUE;
									$toRouteName 		=	$LoginCaptchaAttempt['AttemptStatusRoute'];
								}
								$LoginCaptchaAttemptMessage    	=   $LoginCaptchaAttempt['AttemptDisplayMessage'];
							}
							else
							{
								$this->_writeLog('debug', "Submitted form (" . $SubmittedFormName . ") - recaptcha is not valid");
								$reCaptchaError     =   TRUE;
							}
						}
                        else
						{
							$this->registerAccessAttempt($SubmittedFormName, 0);
							$LoginCaptchaAttemptMessage 	=	"You forgot to complete the ReCaptcha form. Please, retry.";
						}
                    }
                    else
                    {
                        $LoginAttempt    		=   $this->coreAccessAttemptProcess($SubmittedForm, $SubmittedFormName, 'returning_member', 'LoginFormPasswordField', 'get-member-home');
						$this->_writeLog('debug', "<pre>" . print_r($LoginAttempt, 1) . "</pre>");

						if(isset($LoginAttempt['AttemptStatusRoute'][1]))
						{
							$toRouteCreated		=	TRUE;
							$toRouteName 		=	$LoginAttempt['AttemptStatusRoute'];
						}

						$LoginAttemptMessage    =   $LoginAttempt['AttemptDisplayMessage'];
                    }
                }
                else
                {
                    $this->registerAccessAttempt($SubmittedFormName, 0);
                    $this->_writeLog('info', "Invalid " . $SubmittedFormName . " values.");

                    if($SubmittedFormName == 'LoginCaptchaForm')
                    {
                        $LoginCaptchaFormMessages   =   $SubmittedForm->getMessages();
                    }
                    else
                    {
                        $LoginFormMessages          =   $SubmittedForm->getMessages();
                    }
                }
            }
            elseif($activeForm == 'signup')
            {
				if($AttemptedSignups['total'] > self::POLICY_AllowedSignupAttempts)
				{
					$this->applyLock('Locked:Excessive-Signup-Attempts', $this->getRequest()->getPost('new_member'),'excessive-signups');
					return $this->redirect()->toRoute('custom-error-18');
				}
				else
				{
					$SubmittedForm          =   $SignupForm;
					$SubmittedFormValues    =   $this->getRequest()->getPost();
					$SubmittedFormName      =   'SignupForm';
					$SubmittedFormCSRF      =   'signup_csrf';

					/**
					 * Check for robot entries against dummy variables
					 */
					if(!$this->isFormClean($SubmittedFormName, $SubmittedFormValues))
					{
						$this->registerAccessAttempt($SubmittedFormName, 0);

						// todo : add an admin alert

						$this->_writeLog('info', $SubmittedFormName . " has invalid dummy variables passed.");
						$SubmittedFormValues[$SubmittedFormCSRF]    =   '!98475b8!#urwgfwitg2^347tg2%78rtg283*rg';
					}

					$SignupFormValues   =   $this->getRequest()->getPost();
					$SignupForm->setData($SignupFormValues);

					if ($SignupForm->isValid($SignupFormValues))
					{
						$validatedData          =   $SignupForm->getData();

						// Add the emailAddress
						$this->addEmailStatus($validatedData['new_member'], 'AddedUnverified');

						// Get the Site User so you can associate this user behaviour with this new member
						$this->SiteUser         =   $this->getUser();

						// todo: Check if member email already exists
						$doesMemberAlreadyExist =   $this->getMemberEmailsTable()->getMemberEmailsByEmail($validatedData['new_member']);
						if($doesMemberAlreadyExist != FALSE)
						{
							$this->registerAccessAttempt($SubmittedFormName, 0);
							return $this->redirect()->toRoute('member-already-exists');
						}


						// Create a Member Object
						$LoginCredentials   =   $this->getMemberTable()->generateLoginCredentials($validatedData['new_member'], $validatedData['password']);
						$NewMember          =   new Member();
						$NewMember->setMemberType('6');
						$NewMember->setMemberCreationTime();
						$NewMember->setMemberPauseTime(1);
						$NewMember->setMemberCancellationTime(1);
						$NewMember->setMemberLastUpdateTime();
						$NewMember->setMemberLoginCredentials($LoginCredentials[0]);
						$NewMember->setMemberLoginSalt1($LoginCredentials[1]);
						$NewMember->setMemberLoginSalt2($LoginCredentials[2]);
						$NewMember->setMemberLoginSalt3($LoginCredentials[3]);
						$NewMemberObject    =   $this->getMemberTable()->getMember($this->getMemberTable()->saveMember($NewMember));

						if(!is_object($NewMemberObject))
						{
							// todo: handle this better. Write an error, add fatal admin alert and a log entry
							$this->registerAccessAttempt($SubmittedFormName, 0);
							$this->_writeLog('info', $SubmittedFormName . " - Could not create a new member object.");
							throw new \Exception("Could not create a new member object");
						}

						// Update User with Member ID
						$this->SiteUser->setUserMemberID($NewMemberObject->id);
						$this->SiteUser     =   $this->getUserTable()->getUser($this->getUserTable()->saveUser($this->SiteUser));

						// Create & Save a Member Status Object
						$this->addMemberStatus($NewMemberObject->id, 'Successful-Signup');

						// Create & Save a Member Emails Object
						$NewMemberEmail         =   new MemberEmails();
						$NewMemberEmail->setMemberEmailsMemberID($NewMemberObject->id);
						$NewMemberEmail->setMemberEmailsEmailAddress($validatedData['new_member']);
						$NewMemberEmail->setMemberEmailsVerificationSent(0);
						$NewMemberEmail->setMemberEmailsVerificationSentOn(0);
						$NewMemberEmail->setMemberEmailsVerified(0);
						$NewMemberEmail->setMemberEmailsVerifiedOn(0);
						$NewMemberEmail->setMemberEmailsCreationTime();
						$NewMemberEmail->setMemberEmailsLastUpdateTime();
						$NewMemberEmailObject   =   $this->getMemberEmailsTable()->getMemberEmails($this->getMemberEmailsTable()->saveMemberEmails($NewMemberEmail));

						if(!is_object($NewMemberEmailObject))
						{
							// todo: handle this better. Write an error, add fatal admin alert and a log entry
							$this->registerAccessAttempt($SubmittedFormName, 0);
							$this->_writeLog('info', $SubmittedFormName . " - Could not create a new member email object.");
							throw new \Exception("Could not create a new member email object.");
						}

						// Prepare an Email for Validation
						// setup SMTP options
						$verifyEmailLink    =   $this->getMemberEmailsTable()->getVerifyEmailLink($validatedData['new_member'], $NewMemberObject->id, 'verify-new-member');
						$this->sendEmail('verify-new-member', array('verifyEmailLink' => $verifyEmailLink), 'General', $NewMemberEmailObject->getMemberEmailsEmailAddress());

						// Update Member emails that verification was sent and at what time for this member
						$NewMemberEmailObject->setMemberEmailsVerified(0);
						$NewMemberEmailObject->setMemberEmailsVerifiedOn(0);
						$NewMemberEmailObject->setMemberEmailsVerificationSent(1);
						$NewMemberEmailObject->setMemberEmailsVerificationSentOn(strtotime('now'));
						$NewMemberEmailObject   =   $this->getMemberEmailsTable()->getMemberEmails($this->getMemberEmailsTable()->saveMemberEmails($NewMemberEmailObject));


						// Add the emailAddress status
						$this->addEmailStatus($NewMemberEmailObject->getMemberEmailsEmailAddress(), 'VerificationSent');

						// Store admin alert for new member
						// todo: Create a cron script that checks for new members since the last check and adds alerts and sends off emails to whomever needs to know plus other tasks. Call it process new members

						// Add

						// Redirect to Successful Signup Page that informs them of the need to validate the email before they can enjoy the free 90 day Premium membership
						$this->registerAccessAttempt($SubmittedFormName, 1);
						return $this->redirect()->toRoute('member-signup-success');
					}
					else
					{
						$this->registerAccessAttempt($SubmittedFormName, 0);
						$SignupFormMessages           =   $SignupForm->getMessages();
					}
				}
            }
            elseif($activeForm == 'forgot')
            {
				if($AttemptedForgots['total'] > self::POLICY_AllowedForgotAttempts)
				{
					$this->applyLock('Locked:Excessive-ForgotLogin-Attempts', $this->getRequest()->getPost('forgot_email'),'excessive-forgot-logins');
					return $this->redirect()->toRoute('custom-error-20');
				}
				else
				{

					$SubmittedForm          =   $ForgotForm;
					$SubmittedFormValues    =   $this->getRequest()->getPost();
					$SubmittedFormName      =   'ForgotForm';
					$SubmittedFormCSRF      =   'forgot_csrf';

					/**
					 * Check for robot entries against dummy variables
					 */
					if(!$this->isFormClean($SubmittedFormName, $SubmittedFormValues))
					{
						$this->registerAccessAttempt($SubmittedFormName, 0);

						// todo : add an admin alert

						$this->_writeLog('info', $SubmittedFormName . " has invalid dummy variables passed.");
						$SubmittedFormValues[$SubmittedFormCSRF]    =   '!98475b8!#urwgfwitg2^347tg2%78rtg283*rg';
					}

					$ForgotFormValues   =   $this->getRequest()->getPost();
					$ForgotForm->setData($ForgotFormValues);

					if ($ForgotForm->isValid($ForgotFormValues))
					{
						$validatedData      =   $ForgotForm->getData();

						$this->addEmailStatus($validatedData['forgot_email'], 'Forgot');

						// check if the email is in our database
						$MemberEmailObject  =   $this->getMemberEmailsTable()->getMemberEmailsByEmail($validatedData['forgot_email']);

        				if(is_object($MemberEmailObject))
						{
							// if it is send an email with a link to ChangePasswordWithVerifyLinkForm

							$MemberDetailsObject	=	$this->getMemberDetailsTable()->getMemberDetailsByMemberID($MemberEmailObject->member_id);

							$verifyEmailLink    	=   $this->getMemberEmailsTable()->getVerifyEmailLink($validatedData['forgot_email'], $MemberEmailObject->member_id, 'forgot-logins-success');
							$optionsArray 			=	array
													(
														'verifyEmailLink' 	=> 	$verifyEmailLink,
														'first_name'		=>	$MemberDetailsObject->first_name,
														'last_name'			=>	$MemberDetailsObject->last_name,
													);
							$this->sendEmail('forgot-logins-success', $optionsArray, 'General', $MemberEmailObject->getMemberEmailsEmailAddress());
						}

						$this->registerAccessAttempt($SubmittedFormName, 1);
						$forgotSuccessViewModel  =   new ViewModel();
						$forgotSuccessViewModel->setTemplate('auth/auth/forgot-success.phtml');
						return $forgotSuccessViewModel;
					}
					else
					{
						$this->registerAccessAttempt($SubmittedFormName, 0);
						$ForgotFormMessages           =   array_values($ForgotForm->getMessages());
                	}
				}
            }
            else
            {
                // Not one of our forms
            }
        }

        if($toRouteCreated)
		{
			return $this->redirect()->toRoute($toRouteName);
		}
		else
		{
			$viewModel  =   new ViewModel
								(
									array
									(
										'activity'                  =>  (isset($activeForm) ? $activeForm : $this->params('activity')),

										'LoginForm'                 =>  $LoginForm,
										'LoginFormMessages'         =>  $LoginFormMessages,
										'LoginAttemptMessage'       =>  $LoginAttemptMessage,

										'LoginCaptchaForm'          =>  $LoginCaptchaForm,
										'LoginCaptchaFormMessages'  =>  $LoginCaptchaFormMessages,
										'LoginCaptchaAttemptMessage'=>  $LoginCaptchaAttemptMessage,

										'reCaptcha'                 =>  (isset($reCaptcha)      ? $reCaptcha      : NULL),
										'reCaptchaError'            =>  (isset($reCaptchaError) ? $reCaptchaError : NULL),
										'PauseGifDisplaySeconds'    =>  0,

										'SignupForm'                =>  $SignupForm,
										'SignupFormMessages'        =>  $SignupFormMessages,
										'ForgotForm'                =>  $ForgotForm,
										'ForgotFormMessages'        =>  $ForgotFormMessages,

										'LoginHeaderMessage'        =>  $LoginHeaderMessage
									)
								);

			(isset($activeForm) && $activeForm == 'loginCaptcha')	||
			($this->params('activity') == 'login-captcha')
				?   $viewModel->setTemplate('auth/auth/login-captcha.phtml')
				:   $viewModel->setTemplate('auth/auth/login.phtml');

			return $viewModel;
		}
    }


	public function lostSignupVerificationAction()
	{
		// todo : make sure this email and the member attached to it haven't already signed up successfully

		$Form				=	new LostSignupVerificationForm();
		$FormName			=	"LostSignupVerificationForm";
		$FormMessages		=	"";
		$AttemptMessage		=	"";

        $reCaptcha          =   $this->getServiceLocator()->get('ReCaptchaService');
        $reCaptchaError     =   FALSE;
		$AttemptedChanges   =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('LostSignupVerificationForm', array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);

        $myAuthSession      =   new Container('NomsterzAuthSession');

		if($this->getRequest()->isPost())
		{
			$this->_writeLog('debug', "lostSignupVerificationAction form was posted successfully']");
			// Check Attempts
			if($AttemptedChanges['total'] > self::POLICY_AllowedLostSignupVerificationAttempts)
			{
				$this->applyLock('Locked:Excessive-LostSignupVerification-Attempts', '','excessive-lost-signup-verification');
				return $this->redirect()->toRoute('custom-error-21');
			}

			$FormValues 	=   $this->getRequest()->getPost();
            $Form->setData($FormValues);
			$this->_writeLog('debug', "lostSignupVerificationAction form values set']");

			if( $Form->isValid($FormValues) )
			{
				$this->_writeLog('debug', "lostSignupVerificationAction form is valid']");

				if($_POST['recaptcha_challenge_field'] != '' && $_POST['recaptcha_response_field'] != '')
				{
					$this->_writeLog('debug', "lostSignupVerificationAction - You completed the ReCaptcha form.']");

					$reCaptchaResult    =   $reCaptcha->verify
														(
															$_POST['recaptcha_challenge_field'],
															$_POST['recaptcha_response_field']
														);

					if($reCaptchaResult->isValid())
					{
						$this->_writeLog('debug', "lostSignupVerificationAction - You completed the ReCaptcha form correctly.']");

						// Get the form data
						$validatedData      	=   $Form->getData();

						// Get the member emails object which contains the member id
						$MemberEmailsObject		=	$this->getMemberEmailsTable()->getMemberEmailsByEmail($validatedData['lost_signup_email']);

						if(is_object($MemberEmailsObject))
						{
							// Prepare an Email for Validation
							// setup SMTP options
							$verifyEmailLink    =   $this->getMemberEmailsTable()->getVerifyEmailLink($validatedData['lost_signup_email'], $MemberEmailsObject->getMemberEmailsMemberID(), 'verify-new-member');
							$this->sendEmail('verify-new-member-again', array('verifyEmailLink' => $verifyEmailLink), 'General', $validatedData['lost_signup_email']);

							$this->addEmailStatus($validatedData['lost_signup_email'], 'VerificationSentAgain');

							// Store admin alert for new member
							// todo: Create a cron script that checks for new members since the last check and adds alerts and sends off emails to whomever needs to know plus other tasks. Call it process new members

							// Add

							// Redirect to Successful Signup Page that informs them of the need to validate the email before they can enjoy the free 90 day Premium membership
							$this->registerAccessAttempt('LostSignupVerificationForm', 1);
							return $this->redirect()->toRoute('member-signup-again-success');
						}
						else
						{
							$this->registerAccessAttempt('LostSignupVerificationForm', 0);
							$this->_writeLog('debug', "lostSignupVerificationAction - MemberEmailsObject is not an object.']");
							$AttemptMessage 	=	"No member was found with this email address. Please, recheck your email address or contact Customer Support.";
						}
					}
					else
					{
						$this->registerAccessAttempt('LostSignupVerificationForm', 0);
						$this->_writeLog('debug', "lostSignupVerificationAction - You completed the ReCaptcha form incorrectly.']");
						$reCaptchaError     =   TRUE;
					}
				}
				else
				{
					$this->registerAccessAttempt('LostSignupVerificationForm', 0);
					$this->_writeLog('debug', "lostSignupVerificationAction - You forgot to complete the ReCaptcha form. Please, retry.']");
					$AttemptMessage 	=	"You forgot to complete the ReCaptcha form. Please, retry.";
				}
			}
			else
			{
				$this->registerAccessAttempt('LostSignupVerificationForm', 0);
				$this->_writeLog('debug', "lostSignupVerificationAction form is not valid']");
				$FormMessages 	= 	$Form->getMessages();
			}

		}

		$viewModel  		=   new ViewModel
								(
									array
									(
										'Form'          			=>  $Form,
										'FormMessages'         		=>  $FormMessages,
										'AttemptMessage'       		=>  $AttemptMessage,

										'reCaptcha'                 =>  (isset($reCaptcha)      ? $reCaptcha      : NULL),
										'reCaptchaError'            =>  (isset($reCaptchaError) ? $reCaptchaError : NULL),
										'PauseGifDisplaySeconds'    =>  0,
									)
								);
        return $viewModel;
	}

	/**
	 * This is the core login check process after the form entries and expected application settings have been verified
	 *
	 * @param        $SubmittedForm
	 * @param        $SubmittedFormName
	 * @param        $validatedDataEmailAddressKey
	 * @param        $validatedDataPasswordKey
	 * @param string $redirectOnSuccess
	 *
	 * @return array
	 */
	public function coreAccessAttemptProcess($SubmittedForm, $SubmittedFormName, $validatedDataEmailAddressKey, $validatedDataPasswordKey, $redirectOnSuccess)
    {
		$this->_writeLog('debug', "coreAccessAttemptProcess - in");

		// todo : Only ValidMember status can login
        $validatedData      =   $SubmittedForm->getData();
        $this->SiteUser     =   $this->getUser(); 			// Get the Site User so you can associate this user behaviour with this new member
		$this->_writeLog('debug', "coreAccessAttemptProcess - form data and user are got");

        // Use Verified email to get the member emails object which contains the member id
        $MemberEmailObject  =   $this->getMemberEmailsTable()->getMemberEmailsByEmail($validatedData[$validatedDataEmailAddressKey]);
		$this->_writeLog('debug', "coreAccessAttemptProcess - Used Verified email [validatedData[$validatedDataEmailAddressKey]] to get the member emails object : <pre>" . print_r($MemberEmailObject, 1) . "</pre>");

        if(is_object($MemberEmailObject))
        {
			$this->_writeLog('debug', "coreAccessAttemptProcess - MemberEmailObject is object");
            $MemberObject           =   $this->getMemberTable()->getMember($MemberEmailObject->member_id);
			$this->_writeLog('debug', "coreAccessAttemptProcess - The member object : <pre>" . print_r($MemberObject, 1) . "</pre>");

            $MemberStatusObject     =   $this->getMemberStatusTable()->getMemberStatusByMemberID($MemberObject->id);
			$this->_writeLog('debug', "coreAccessAttemptProcess - The member status object : <pre>" . print_r($MemberStatusObject, 1) . "</pre>");


            if(is_object($MemberObject))
            {
				$this->_writeLog('debug', "coreAccessAttemptProcess - MemberObject is object");
                /**
                 * At this point you are about to login the member.
                 * First you want to make sure the member is allowed to login
                 * Check if this member is not an employee
                 * Check what there member status is
                 * Check if their financial status is valid
                 * Have these variables ready in scope or session
                 */

				// Check the Members Force
				$ValidForce 				=	$this->memberHasNoForce($MemberStatusObject->status, $SubmittedFormName, $MemberObject->id);

				// Check Member Type & Status
				$ValidStatus				=	$this->checkMemberTypeAndStatus($MemberObject->member_type, $MemberStatusObject->status, $SubmittedFormName);

				// Check if their financial status is valid
				$ValidFinancialStatus		=	$this->checkMemberFinancialStatus();

				if($ValidStatus['AttemptStatus'] && $ValidForce['AttemptStatus'] && $ValidFinancialStatus['AttemptStatus'])
				{
					$this->_writeLog('debug', "coreAccessAttemptProcess - Status Force Financials are valid.");
					$accessAttemptArray 	=	$this->coreAccessAttempt($MemberObject, $SubmittedFormName, $validatedData[$validatedDataEmailAddressKey], $validatedData[$validatedDataPasswordKey], $redirectOnSuccess);
					$this->_writeLog('debug', "coreAccessAttemptProcess - Result of coreAccessAttempt<pre>" . print_r($accessAttemptArray, 1) . "</pre>");
					return $accessAttemptArray;
				}

				if(!$ValidStatus['AttemptStatus'])
				{
					$this->registerAccessAttempt($SubmittedFormName, 0);
					$this->_writeLog('info', "Status is invalid.");
					return 	array
							(
								'AttemptStatus' 		=>	FALSE,
								'AttemptMessage' 		=>	$ValidStatus['AttemptMessage'],
								'AttemptDisplayMessage' =>	$ValidStatus['AttemptDisplayMessage'],
								'AttemptStatusRoute' 	=>	$ValidStatus['AttemptStatusRoute'],
							);
				}

				if(!$ValidForce['AttemptStatus'])
				{
					$this->registerAccessAttempt($SubmittedFormName, 0);
					$this->_writeLog('info', "Force exists.");
					return 	array
							(
								'AttemptStatus' 		=>	FALSE,
								'AttemptMessage' 		=>	$ValidForce['AttemptMessage'],
								'AttemptDisplayMessage' =>	$ValidForce['AttemptDisplayMessage'],
								'AttemptStatusRoute' 	=>	$ValidForce['AttemptStatusRoute'],
							);
				}

				if(!$ValidFinancialStatus['AttemptStatus'])
				{
					$this->registerAccessAttempt($SubmittedFormName, 0);
					$this->_writeLog('info', "Something aint right with the money.");
					return 	array
							(
								'AttemptStatus' 		=>	FALSE,
								'AttemptMessage' 		=>	$ValidFinancialStatus['AttemptMessage'],
								'AttemptDisplayMessage' =>	$ValidFinancialStatus['AttemptDisplayMessage'],
								'AttemptStatusRoute' 	=>	$ValidFinancialStatus['AttemptStatusRoute'],
							);
				}
            }
            else
            {
                $this->registerAccessAttempt($SubmittedFormName, 0);
                $this->_writeLog('info', "Error #9 - Invalid MemberObject during " . $SubmittedFormName . " login.");
				return 	array
						(
							'AttemptStatus' 		=>	FALSE,
							'AttemptMessage' 		=>	"Error #9 - Invalid MemberEmailObject during " . $SubmittedFormName . " login.",
							'AttemptDisplayMessage' =>	"Please, contact Customer Service with regard to your access status.",
							'AttemptStatusRoute' 	=>	"custom-error-9",
						);
            }
        }
        else
        {
            $this->registerAccessAttempt($SubmittedFormName, 0);
            $this->_writeLog('info', "Error #10 - Invalid MemberEmailObject during " . $SubmittedFormName . " login.");
            return 	array
					(
						'AttemptStatus' 		=>	FALSE,
						'AttemptMessage' 		=>	"Error #10 - Invalid MemberEmailObject during " . $SubmittedFormName . " login.",
						'AttemptDisplayMessage' =>	"Please, contact Customer Service with regard to your access status.",
						'AttemptStatusRoute' 	=>	"custom-error-10",
					);
        }
    }

    public function employeeLogoutAction()
    {
        $auth = new AuthenticationService();
        $auth->clearIdentity();
        
        
        
        $viewModel  =   new ViewModel
                            (
                                array
                                (
                                    'activity'  =>  $this->params('action'),
                                )
                            );
        $viewModel->setTemplate('auth/auth/logout.phtml');
        
        return $viewModel;
    }

    public function memberLogoutExpiredSessionAction()
    {
        $this->logout();

		// Do expired Session stuff

		return $this->redirect()->toRoute('member-login-after-expired-session');
    }

    public function memberLogoutAction()
    {
        $this->logout();

		// Do intentional logout stuff

		return $this->redirect()->toRoute('member-login-after-intentional-logout');
    }

    public function logout()
    {
		if ($this->getAuthService()->hasIdentity())
        {
            $this->getAuthService()->clearIdentity();
        }
    }

    public function logoutAction()
    {
        $auth = new AuthenticationService();
        $auth->clearIdentity();



        $viewModel  =   new ViewModel
                            (
                                array
                                (
                                    'activity'  =>  $this->params('action'),
                                )
                            );
        $viewModel->setTemplate('auth/auth/logout.phtml');

        return $viewModel;
    }

	/**
	 * Processes the verification link from sign up emails and generates the Verification Details form
	 *
	 * @return \Zend\Http\Response|ViewModel
	 */
	public function verifyEmailAction()
    {
        if ($this->getRequest())
        {
            $verifiedMemberIDArray  =   $this->getMemberEmailsTable()->verifyEmailByLinkAndGetMemberIDArray($this->params('vcode'), 'VerificationDetailsForm'); // Must return both email and member id bc a member can have more than one email address
			$vcodeCreateTime		=	(is_numeric($verifiedMemberIDArray['vcodeCreateTime']) ? (int) $verifiedMemberIDArray['vcodeCreateTime'] : 0);
			$verificationDuration	=	( (strtotime("now") - $vcodeCreateTime) <= self::POLICY_AllowedVerificationSeconds_Signup ? TRUE : FALSE );

            if($verificationDuration)
			{
				if (!isset($verifiedMemberIDArray['errorNbr']) && !isset($verifiedMemberIDArray['errorMsg']))
				{
					if (isset($verifiedMemberIDArray) && is_array($verifiedMemberIDArray))
					{
						$verifiedMemberObject       =   $this->getMemberTable()->getMember($verifiedMemberIDArray['memberID']);
						$verifiedMemberEmailsObject =   $this->getMemberEmailsTable()->getMemberEmailsByEmail($verifiedMemberIDArray['email']);
						$verifiedMemberStatusObject =   $this->getMemberStatusTable()->getMemberStatusByMemberID($verifiedMemberIDArray['memberID']);

						if ($verifiedMemberIDArray['alreadyVerified'] === 0)
						{
							if (is_object($verifiedMemberObject) && is_object($verifiedMemberEmailsObject))
							{
								// Create New Member Status for this member identifying as verified and starting trial
								$NewMemberStatus        =   new MemberStatus();
								$NewMemberStatus->setMemberStatusStatus('VerifiedEmail');
								$NewMemberStatus->setMemberStatusMemberID($verifiedMemberObject->id);
								$NewMemberStatus->setMemberStatusCreationTime();
								$this->getMemberStatusTable()->saveMemberStatus($NewMemberStatus);


								// Update Member emails - verified is true verified on now
								$verifiedMemberEmailsObject->setMemberEmailsVerified(1);
								$verifiedMemberEmailsObject->setMemberEmailsVerifiedOn(strtotime('now'));
								$this->getMemberEmailsTable()->saveMemberEmails($verifiedMemberEmailsObject);
							}
							else
							{
								$this->_writeLog('info', "Error #2 - Valid verifiedMemberObject and verifiedMemberEmailsObject could not be created.");
								return $this->redirect()->toRoute('custom-error-2');
							}
						}

						$this->addEmailStatus($verifiedMemberIDArray['email'], 'Verified');

						// Create Member Details Form - also force to add name, gender, customer type and zip code and time zone in form
						$VerificationDetailsForm         = new VerificationDetailsForm();
						$VerificationDetailsFormMessages = '';
						$VerificationDetailsForm->get('vcode')->setAttribute('value', $this->params('vcode'));

						$viewModel = new ViewModel
						(
							array
							(
								'VerificationDetailsForm'         => $VerificationDetailsForm,
								'VerificationDetailsFormMessages' => $VerificationDetailsFormMessages,
							)
						);
						$viewModel->setTemplate('auth/auth/verified-email-success.phtml');

						return $viewModel;
					}
					else
					{
						$this->_writeLog('info', "Error #3 - returned value from this->getMemberEmailsTable()->verifyEmailByLinkAndGetMemberIDArray(this->params('vcode'),0) is not an array.");
						return $this->redirect()->toRoute('custom-error-3');
					}
				}
				else
				{
					$this->_writeLog('info', "Error #" . $verifiedMemberIDArray['errorNbr'] . " - " . $verifiedMemberIDArray['errorMsg'] . ".");
					return $this->redirect()->toRoute('custom-error-' . $verifiedMemberIDArray['errorNbr'] . '');
				}
			}
			else
			{
				$this->_writeLog('info', "Error #22 - verification link has expired.");
				return $this->redirect()->toRoute('custom-error-22');
			}
        }
        else
        {
            $this->_writeLog('info', "Error #4 - Bad Request.");
            return $this->redirect()->toRoute('custom-error-4');
        }
    }

	/**
	 * Processes the Verification Details form
	 *
	 * @return \Zend\Http\Response|ViewModel
	 */
	public function processVerificationDetailsAction()
    {
        // Please use your info to login to your free trial
        // success needs to be on the landing pages so the login button is right on top

        $VerificationDetailsForm            =   new VerificationDetailsForm();
		$vcodeFromApp						=	$this->params('vcode');
		$vcodeFromForm						=	$this->getRequest()->getPost('vcode');
        $VerificationDetailsForm->get('vcode')->setAttribute('value', (isset($vcodeFromForm) && !empty($vcodeFromForm) ? $vcodeFromForm : $vcodeFromApp) );
        $VerificationDetailsFormMessages    =   '';

        if($this->getRequest()->isPost())
        {
            $VerificationDetailsFormValues  =   $this->getRequest()->getPost();
            $VerificationDetailsForm->setData($VerificationDetailsFormValues);

            if ($VerificationDetailsForm->isValid($VerificationDetailsFormValues))
            {
                $validatedData          =   $VerificationDetailsForm->getData();
                $verifiedMemberIDArray  =   $this->getMemberEmailsTable()->verifyEmailByLinkAndGetMemberIDArray($validatedData['vcode'], 'VerificationDetailsForm');

                if (!isset($verifiedMemberIDArray['errorNbr']) && !isset($verifiedMemberIDArray['errorMsg']))
                {
                    if (isset($verifiedMemberIDArray) && is_array($verifiedMemberIDArray))
                    {
                        $MemberDetailsObject   =   $this->getMemberDetailsTable()->getMemberDetailsByMemberID($verifiedMemberIDArray['memberID']);

                        if(!is_object($MemberDetailsObject))
                        {
                            $MemberDetailsObject       =   new MemberDetails();
                        }

                        $MemberDetailsObject->setMemberDetailsMemberID($verifiedMemberIDArray['memberID']);
                        $MemberDetailsObject->setMemberDetailsFirstName($validatedData['first_name']);
                        $MemberDetailsObject->setMemberDetailsLastName($validatedData['last_name']);
                        $MemberDetailsObject->setMemberDetailsGender((int)$validatedData['gender']);
                        $MemberDetailsObject->setMemberDetailsZipCode($validatedData['zipcode']);
                        $MemberDetailsObject->setMemberDetailsCreationTime();
                        $MemberDetailsObject->setMemberDetailsLastUpdateTime();
                        $this->getMemberDetailsTable()->saveMemberDetails($MemberDetailsObject);

                        // Update Member Object with Member Type
                        $verifiedMemberObject   =   $this->getMemberTable()->getMember($verifiedMemberIDArray['memberID']);
                        $verifiedMemberObject->setMemberType($validatedData['member_type']);
                        $this->getMemberTable()->saveMember($verifiedMemberObject);

                        // Add new member status
                        $NewMemberStatus        =   new MemberStatus();
                        $NewMemberStatus->setMemberStatusStatus('VerifiedStartupDetails');
                        $NewMemberStatus->setMemberStatusMemberID($verifiedMemberObject->id);
                        $NewMemberStatus->setMemberStatusCreationTime();
                        $this->getMemberStatusTable()->saveMemberStatus($NewMemberStatus);

                        $NewMemberStatus        =   new MemberStatus();
                        $NewMemberStatus->setMemberStatusStatus('BeginFirst90Days');
                        $NewMemberStatus->setMemberStatusMemberID($verifiedMemberObject->id);
                        $NewMemberStatus->setMemberStatusCreationTime();
                        $this->getMemberStatusTable()->saveMemberStatus($NewMemberStatus);

                        $NewMemberStatus        =   new MemberStatus();
                        $NewMemberStatus->setMemberStatusStatus('ValidMember');
                        $NewMemberStatus->setMemberStatusMemberID($verifiedMemberObject->id);
                        $NewMemberStatus->setMemberStatusCreationTime();
                        $this->getMemberStatusTable()->saveMemberStatus($NewMemberStatus);

						$emailTemplateArrayOptions	=	array
														(
															'first_name' => $validatedData['first_name'],
															'last_name' => $validatedData['last_name'],
														);
						$this->sendEmail('genericProfileInformationChange', $emailTemplateArrayOptions, 'General', $verifiedMemberIDArray['email']);

                        return $this->redirect()->toRoute('verification-details-success');

                    }
                    else
                    {
                        $this->_writeLog('info', "Error #5 - returned value from this->getMemberEmailsTable()->verifyEmailByLinkAndGetMemberIDArray(this->params('vcode'),0) is not an array");
                        return $this->redirect()->toRoute('custom-error-5');
                    }
                }
                else
                {
                    $this->_writeLog('info', "Error #" . $verifiedMemberIDArray['errorNbr'] . " - " . $verifiedMemberIDArray['errorMsg'] . ".");
                    return $this->redirect()->toRoute('custom-error-' . $verifiedMemberIDArray['errorNbr'] . '');
                }
            }
            else
            {
                $VerificationDetailsFormMessages           =   $VerificationDetailsForm->getMessages();
            }
        }

        $viewModel      =   new ViewModel
                                (
                                    array
                                    (
                                        'VerificationDetailsForm'           =>  $VerificationDetailsForm,
                                        'VerificationDetailsFormMessages'   =>  $VerificationDetailsFormMessages,
                                    )
                                );
        $viewModel->setTemplate('auth/auth/verified-email-success.phtml');

        return $viewModel;

    }

	/**
	 * Checks if form has been populated by robots
	 *
	 * @param $formName
	 * @param $formValues
	 *
	 * @return bool
	 */
	public function isFormClean($formName, $formValues)
    {
        $returnValue    =   FALSE;

        if(is_object($formValues))
        {
            switch($formName)
            {
                case 'LoginForm'            					:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'SignupForm'     							:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'ForgotForm'     							:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'LoginCaptchaForm'     					:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;

                case 'ChangePasswordWithVerifyLinkForm'     	:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
                                                					break;

                case 'ChangePasswordWithOldPasswordForm'     	:   $dummyInput     =   array
																						(
																							'usr'           =>  '',
																							'username'      =>  '',
																							'email'         =>  '',
																							'login_email'   =>  '',
																						);
																	break;


                default  :   $dummyInput     =	array
												(
													'false'     =>  'FALSE',
												);
            }

            foreach ($dummyInput as $dumbKey => $dumbValue)
            {
                if(array_key_exists($dumbKey, $formValues))
                {
                    if($dummyInput[$dumbKey] != 'FALSE')
                    {
                        if($formValues[$dumbKey] == $dummyInput[$dumbKey])
                        {
                            $returnValue    =   TRUE;
                        }
                        else
                        {
                            $this->_writeLog('info', "Form value for dummy input has incorrect value of [" . $formValues[$dumbKey]. "]. It should be [" . $dummyInput[$dumbKey]. "].");
                            $returnValue    =   FALSE;
                        }
                    }
                    else
                    {
                        $this->_writeLog('info', "Invalid formName. => dummyInput[" . $dumbValue . "]");
                        $returnValue    =   FALSE;
                    }
                }
                else
                {
                    $this->_writeLog('info', "Array key from variable dumbKey (" . $dumbKey . ") does not exist in variable array formValues.");
                    $returnValue    =   FALSE;
                }
            }
        }
        else
        {
            $this->_writeLog('info', "Variable formValues is not an array.");
            $returnValue    =   FALSE;
        }

        return $returnValue;
    }

	/**
	 * This is the catch all method for the policies affecting whether a user/member is allowed access.
	 * It also takes into consideration reasons to lock the site that may go beyond just a single user
	 *
	 * @return bool
	 */
	public function isAccessAllowed()
	{
		$returnValue 	=	FALSE;

		if($this->isUserAllowedAccess())
		{
			$returnValue	=	TRUE;
		}

		if($this->isUserIPAddressAllowedAccess())
		{
			$returnValue	=	TRUE;
		}

		if($this->isUserMemberAllowedAccess())
		{
			$returnValue	=	TRUE;
		}

		return $returnValue;
	}

	/**
	 * This method determines if the user id is allowed access
	 *
	 * @return bool
	 */
	public function isUserAllowedAccess()
	{
		$this->SiteUser			=	$this->getUser();
		$BlockedUserStatuses 	=	array
									(
										'Locked:Excessive-Login-Attempts',
									);
		return (!in_array($this->SiteUser->getUserStatus(),$BlockedUserStatuses) ? TRUE : FALSE);
	}

	/**
	 * This determines if the ip address provided by the user is allowed access
	 *
	 * @return bool
	 */
	public function isUserIPAddressAllowedAccess()
	{
		$BlockedIPBinStatuses 	=	array
									(
										'Locked:Excessive-Login-Attempts',
									);
		return (count(array_intersect($BlockedIPBinStatuses, $this->getIPBinTable()->getIpStatusArrayByIPAddress($this->getUser()->getUserIPAddress())))  == 0 ? TRUE : FALSE);
	}

	/**
	 * Is the member associated with this user allowed access
	 *
	 * @return bool
	 */
	public function isUserMemberAllowedAccess()
	{
		$BlockedMemberStatuses 	=	array
									(
										'Locked:Excessive-Login-Attempts',
									);

		return (	$this->getUser()->getUserMemberID()*1 > 0
				&& 	!in_array
					(
						$this->getMemberStatusTable()->getMemberStatusByMemberID($this->getUser()->getUserMemberID()),
						$BlockedMemberStatuses
					)
					? 	TRUE
					: 	FALSE);
	}

	/**
	 * Stores an access attempt
	 *
	 * @param $accessFormName
	 * @param $attemptBoolean
	 */
	public function registerAccessAttempt($accessFormName, $attemptBoolean)
    {
        $AccessAttempt  =   new AccessAttempt();
        $AccessAttempt->setAccessAttemptUserID($this->getUser()->id);
        $AccessAttempt->setAccessAttemptType($accessFormName);
        $AccessAttempt->setAccessAttemptSuccess($attemptBoolean);
        $AccessAttempt->setAccessAttemptTime();
        $this->getAccessAttemptTable()->saveAccessAttempt($AccessAttempt);
    }

	/**
	 * Sends an email
	 *
	 * @param $emailTemplateName
	 * @param $emailTemplateArrayOptions
	 * @param $sentFromTag
	 * @param $sendToEmail
	 */
	public function sendEmail($emailTemplateName, $emailTemplateArrayOptions, $sentFromTag, $sendToEmail)
	{
		$config             =   $this->getConfig();
		$emailConfig        =   $config['emailOptions']['smtpOptions'];
		$emailOptions       =   new SmtpOptions($emailConfig);
		$emailTransport     =   new Mail\Transport\Smtp($emailOptions);

		$EmailTemplate      =   new EmailUtility();
		$emailContent       =   $EmailTemplate->getEmailTemplate($emailTemplateName, $emailTemplateArrayOptions);

		$templateRenderer   =   $this->getServiceLocator()->get('ViewRenderer');
		$emailHTMLContent   =   $templateRenderer->render($emailContent['html'], $emailContent['templateVariables']);
		$emailTextContent   =   $templateRenderer->render($emailContent['text'], $emailContent['templateVariables']);

		// Create HTML & Text Headers
		$emailHTMLHeader        =   new MimePart($emailHTMLContent);
		$emailHTMLHeader->type  =   "text/html";

		$emailTextHeader        =   new MimePart($emailTextContent);
		$emailTextHeader->type  =   "text/plain";

		$emailBody              =   new MimeMessage();
		$emailBody->setParts(array($emailTextHeader, $emailHTMLHeader,));

		// instance mail
		$mail   =   new Mail\Message();
		$mail->setBody($emailBody);
		$mail->setFrom($config['emailOptions']['FromEmailAddresses'][( in_array($sentFromTag, $config['emailOptions']['FromEmailAddresses']) ? $sentFromTag : 'General' )]['email'],
					   $config['emailOptions']['FromEmailAddresses'][( in_array($sentFromTag, $config['emailOptions']['FromEmailAddresses']) ? $sentFromTag : 'General' )]['senderName']);
		$mail->setTo($sendToEmail);
		$mail->setSubject($emailContent['subject']);
		$mail->setEncoding("UTF-8");
		$mail->getHeaders()->get('content-type')->setType('multipart/alternative');

		$emailTransport->send($mail);
	}

	/**
	 * Applies an appropriate lock to a user, ip, and or member and sends an email if necessary and possible
	 *
	 * @param        $lockStatus
	 * @param string $contactEmail
	 * @param string $emailTemplateName
	 * @param array  $emailTemplateOptionsArray
	 * @param string $emailTemplateSendFromTag
	 */
	public function applyLock($lockStatus, $contactEmail='', $emailTemplateName='', $emailTemplateOptionsArray=array(), $emailTemplateSendFromTag='Customer Service')
	{
		// Get the User
		$this->SiteUser 	=	$this->getUser();
		// Change the user status
		$this->SiteUser->setUserStatus($lockStatus);
		// Lock the user
		$this->getUserTable()->saveUser($this->SiteUser);

		// Create an IP Block
		$ipBinLock			=	new IPBin();
		$ipBinLock->setIPBinUserID($this->SiteUser->id);
		$ipBinLock->setIPBinMemberID($this->SiteUser->getUserMemberID());
		$ipBinLock->setIPBinIPAddress();
		$ipBinLock->setIPBinIPAddressStatus($lockStatus);
		$ipBinLock->setIPBinCreationTime();
		$ipBinLock->setIPBinLastUpdateTime();
		// Lock the user ip
		$this->getIPBinTable()->saveIPBin($ipBinLock);

		// Lock the user member
		if($this->getUser()->getUserMemberID() > 0)
		{
			$MemberStatus 	=	$this->getMemberStatusTable()->getMemberStatusByMemberID($this->getUser()->getUserMemberID());
		}
		if(is_object($MemberStatus))
		{
			$MemberStatus->setMemberStatusStatus($lockStatus);
			$this->getMemberStatusTable()->saveMemberStatus($MemberStatus);
		}

		// Validate email format
		$validator 	= 	new \Zend\Validator\EmailAddress();
		if ($validator->isValid($contactEmail))
		{
			// if email is in our database
			$MemberEmailsObject = 	$this->getMemberEmailsTable()->getMemberEmailsByEmail($contactEmail);
			if(is_object($MemberEmailsObject))
			{
				// Lock the member associated with the email address
				$MemberStatus 	=	$this->getMemberStatusTable()->getMemberStatusByMemberID($MemberEmailsObject->getMemberEmailsMemberID());
				$MemberStatus->setMemberStatusStatus($lockStatus);
				$this->getMemberStatusTable()->saveMemberStatus($MemberStatus);

				// Send an email to the member
				$this->sendEmail($emailTemplateName, $emailTemplateOptionsArray, $emailTemplateSendFromTag, $MemberEmailsObject->getMemberEmailsEmailAddress());
			}
			else
			{
				// Send an email to the user
				$this->sendEmail($emailTemplateName, $emailTemplateOptionsArray, $emailTemplateSendFromTag, $contactEmail);
			}
		}
	}


	/**
	 * Initiate the process of changing a members password
	 * at the point where the user has been identified as a member but authorization is not necessary.
	 * For instance, when you want to force a member to change an outdated password or when on-boarding old NotaryTools.net
	 * clients. This method expects a vcode parameter consisting of a member id and an email address.
	 * Without it ask to re click the link or regenerate the link in another form that happens to work exactly as the
	 * Forgot Form.
	 *
	 * @return ViewModel
	 */
	public function changePasswordWithVerifyEmailLinkAction()
	{
		$Form				=	new ChangePasswordWithVerifyLinkForm();
		$vcodeFromApp		=	$this->params('vcode');
		$vcodeFromForm		=	$this->getRequest()->getPost('vcode');
        $Form->get('vcode')->setAttribute('value', (isset($vcodeFromForm) && !empty($vcodeFromForm) ? $vcodeFromForm : $vcodeFromApp) );
		$FormMessages		=	"";
		$AttemptMessage		=	"";

        $reCaptcha          =   $this->getServiceLocator()->get('ReCaptchaService');
        $reCaptchaError     =   FALSE;
		$AttemptedChanges   =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('ChangePasswordWithVerifyLinkForm', array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);

		$myAuthSession      =   new Container('NomsterzAuthSession');

		if($this->getRequest()->isPost())
		{
			$vcodeDetails 			=	$this->getMemberEmailsTable()->verifyEmailByLinkAndGetMemberIDArray($this->getRequest()->getPost('vcode'),'ChangePasswordWithVerifyLinkForm');
			$vcodeCreateTime		=	(is_numeric($vcodeDetails['vcodeCreateTime']) ? (int) $vcodeDetails['vcodeCreateTime'] : 0);
			$verificationDuration	=	( (strtotime("now") - $vcodeCreateTime) <= self::POLICY_AllowedVerificationSeconds_ChangePassword ? TRUE : FALSE );

			if(!$verificationDuration)
			{
				$this->_writeLog('info', "Error #22 - verification link has expired.");
				return $this->redirect()->toRoute('custom-error-22');
			}

			if($AttemptedChanges['total'] > self::POLICY_AllowedChangeVerifiedMemberPasswordAttempts)
			{
				$this->applyLock('Locked:Excessive-ChangeVerifiedLinkPassword-Attempts', $vcodeDetails['email'],'excessive-change-verified-member-password');
				return $this->redirect()->toRoute('custom-error-19');
			}

			$FormValues 	=   $this->getRequest()->getPost();
            $Form->setData($FormValues);

			if( $Form->isValid($FormValues) )
			{
				if($_POST['recaptcha_challenge_field'] != '' && $_POST['recaptcha_response_field'] != '')
				{
					$reCaptchaResult    =   $reCaptcha->verify
													(
														$_POST['recaptcha_challenge_field'],
														$_POST['recaptcha_response_field']
													);

					if($reCaptchaResult->isValid())
					{
						$validatedData = $Form->getData();

						if( $vcodeDetails['email'] ==  $validatedData['change_verify_member'])
						{
							// Get the status of this email and ensure it's valid
							$emailStatusObject = $this->getEmailStatusTable()->getEmailStatusByEmail($validatedData['change_verify_member']);

							if( $emailStatusObject->getEmailStatusStatus() == 'Forgot' )
							{
								$verifiedMemberIDArray = $this->getMemberEmailsTable()->verifyEmailByLinkAndGetMemberIDArray($validatedData['vcode'], 'ChangePasswordWithVerifyLinkForm');

								if( !isset($verifiedMemberIDArray['errorNbr']) && !isset($verifiedMemberIDArray['errorMsg']) )
								{
									if( isset($verifiedMemberIDArray) && is_array($verifiedMemberIDArray) )
									{
										$this->addEmailStatus($verifiedMemberIDArray['email'], 'Remembered');

										// Create a Member Object
										$LoginCredentials = $this->getMemberTable()->generateLoginCredentials($verifiedMemberIDArray['email'], $validatedData['password']);
										$RememberedMember = $this->getMemberTable()->getMember($verifiedMemberIDArray['memberID']);
										$RememberedMember->setMemberLastUpdateTime();
										$RememberedMember->setMemberLoginCredentials($LoginCredentials[0]);
										$RememberedMember->setMemberLoginSalt1($LoginCredentials[1]);
										$RememberedMember->setMemberLoginSalt2($LoginCredentials[2]);
										$RememberedMember->setMemberLoginSalt3($LoginCredentials[3]);
										$RememberedMember = $this->getMemberTable()->getMember($this->getMemberTable()->saveMember($RememberedMember));

										$this->addEmailStatus($verifiedMemberIDArray['email'], 'Verified');

										// Send and email stating your password has been changed
										$MemberDetailsObject 		=	$this->getMemberDetailsTable()->getMemberDetailsByMemberID($verifiedMemberIDArray['memberID']);
										$emailTemplateArrayOptions	=	array
																		(
																			'first_name' 	=> 	$MemberDetailsObject->getMemberDetailsFirstName(),
																			'last_name' 	=> 	$MemberDetailsObject->getMemberDetailsLastName(),
																		);
										$this->sendEmail('genericPasswordChange', $emailTemplateArrayOptions, 'General', $validatedData['lost_signup_email']);

										$this->registerAccessAttempt('ChangePasswordWithVerifyLinkForm', 1);
										return $this->redirect()->toRoute('reset-verified-password-success');

									}
									else
									{
										$this->registerAccessAttempt('ChangePasswordWithVerifyLinkForm', 0);
										$this->_writeLog('info', "Error #5 - returned value from this->getMemberEmailsTable()->verifyEmailByLinkAndGetMemberIDArray(this->params('vcode'),0) is not an array");

										return $this->redirect()->toRoute('custom-error-5');
									}
								}
								else
								{
									$this->registerAccessAttempt('ChangePasswordWithVerifyLinkForm', 0);
									$this->_writeLog('info', "Error #" . $verifiedMemberIDArray['errorNbr'] . " - " . $verifiedMemberIDArray['errorMsg'] . ".");

									return $this->redirect()->toRoute('custom-error-' . $verifiedMemberIDArray['errorNbr'] . '');
								}
							}
							else
							{
								$this->registerAccessAttempt('ChangePasswordWithVerifyLinkForm', 0);
								$AttemptMessage = "Your login credentials can not be updated at this time. Please retry the link or contact Customer Service";
								$this->_writeLog('info', "emailStatusObject->getEmailStatusStatus != 'Forgot'");
							}
						}
						else
						{
							$this->registerAccessAttempt('ChangePasswordWithVerifyLinkForm', 0);
							$AttemptMessage = "Your new access credentials can not be updated at this time. Please retry the link or contact Customer Service";
							$this->_writeLog('info', "vcodeDetails['email'] !=  validatedData['change_verify_member']");
						}
					}
					else
					{
						$this->registerAccessAttempt('ChangePasswordWithVerifyLinkForm', 0);
						$reCaptchaError     =   TRUE;
					}
				}
				else
				{
					$this->registerAccessAttempt('ChangePasswordWithVerifyLinkForm', 0);
					$AttemptMessage 	=	"You forgot to complete the ReCaptcha form. Please, retry.";
				}
			}
			else
			{
				$this->registerAccessAttempt('ChangePasswordWithVerifyLinkForm', 0);
				$FormMessages = $Form->getMessages();
			}
		}

		$viewModel  		=   new ViewModel
								(
									array
									(
										'Form'          			=>  $Form,
										'FormMessages'         		=>  $FormMessages,
										'AttemptMessage'       		=>  $AttemptMessage,

										'reCaptcha'                 =>  (isset($reCaptcha)      ? $reCaptcha      : NULL),
										'reCaptchaError'            =>  (isset($reCaptchaError) ? $reCaptchaError : NULL),
										'PauseGifDisplaySeconds'    =>  0,
									)
								);

		$viewModel->setTemplate('auth/auth/change-password-with-verified-email-link.phtml');

        return $viewModel;
	}


	/**
	 * Initiate the process of changing a members password
	 * at the point when the user is logged in, identified, and authorized. Basically, from inside the app.
	 * Requires a logout, the user email as a username, the old password, the new password, the new password confirmed.
	 * Basically, the user is going:
	 * to be logged out,
	 * then logged in,
	 * then the login creds updated
	 * then logged out and
	 * requested to login again
	 *
	 * @return ViewModel
	 */
	public function changePasswordWithOldPasswordAction()
	{
		$this->logout();

		$Form				=	new ChangePasswordWithOldPasswordForm();
		$FormName			=	"ChangePasswordWithOldPasswordForm";
		$FormMessages		=	"";
		$AttemptMessage		=	"";

        $reCaptcha          =   $this->getServiceLocator()->get('ReCaptchaService');
        $reCaptchaError     =   FALSE;
		$AttemptedChanges   =   $this->getAccessAttemptTable()->getAccessAttemptByUserIDs('ChangePasswordWithOldPasswordForm', array($this->getUser()->id), self::POLICY_AllowedAttemptsLookBackDuration);

        $myAuthSession      =   new Container('NomsterzAuthSession');

		if($this->getRequest()->isPost())
		{
			$this->_writeLog('debug', "changePasswordWithOldPasswordAction form was posted successfully']");
			// Check Attempts
			if($AttemptedChanges['total'] > self::POLICY_AllowedChangeOldMemberPasswordAttempts)
			{
				$this->applyLock('Locked:Excessive-ChangeOldPassword-Attempts', '','excessive-change-old-member-password');
				return $this->redirect()->toRoute('custom-error-20');
			}

			$FormValues 	=   $this->getRequest()->getPost();
            $Form->setData($FormValues);
			$this->_writeLog('debug', "changePasswordWithOldPasswordAction form values set']");

			if( $Form->isValid($FormValues) )
			{
				$this->_writeLog('debug', "changePasswordWithOldPasswordAction form is valid']");

				if($_POST['recaptcha_challenge_field'] != '' && $_POST['recaptcha_response_field'] != '')
				{
					$this->_writeLog('debug', "changePasswordWithOldPasswordAction - You completed the ReCaptcha form.']");

					$reCaptchaResult    =   $reCaptcha->verify
														(
															$_POST['recaptcha_challenge_field'],
															$_POST['recaptcha_response_field']
														);

					if($reCaptchaResult->isValid())
					{
						$this->_writeLog('debug', "changePasswordWithOldPasswordAction - You completed the ReCaptcha form correctly.']");

						// Check that the old login works
						$currentLogin	=	$this->coreAccessAttemptProcess
													(
														$Form,
														'ChangePasswordWithOldPasswordForm',
														'change_old_member',
														'password',
														''
													);

						if($currentLogin['AttemptStatus'])
						{
							$this->_writeLog('debug', "changePasswordWithOldPasswordAction - Login attempt status is true.']");

							// Get the form data
							$validatedData      	=   $Form->getData();

							// Get the member emails object which contains the member id
							$MemberEmailsObject		=	$this->getMemberEmailsTable()->getMemberEmailsByEmail($validatedData['change_old_member']);

							// Create a member object
							$MemberObject 			=	$this->getMemberTable()->getMember($MemberEmailsObject->getMemberEmailsMemberID());

							// Get the new login creds of the member
							$NewLoginCredentials 	= 	$this->getMemberTable()->generateLoginCredentials($validatedData['change_old_member'], $validatedData['password']);

							// Change the login creds of the member
							$MemberObject->setMemberLastUpdateTime();
							$MemberObject->setMemberLoginCredentials($NewLoginCredentials[0]);
							$MemberObject->setMemberLoginSalt1($NewLoginCredentials[1]);
							$MemberObject->setMemberLoginSalt2($NewLoginCredentials[2]);
							$MemberObject->setMemberLoginSalt3($NewLoginCredentials[3]);
							$MemberObject 			= 	$this->getMemberTable()->getMember($this->getMemberTable()->saveMember($MemberObject));

							$this->addEmailStatus($validatedData['change_old_member'], 'ChangedPassword');
							$this->addEmailStatus($validatedData['change_old_member'], 'Verified');

							$this->addMemberStatus($MemberObject->getMemberId(), 'ChangedPassword');
							$this->addMemberStatus($MemberObject->getMemberId(), 'ValidMember');

							$this->registerAccessAttempt('ChangePasswordWithOldPasswordForm', 1);

							// Send and email stating your password has been changed
							$MemberDetailsObject 		=	$this->getMemberDetailsTable()->getMemberDetailsByMemberID($MemberObject->id);
							$emailTemplateArrayOptions	=	array
															(
																'first_name' 	=> 	$MemberDetailsObject->getMemberDetailsFirstName(),
																'last_name' 	=> 	$MemberDetailsObject->getMemberDetailsLastName(),
															);
							$this->sendEmail('genericPasswordChange', $emailTemplateArrayOptions, 'General', $validatedData['lost_signup_email']);

							// Redirect to login page with header msg saying Please login with your new access credentials
							return $this->redirect()->toRoute('member-login-after-changed-password');
						}
						else
						{
							$this->registerAccessAttempt('ChangePasswordWithOldPasswordForm', 0);
							$this->_writeLog('debug', "changePasswordWithOldPasswordAction - Login attempt status was false.']");
							$AttemptMessage 	=	$currentLogin['AttemptDisplayMessage'];

							if($currentLogin['AttemptStatusRoute'] != '')
							{
								return $this->redirect()->toRoute($currentLogin['AttemptStatusRoute']);
							}
						}
					}
					else
					{
						$this->registerAccessAttempt('ChangePasswordWithOldPasswordForm', 0);
						$this->_writeLog('debug', "changePasswordWithOldPasswordAction - You completed the ReCaptcha form incorrectly.']");
						$reCaptchaError     =   TRUE;
					}
				}
				else
				{
					$this->registerAccessAttempt('ChangePasswordWithOldPasswordForm', 0);
					$this->_writeLog('debug', "changePasswordWithOldPasswordAction - You forgot to complete the ReCaptcha form. Please, retry.']");
					$AttemptMessage 	=	"You forgot to complete the ReCaptcha form. Please, retry.";
				}
			}
			else
			{
				$this->registerAccessAttempt('ChangePasswordWithOldPasswordForm', 0);
				$this->_writeLog('debug', "changePasswordWithOldPasswordAction form is not valid']");
				$FormMessages 	= 	$Form->getMessages();
			}

		}

		$viewModel  		=   new ViewModel
								(
									array
									(
										'Form'          			=>  $Form,
										'FormMessages'         		=>  $FormMessages,
										'AttemptMessage'       		=>  $AttemptMessage,

										'reCaptcha'                 =>  (isset($reCaptcha)      ? $reCaptcha      : NULL),
										'reCaptchaError'            =>  (isset($reCaptchaError) ? $reCaptchaError : NULL),
										'PauseGifDisplaySeconds'    =>  0,
									)
								);

		$viewModel->setTemplate('auth/auth/change-password-with-old-password.phtml');

        return $viewModel;
	}

	/**
	 * Sets the current status of the email address. This is independent from the email address' owner and is used to
	 * track events occurring to the email address itself such as being forgotten or made a default email address.
	 *
	 * @param $emailAddress
	 * @param $status
	 */
	public function addEmailStatus($emailAddress, $status)
	{
		$EmailStatusObject		=	new EmailStatus();
		$EmailStatusObject->setEmailStatusEmailAddress($emailAddress);
		$EmailStatusObject->setEmailStatusStatus($status);
		$EmailStatusObject->setEmailStatusCreationTime();
		$this->getEmailStatusTable()->saveEmailStatus($EmailStatusObject);
	}

	/**
	 * Sets the current status of the member. Each status is independent and this forms a way to "track" the members
	 * activity on their own as well as in correlation with their use behaviour
	 *
	 * @param $memberID
	 * @param $status
	 */
	public function addMemberStatus($memberID, $status)
	{
		$NewMemberStatus        =   new MemberStatus();
		$NewMemberStatus->setMemberStatusStatus($status);
		$NewMemberStatus->setMemberStatusMemberID($memberID);
		$NewMemberStatus->setMemberStatusCreationTime();
		$this->getMemberStatusTable()->getMemberStatus($this->getMemberStatusTable()->saveMemberStatus($NewMemberStatus));
	}


	public function checkMemberTypeAndStatus($memberType, $memberStatus, $FormName)
	{
		if($memberType == 'notary')
		{
			switch($memberStatus)
			{
				case 'Successful-Signup'            		:   $this->_writeLog('info', "Error #7 - Blocked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'custom-error-7';
																break;

				case 'VerifiedEmail'                		:   $this->_writeLog('info', "Error #16 - Blocked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'custom-error-16';
																break;

				case 'VerifiedStartupDetails'       		:   $this->_writeLog('info', "Error #8 - Blocked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'custom-error-8';
																break;

				case 'TrialPeriodExpired'           		:   $this->_writeLog('info', "Error #11 - Blocked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'custom-error-11';
																break;

				case 'Paused-Member'                		:   $this->_writeLog('info', "Error #12 - Blocked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'custom-error-12';
																break;

				case 'Cancelled-Member'             		:   $this->_writeLog('info', "Error #13 - Blocked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'custom-error-13';
																break;

				case 'Paused-Financial'             		:   $this->_writeLog('info', "Error #14 - Blocked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'custom-error-14';
																break;

				case 'Cancelled-Financial'          		:   $this->_writeLog('info', "Error #17 - Blocked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'custom-error-17';
																break;

				case 'Locked:Excessive-Login-Attempts'		:   $this->_writeLog('info', "Locked MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
																$this->registerAccessAttempt($FormName, 0);
																$AttemptStatus 			=	FALSE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'access-temp-disabled';
																break;


				case 'ValidMember'                     		:   $AttemptStatus 			=	TRUE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'';
																break;

				case 'Premium'                      		:   $AttemptStatus 			=	TRUE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'';
																break;

				case 'Standard'                     		:   $AttemptStatus 			=	TRUE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'';
																break;

				case 'Basic'                        		:   $AttemptStatus 			=	TRUE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'';
																break;

				case 'BeginFirst90Days'             		:   $AttemptStatus 			=	TRUE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'';
																break;

				case 'First90DaysPlus30'            		:   $AttemptStatus 			=	TRUE;
																$AttemptMessage 		=	"";
																$AttemptDisplayMessage 	=	"";
																$AttemptStatusRoute 	=	'';
																break;




				default     :   $this->_writeLog('info', "Error #6 - Invalid MemberStatusObject status ['". $memberStatus ."'] during " . $FormName . " .");
								$this->registerAccessAttempt($FormName, 0);
								$AttemptStatus 			=	FALSE;
								$AttemptMessage 		=	"";
								$AttemptDisplayMessage 	=	"";
								$AttemptStatusRoute 	=	'custom-error-6';
			}
		}
		else
		{
			$this->registerAccessAttempt($FormName, 0);
			// this may be the wrong login for you. Please check your signup/membership confirmation for the correct login url
			$this->_writeLog('info', "Error #15 - unknown member with member type (" . $memberType . ") attempting login during " . $FormName . " login.");
			$AttemptStatus 			=	FALSE;
			$AttemptMessage 		=	"";
			$AttemptDisplayMessage 	=	"";
			$AttemptStatusRoute 	=	'custom-error-15';
		}

		return 	array
				(
					'AttemptStatus' 		=>	$AttemptStatus,
					'AttemptMessage' 		=>	$AttemptMessage,
					'AttemptDisplayMessage' =>	$AttemptDisplayMessage,
					'AttemptStatusRoute' 	=>	$AttemptStatusRoute,
				);
	}

	/**
	 * The "Force:" keyword is used to denote that the user, having passed basic identification (NOT Authentication)
	 * needs to perform certain actions or have certain actions performed upon them
	 *
	 * @param $memberStatus
	 * @param $SubmittedFormName
	 * @param $memberID
	 *
	 * @return bool|\Zend\Http\Response
	 */
	public function memberHasNoForce($memberStatus, $SubmittedFormName, $memberID)
	{
		if(substr($memberStatus, 0, 6) == 'Force:')
		{
			switch($memberStatus)
			{
				case 'Force:ChangePasswordWithVerifyEmailLink' 	:   // On-boarding from NotaryTools.net
																	// todo : make sure that there is a link that helps them regenerate the email if they did not get it
																	$this->_writeLog('info', "". $memberStatus ." during " . $SubmittedFormName . " login for Member ID " . $memberID . ".");
																	$AttemptStatus 			=	FALSE;
																	$AttemptMessage 		=	"";
																	$AttemptDisplayMessage 	=	"";
																	$AttemptStatusRoute 	=	'change-password-verification';
																	break;

				case 'Force:ChangePasswordWithOldPassword'		:   /**
																	 * Force member to change password
																	 * Keep in mind this presents a slew of problems
																	 * 1. Password cannot be the same as the previous
																	 * 2. Inform members not to do stupid things like change password to what it was before
																	 * 3. Add Status 'PasswordChange:TooOld
																	 */
																	$this->_writeLog('info', "". $memberStatus ." during " . $SubmittedFormName . " login for Member ID " . $memberID . ".");
																	$AttemptStatus 			=	FALSE;
																	$AttemptMessage 		=	"";
																	$AttemptDisplayMessage 	=	"";
																	$AttemptStatusRoute 	=	'force-change-password-2';
																	break;

				default : 	$AttemptStatus 			=	TRUE;
							$AttemptMessage 		=	"";
							$AttemptDisplayMessage 	=	"";
							$AttemptStatusRoute 	=	'';
			}
		}
		else
		{
			$AttemptStatus 			=	TRUE;
			$AttemptMessage 		=	"";
			$AttemptDisplayMessage 	=	"";
			$AttemptStatusRoute 	=	'';
		}

		return 	array
				(
					'AttemptStatus' 		=>	$AttemptStatus,
					'AttemptMessage' 		=>	$AttemptMessage,
					'AttemptDisplayMessage' =>	$AttemptDisplayMessage,
					'AttemptStatusRoute' 	=>	$AttemptStatusRoute,
				);
	}


	public function checkMemberFinancialStatus()
	{
		$AttemptStatus 			=	TRUE;
		$AttemptMessage 		=	"";
		$AttemptDisplayMessage 	=	"";
		$AttemptStatusRoute 	=	'';

		return 	array
				(
					'AttemptStatus' 		=>	$AttemptStatus,
					'AttemptMessage' 		=>	$AttemptMessage,
					'AttemptDisplayMessage' =>	$AttemptDisplayMessage,
					'AttemptStatusRoute' 	=>	$AttemptStatusRoute,
				);
	}


	/**
	 * coreAccessAttempt
	 *
	 * @param $MemberObject
	 * @param $SubmittedFormName
	 * @param $emailAddress
	 * @param $password
	 *
	 * @return array
	 */
	public function coreAccessAttempt($MemberObject, $SubmittedFormName, $emailAddress, $password, $redirectOnSuccess)
	{
		$this->_writeLog('debug', "coreAccessAttempt - in");

		// todo : validate that member object is an object and has the necessary properties

		$this->getAuthService()
			 ->getAdapter()
			 ->setIdentity($MemberObject->id)
			 ->setCredential
				(
					$this->getMemberTable()
						 ->generateMemberLoginCredentials
							(
								(string) $emailAddress,
								(string) $password,
								(string) $MemberObject->salt1,
								(string) $MemberObject->salt2,
								(string) $MemberObject->salt3
							)
				);

		$this->_writeLog('debug', "coreAccessAttempt - getAuthService getAdapter setIdentity MemberObject->id = " . $MemberObject->id);
		$this->_writeLog('debug', "coreAccessAttempt - setCredential");
		$this->_writeLog('debug', "coreAccessAttempt - generateMemberLoginCredentials vars - emailAddress = " . $emailAddress);
		$this->_writeLog('debug', "coreAccessAttempt - generateMemberLoginCredentials vars - password = " . $password);
		$this->_writeLog('debug', "coreAccessAttempt - generateMemberLoginCredentials vars - MemberObject->salt1 = " . $MemberObject->salt1);
		$this->_writeLog('debug', "coreAccessAttempt - generateMemberLoginCredentials vars - MemberObject->salt2 = " . $MemberObject->salt2);
		$this->_writeLog('debug', "coreAccessAttempt - generateMemberLoginCredentials vars - MemberObject->salt3 = " . $MemberObject->salt3);

		$result 			=   $this->getAuthService()->authenticate();
		$this->_writeLog('debug', "this->getAuthService()->authenticate() result = <pre>" . print_r($result, 1) . "</pre>");
		$resultMessages     =   '';
		foreach ($result->getMessages() as $message)
		{
			$resultMessages .= "$message\n";
		}
		$this->_writeLog('debug', "coreAccessAttempt - resultMessages = <pre>" . print_r($resultMessages, 1) . "</pre>");

		switch ($result->getCode())
		{
			case Result::SUCCESS                        :   $this->getAuthService()
																 ->getStorage()
																 ->write
																	(
																		$this->getAuthService()
																			 ->getAdapter()
																			 ->getResultRowObject
																				(
																					null,
																					'login_credentials' // omit the creds, return everything else in this row
																				)
																	);
															// Clean up session

															$this->registerAccessAttempt($SubmittedFormName, 1);
															$this->_writeLog('debug', "coreAccessAttempt - SUCCESS routeForSuccess = <pre>" . ( isset($routeForSuccess) ? print_r($routeForSuccess, 1) : "") . "</pre>");
															$this->_writeLog('debug', "coreAccessAttempt - array vars returned");
															$AttemptStatus				=	TRUE;
															$AttemptMessage 			=	"SUCCESS";
															$AttemptDisplayMessage 		=	"Your access attempt was successful.";
															$AttemptStatusRoute			=	isset($redirectOnSuccess[1]) ? $redirectOnSuccess : '';
															break;


			case Result::FAILURE                        :   $this->registerAccessAttempt($SubmittedFormName, 0);
															$this->_writeLog('info', "Error #18 - Access FAILURE => Access Message => ".$resultMessages." during " . $SubmittedFormName . " .");
															$AttemptStatus				=	FALSE;
															$AttemptMessage 			=	"FAILURE";
															$AttemptDisplayMessage    	=   'Unfortunately, your access attempt did not succeed. Please retry.';
															$AttemptStatusRoute			=	'';
															break;


			case Result::FAILURE_IDENTITY_NOT_FOUND     :   $this->registerAccessAttempt($SubmittedFormName, 0);
															$this->_writeLog('info', "Error #19 - Access FAILURE_IDENTITY_NOT_FOUND => Access Message => ".$resultMessages." during " . $SubmittedFormName . " .");
															$AttemptStatus				=	FALSE;
															$AttemptMessage 			=	"FAILURE_IDENTITY_NOT_FOUND";
															$AttemptDisplayMessage    	=   'Unfortunately, we could not find your account. Please retry.';
															$AttemptStatusRoute			=	'';
															break;


			case Result::FAILURE_IDENTITY_AMBIGUOUS     :   $this->registerAccessAttempt($SubmittedFormName, 0);
															$this->_writeLog('info', "Error #20 - Access FAILURE_IDENTITY_AMBIGUOUS => Access Message => ".$resultMessages." during " . $SubmittedFormName . " .");
															$AttemptStatus				=	FALSE;
															$AttemptMessage 			=	"FAILURE_IDENTITY_AMBIGUOUS";
															$AttemptDisplayMessage    	=   'Unfortunately, your access attempt did not succeed. Please retry.';
															$AttemptStatusRoute			=	'';
															break;


			case Result::FAILURE_CREDENTIAL_INVALID     :   $this->registerAccessAttempt($SubmittedFormName, 0);
															$this->_writeLog('info', "Error #21 - Access FAILURE_CREDENTIAL_INVALID => Access Message => ".$resultMessages." during " . $SubmittedFormName . " .");
															$AttemptStatus				=	FALSE;
															$AttemptMessage 			=	"FAILURE_CREDENTIAL_INVALID";
															$AttemptDisplayMessage    	=   'Unfortunately, your email and password were not recognized. Please retry.';
															$AttemptStatusRoute			=	'';
															break;


			case Result::FAILURE_UNCATEGORIZED          :   $this->registerAccessAttempt($SubmittedFormName, 0);
															$this->_writeLog('info', "Error #22 - Access FAILURE_UNCATEGORIZED => Access Message => ".$resultMessages." during " . $SubmittedFormName . " .");
															$AttemptStatus				=	FALSE;
															$AttemptMessage 			=	"FAILURE_UNCATEGORIZED";
															$AttemptDisplayMessage    	=   'Unfortunately, your access attempt did not succeed. Please retry.';
															$AttemptStatusRoute			=	'';
															break;



			default :   $this->registerAccessAttempt($SubmittedFormName, 0);
						$this->_writeLog('info', "Error #23 - Access default => Access Message => ".$resultMessages." during " . $SubmittedFormName . " .");
						$AttemptStatus				=	FALSE;
						$AttemptMessage 			=	"UNKNOWN";
						$AttemptDisplayMessage    	=   'Unfortunately, your access attempt did not succeed. Please retry.';
						$AttemptStatusRoute			=	'';
						break;
		}

		$this->_writeLog('debug', "coreAccessAttempt - returning array.");
		return 	array
				(
					'AttemptStatus' 		=>	$AttemptStatus,
					'AttemptMessage' 		=>	$AttemptMessage,
					'AttemptDisplayMessage' =>	$AttemptDisplayMessage,
					'AttemptStatusRoute' 	=>	$AttemptStatusRoute,
				);
	}
}
