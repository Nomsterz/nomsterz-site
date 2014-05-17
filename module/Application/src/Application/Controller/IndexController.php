<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Di\Config;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\EventManager\EventManagerInterface;
use Zend\Session\Container;

use Application\Model\Error;
use Application\Model\User;
use Application\Model\Pagehit;

class IndexController extends AbstractActionController
{
    const POLICY_UserIDCookieDuration       =   365;

    protected $SiteUser =   null;
    protected $PageHit  =   null;
    protected $Config   =   null;

    /**
     * Tables Used in this controller
     * 1. User
     * 2. Error
     */
    protected $userTable;
    protected $errorTable;
    protected $pageHitTable;






    protected function getConfig()
    {
        if (!$this->Config)
        {
            $this->Config   =   $this->getServiceLocator()->get('config');
        }

        return $this->Config;
    }



    public function getUserTable()
    {
        if (!$this->userTable)
        {
            $sm                 =   $this->getServiceLocator();
            $this->userTable    =   $sm->get('Application\Mapper\UserTable');
        }
        return $this->userTable;
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

    public function getPageHitTable()
    {
        if (!$this->pageHitTable)
        {
            $sm                     =   $this->getServiceLocator();
            $this->pageHitTable     =   $sm->get('Application\Mapper\PagehitTable');
        }
        return $this->pageHitTable;
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

            if(is_object($siteUser))
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
        $newUser->setUserType('Open');
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

    public function registerPageHit()
    {
        $pageHit      =   new Pagehit();
        $pageHit->setPageHitUserId($this->getUser()->getUserId());
        $pageHit->setPageHitCookies();
        $pageHit->setPageHitURLLocation($this->getRequest()->getUriString());
        $pageHit->setPageHitClientTime(0);
        $pageHit->setPageHitServerTime();

        $this->getPageHitTable()->savePagehit($pageHit);
    }


    public function _writeLog($priority='info', $message)
    {
        $this->SiteUser =   $this->getUser();
        $message        =   "
                Namespace   =>  " . __NAMESPACE__ . ";
                Controller  =>  " . $this->params('controller') . ";
                Action      =>  " . $this->params('action') . ";
                User        =>  " . ($this->SiteUser->id     ? $this->SiteUser->id   : 0) . ";
                Member      =>  " . (isset($this->SiteMember->id) ? $this->SiteMember->id : 0) . ";
                Message     =>  " . $message;
        $this->getServiceLocator()->get('Zend\Log')->$priority($message);
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
     *
     * Inject an EventManager instance
     *
     * Perform preparatory tasks
     *
     * Things that have to happen
     * -------------------------------------------------
     * 1. Identify the user
     * 2. Identify the users attributes - new/old, customer, member, etc
     * 3. Register a site hit
     * 4. Get hit attributes - cookies, sessions, social sessions, browsers, devices, etc
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



    public function loginAction()
    {
        $viewModel  =   new ViewModel
                        (
                            array
                            (
                                'activity'      =>  $this->params('activity'),
                            )
                        );

        return $viewModel;
    }



    public function indexAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }


    public function whatwedoAction()
    {
        $this->registerPageHit();


        $viewModel  =   new ViewModel
                        (
                            array
                            (
                                'customer'     =>  $this->params('customer'),
                            )
                        );

        switch($this->params('customer'))
        {
            case 'notaries'             :   $viewModel->setTemplate('application/index/whatwedo-for-notaries.phtml');
                                            break;
            case 'signing-agencies'     :   $viewModel->setTemplate('application/index/whatwedo-for-signing-agencies.phtml');
                                            break;
            case 'lenders'              :   $viewModel->setTemplate('application/index/whatwedo-for-lenders.phtml');
                                            break;

            default : $viewModel->setTemplate('application/index/whatwedo-for-notaries.phtml');
        }

        return $viewModel;
    }


    public function aboutusAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }
    public function theteamAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }


