<?php
/**
 * Class Error
 *
 * filename:   Error.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/6/14 9:33 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Application\Model;
 
/**
 * @ORM\Entity
 * @ORM\Table
 * (
 *      name="error_tickets",
 *      indexes=
 *      {
 *          @ORM\Index( name="ndx1", columns={"user_id"}),
 *          @ORM\Index( name="ndx2", columns={"cookie_name"}),
 *          @ORM\Index( name="ndx3", columns={"ip_address"}),
 *          @ORM\Index( name="ndx4", columns={"error_time"}),
 *
 *          @ORM\Index( name="ndx1_4", columns={"user_id","error_time"}),
 *          @ORM\Index( name="ndx2_4", columns={"cookie_name","error_time"}),
 *          @ORM\Index( name="ndx3_4", columns={"ip_address","error_time"}),
 *          @ORM\Index( name="ndx1_3", columns={"user_id","ip_address"}),
 *      }
 * )
 *
 */
class Error
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    public $user_id          =   0;

    /**
     * @ORM\Column(type="string", length=16)
     *
     * @var string
     */
    public $cookie_name      =   '';

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $cookie_value     =   '';

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $mvc_namespace     =   '';

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $mvc_controller     =   '';

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $mvc_action     =   '';

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $script_name     =   '';

    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    public $uri     =   '';

    /**
     * @ORM\Column(type="integer", length=24)
     *
     * @var int
     */
    public $error_time       =   0;

    /**
     * @ORM\Column(type="string", length=2)
     *
     * @var string
     */
    public $error_level      =   '';

    /**
     * @ORM\Column(type="string", length=512)
     *
     * @var string
     */
    public $err_message      =   '';


    public function exchangeArray($data)
    {
        $this->id               =   (!empty($data['id']))               ?   $data['id']             :   null;
        $this->user_id          =   (!empty($data['user_id']))          ?   $data['user_id']        :   0;
        $this->cookie_name      =   (!empty($data['cookie_name']))      ?   $data['cookie_name']    :   '';
        $this->cookie_value     =   (!empty($data['cookie_value']))     ?   $data['cookie_value']   :   '';
        $this->mvc_namespace    =   (!empty($data['mvc_namespace']))    ?   $data['mvc_namespace']  :   '';
        $this->mvc_controller   =   (!empty($data['mvc_controller']))   ?   $data['mvc_controller'] :   '';
        $this->mvc_action       =   (!empty($data['mvc_action']))       ?   $data['mvc_action']     :   '';
        $this->script_name      =   (!empty($data['script_name']))      ?   $data['script_name']    :   '';
        $this->uri              =   (!empty($data['uri']))              ?   $data['uri']            :   '';
        $this->error_time       =   (!empty($data['error_time']))       ?   $data['error_time']     :   strtotime('now');
        $this->error_level      =   (!empty($data['error_level']))      ?   $data['error_level']    :   0;
        $this->err_message      =   (!empty($data['err_message']))      ?   $data['err_message']    :   'No error message entered.';
    }

    /**
     * Getters and Setters for Errors
     */

    public function getErrorId()
    {
        return $this->id;
    }


    public function getErrorUserId()
    {
        return $this->user_id;
    }

    public function setErrorUserId($value)
    {
        $this->user_id = $value;
    }



    public function getErrorCookieName()
    {
        return $this->cookie_name;
    }

    public function setErrorCookieName($value)
    {
        $this->cookie_name = $value;
    }



    public function getErrorCookieValue()
    {
        return $this->cookie_value;
    }

    public function setErrorCookieValue($value)
    {
        $this->cookie_value = $value;
    }



    public function getErrorIPAddress()
    {
        return long2ip((float)$this->ip_address);
    }

    public function setErrorIPAddress()
    {
        $this->ip_address = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
    }



    public function getErrorMVCNamespace()
    {
        return $this->mvc_namespace;
    }

    public function setErrorMVCNamespace($value)
    {
        $this->mvc_namespace = $value;
    }



    public function getErrorMVCController()
    {
        return $this->mvc_controller;
    }

    public function setErrorMVCController($value)
    {
        $this->mvc_controller = $value;
    }



    public function getErrorMVCAction()
    {
        return $this->mvc_action;
    }

    public function setErrorMVCAction($value)
    {
        $this->mvc_action = $value;
    }



    public function getErrorScriptName()
    {
        return $this->script_name;
    }

    public function setErrorScriptName($value)
    {
        $this->script_name = $value;
    }



    public function getErrorURI()
    {
        return $this->uri;
    }

    public function setErrorURI($value)
    {
        $this->uri = $value;
    }



    public function getErrorTime()
    {
        return $this->error_time;
    }

    public function setErrorTime()
    {
        $this->error_time = strtotime('now');
    }



    public function getErrorLevel()
    {
        return $this->error_level;
    }

    public function setErrorLevel($value)
    {
        $this->error_level = $value;
    }



    public function getErrorMessage()
    {
        return $this->err_message;
    }

    public function setErrorMessage($value)
    {
        $this->err_message = $value;
    }


    public function setDefaultErrorMessage($ErrorMessage, $UserID, $MVC_Namespace, $MVC_Controller, $MVC_Action, $ScriptName, $URI)
    {
        $this->setErrorMessage($ErrorMessage);
        $this->setErrorUserId($UserID);
        $this->setErrorMVCNamespace($MVC_Namespace);
        $this->setErrorMVCController($MVC_Controller);
        $this->setErrorMVCAction($MVC_Action);
        $this->setErrorScriptName($ScriptName);
        $this->setErrorURI($URI);
        $this->setErrorTime();
    }

}

