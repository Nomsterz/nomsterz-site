<?php
/**
 * Class MemberController
 *
 * filename:   MemberController.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       2/10/14 5:51 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Member\Controller;

use Zend\Mvc\Controller\AbstractActionController;

use Zend\Mail;

use Zend\Session\Container;

use Zend\Authentication\Adapter\DbTable as AuthAdapter;

class MemberController extends AbstractActionController
{
    protected $memberID   	=   null;
    protected $memberType   =   null;
    protected $PageHit  	=   null;
    protected $SiteUser 	=   null;
    protected $Config   	=   null;

    /**
     * Authentication
     */
    protected $storage;
    protected $authservice;



	public function getMemberIdentity()
	{
		// Check if logged in
		if($this->getAuthService()->hasIdentity())
        {
            $identity = $this->getAuthService()->getIdentity();
			#echo "<pre>" . print_r($identity, 1) . "</pre>";
			if(is_object($identity) && $identity->id > 0 && !empty($identity->member_type) && $identity->member_type != 'unknown')
			{
				$this->memberID 	=	$identity->id;
				$this->memberType 	=	$identity->member_type;
				return TRUE;
			}
        }
		/**
		 * Session has Expired
		 *
		 * 1. Elegantly and Completely Logout Member
		 * 2. Redirect them to a "Your Session has expired page"
		 */
		return $this->redirect()->toRoute('member-logout-expired-session');
	}

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
            $this->storage = $this->getServiceLocator()->get('Auth\NomsterzAuthMemberStorage');
        }

        return $this->storage;
    }



	/**
	 * @param string $priority
	 * @param        $message
	 */
	public function _writeLog($priority='info', $message)
    {
        $this->getServiceLocator()->get('Zend\Log\Member')->$priority($message);
    }

    protected function getConfig()
    {
        if (!$this->Config)
        {
            $this->Config   =   $this->getServiceLocator()->get('config');
        }

        return $this->Config;
    }

    public function getMemberHomeAction()
    {
        // Check if logged in
		$this->getMemberIdentity();

		switch($this->memberType)
		{
			case 'notary'	:	return $this->redirect()->toRoute
															(
														  		'notary-home',
																array
																(
																	'params'	=> 	array
																					(
																						'memberID'		=>	$this->memberID,
																					)
																)
															);
								break;

			default : throw new \Exception("What kind of member (" . $this->memberType . ") is this?");
		}


    }
}
