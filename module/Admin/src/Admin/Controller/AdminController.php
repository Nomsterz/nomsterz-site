<?php
/**
 * Class AdminController
 *
 * filename:   AdminController.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/15/14 11:06 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
{
    protected $Employee =   null;
    protected $PageHit  =   null;
    protected $Config   =   null;

    /**
     * Tables Used in this controller
     * 1. Employee
     * 2. Error
     */
    protected $employeeTable;
    protected $errorTable;
    protected $pageHitTable;
    protected $faqTable;
    protected $faqHitTable;

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
            $this->storage = $this->getServiceLocator()->get('Auth\NomsterzAuthMemberStorage');
        }

        return $this->storage;
    }

    public function _writeLog($priority='info', $message)
    {
        $this->getServiceLocator()->get('Zend\Log\Admin')->$priority($message);
    }

    protected function getConfig()
    {
        if (!$this->Config)
        {
            $this->Config   =   $this->getServiceLocator()->get('config');
        }

        return $this->Config;
    }

    public function indexAction()
    {
        // Log Controller Access
        $this->_writeLog('info','AdminModule-AdminController-IndexAction instantiated log');

        /**
         * Get Configs
         */
        $this->getConfig();

        /**
         * Get the Employee
         */

        if ($this->getAuthService()->hasIdentity())
        {
            // Identity exists; get it
            $identity   =   $this->getAuthService()->getIdentity();
        }
        else
        {
            $identity = "sgdsgsdgdsgdsb";
        }


        $viewModel  =   new ViewModel
                        (
                            array
                            (
                                'employeeFullName'     =>  'Chukky Nze',
                                'identity'     =>  $identity,
                            )
                        );

        return $viewModel;
    }


    public function loginAction()
    {
        // Log Controller Access
        $this->_writeLog('info','AdminModule-AdminController-loginAction instantiated log');

        $viewModel  =   new ViewModel
                        (
                            array
                            (
                                'employeeFullName'     =>  'Chukky Nze',
                            )
                        );

        return $viewModel;
    }
}