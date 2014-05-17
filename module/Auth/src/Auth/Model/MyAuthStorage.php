<?php
/**
 * Class MyAuthStorage
 *
 * filename:   MyAuthStorage.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       2/1/14 7:53 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Model;

use Zend\Authentication\Storage;
 
class MyAuthStorage extends Storage\Session
{
    public function setRememberMe($rememberMe = 0, $time = 1209600)
    {
         if ($rememberMe == 1) {
             $this->session->getManager()->rememberMe($time);
         }
    }

    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}