    public function howmuchAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }


    public function featuresAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }


    public function ourblogAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }


    public function faqAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }
    public function suggestionsAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }
    public function customersupportAction()
    {
        $this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }


    public function signupSuccessAction()
    {
		$this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }


    public function verificationDetailsSuccessAction()
    {
		$this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }

    public function resetPasswordSuccessAction()
    {
		$this->registerPageHit();

		switch($this->params('activity'))
		{
			case 'verified-link'	:	$PageMsg 	=	'You have successfully used the verification link to reset your password. Click login to access the Member Section.';
										break;
			case 'old-password'		:	$PageMsg 	=	'You have successfully reset your old password. Click login to access the Member Section.';
										break;

			default	:	$this->_writeLog('info','Bad case for resetPasswordSuccessAction()');
						$PageMsg 	=	'It appears you have reached the wrong page. Please, click the home or logo button to get back on track.'; break;
		}

        $viewModel      =   new ViewModel
                                (
                                    array
                                    (
                                        'PageMsg'   =>  $PageMsg,
                                    )
                                );
        return $viewModel;
    }


    public function memberAlreadyExistsAction()
    {

        // todo: if a user has a member id then they have already signed up and should not see this page
        // todo: store a light alert for admin purposes


		$this->registerPageHit();

        return  new ViewModel
                    (
                        array
                        (
                            'users'     =>  '',
                        )
                    );
    }

    public function processErrorsAction()
    {
        // todo:  this can be stored in the database so we can track which errors occur the most and at what frequency and by which member and user

		// Customer Service should be the first point of call not Tech support
        $techSupport            =   "<a href='mailto:technicalsupport@nomsterz.com?subject=Error:".$this->params('errorNbr')."'>Technical Support</a>";
        $customerService        =   "<a href='mailto:customersupport@nomsterz.com?subject=Error:".$this->params('errorNbr')."'>Customer Support</a>";
        $chooseSubscription     =   "<a href='/plans'>Choose a Plan</a>";

        switch($this->params('errorNbr'))
        {
			case 'accessTempDisabled'	:	$ErrorMsg       =   "Unfortunately, your access has been temporarily disabled. Please, email " . $customerService;
                            				break;

			case 'accessPermDisabled'	:	$ErrorMsg       =   "Unfortunately, your access has been permanently disabled. Please, email " . $customerService;
                            				break;




            case '1'    :   $ErrorMsg       =   "Sorry, your email verification link could not be validated. Please, re-click the link or email " . $customerService;
                            break;

            case '2'    :   $ErrorMsg       =   "Sorry, your email verification link could not be validated. Please, re-click the link or email " . $customerService;
                            break;

            case '3'    :   $ErrorMsg       =   "Sorry, your email verification link could not be validated. Please, re-click the link or email " . $customerService;
                            break;

            case '4'    :   $ErrorMsg       =   "Sorry, your email verification link could not be validated. Please, re-click the link or email " . $customerService;
                            break;

            case '5'    :   $ErrorMsg       =   "Sorry, your verification details could not be saved. Please, re-click the email verification link or contact " . $customerService;
                            break;

            case '6'    :   $ErrorMsg       =   "Sorry, your login could not be processed at this time. Please, retry or contact " . $customerService;
                            break;

            case '7'    :   $ErrorMsg       =   "Please, verify your email before your access credentials into your Nomsterz.com member section can be processed. If this error is incorrect, Please email" . $customerService;
                            break;

            case '8'    :   $ErrorMsg       =   "Sorry, your access credentials into your Nomsterz.com member section could not be processed at this time. A message has been sent to " . $customerService;
                            break;

            case '9'    :   $ErrorMsg       =   "Sorry, your login could not be processed at this time. Please, retry or contact " . $customerService;
                            break;

            case '10'   :   $ErrorMsg       =   "Your email address is not yet registered as a member. Please, click the signup link above or contact " . $customerService;
                            break;

            case '11'   :   $ErrorMsg       =   "Unfortunately, your trial period has expired. To re-activate your account please, " . $chooseSubscription;
                            break;

            case '12'   :   $ErrorMsg       =   "Unfortunately, your account has been paused. Please email " . $customerService;
                            break;

            case '13'   :   $ErrorMsg       =   "Unfortunately, your account has been cancelled. Please email " . $customerService;
                            break;

            case '14'   :   $ErrorMsg       =   "Unfortunately, your account requires an active subscription. Please email " . $customerService;
                            break;

            case '15'   :   $ErrorMsg       =   "This may be the wrong access point into Nomsterz.com for you or your signup process is unfinished.</p>

            									<p>Please check your signup/membership confirmation for the correct login url or have it <a href='/resendSignupConfirmation'>resent</a></p>
            									<p>If that doesn't work for you, contact " . $customerService;
                            break;

            case '16'   :   $ErrorMsg       =   "Please, first enter your personalized account details so we can customize your account or contact " . $customerService;
                            break;

            case '17'   :   $ErrorMsg       =   "Unfortunately, your account has been cancelled. Please email " . $customerService;
                            break;

            case '18'   :   $ErrorMsg       =   "Unfortunately, you've attempted to sign up too many times. Please email " . $customerService . ". We are sure they can help you.";
                            break;

            case '19'   :   $ErrorMsg       =   "Unfortunately, you've attempted to change your password with the link we have sent you too many times. Please email " . $customerService . ". We are sure they can help you.";
                            break;

            case '20'   :   $ErrorMsg       =   "Unfortunately, you've attempted to change your password too many times. Please email " . $customerService . ". We are sure they can help you.";
                            break;

            case '21'   :   $ErrorMsg       =   "Unfortunately, you've attempted to resend your signup verification email too many times. Please email " . $customerService . ". We are sure they can help you.";
                            break;

            case '22'   :   $ErrorMsg       =   "Unfortunately your verification link has expired. Please, retry or email " . $customerService . ". We are sure they can help you.";
                            break;

			/**
			 * Change Password Errors
			 */


			default     :   $ErrorMsg       =   "Sorry, We Can't Find What You are Looking For.";
        }

        $viewModel      =   new ViewModel
                                (
                                    array
                                    (
                                        'Exclamation'   =>  (isset($Exclamation) ?  $Exclamation : 'Uh Oh!' ),
                                        'ErrorMsg'   	=>  $ErrorMsg,
                                    )
                                );
        $viewModel->setTemplate('application/error/custom');

        return $viewModel;
    }
}
