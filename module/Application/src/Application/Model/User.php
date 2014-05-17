<?php
/**
 * Class User
 *
 * filename:   User.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/6/14 5:40 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Application\Model;
 

class User
{
    public $id;

    public $user_type 		= '';
    public $hash 			= '';
    public $member_id 		= 0;
    public $agent 			= '';
    public $ip_address 		= 0;
	public $user_status 	= 'Open';
    public $created 		= 0;
    public $last_updated 	= 0;


    public function exchangeArray($data)
    {
        $this->id               =   (!empty($data['id']))           ?   $data['id']             :   null;
        $this->user_type        =   (!empty($data['user_type']))    ?   $data['user_type']      :   '';
        $this->hash             =   (!empty($data['hash']))         ?   $data['hash']           :   '';
        $this->member_id        =   (!empty($data['member_id']))    ?   $data['member_id']      :   0;
        $this->agent            =   (!empty($data['agent']))        ?   $data['agent']          :   '';
        $this->ip_address       =   (!empty($data['ip_address']))   ?   $data['ip_address']     :   0;
        $this->user_status      =   (!empty($data['user_status']))  ?   $data['user_status']    :   0;
        $this->created          =   (!empty($data['created']))      ?   $data['created']        :   0;
        $this->last_updated     =   (!empty($data['last_updated'])) ?   $data['last_updated']   :   0;
    }

    /**
     * Getters and Setters for Errors
     */



    public function getUserId()
    {
        return $this->id;
    }



    public function getUserType()
    {
        return $this->user_type;
    }

    public function setUserType($value)
    {
        $this->user_type = $value;
    }



    public function getUserHash()
    {
        return $this->hash;
    }

    public function setUserHash($value)
    {
        $currTime       =   strtotime('now');
        $currentMaxID   =   $value;
        $randomString   =   '';
        $hash           =   0;
        $this->hash = $hash;
    }



    public function getUserMemberID()
    {
        return $this->member_id;
    }

    public function setUserMemberID($value)
    {
        $this->member_id = $value;
    }



    public function getUserBrowserAgent()
    {
        return $this->agent;
    }

    public function setUserBrowserAgent()
    {
        $this->agent = $_SERVER['HTTP_USER_AGENT'];
    }



    public function getUserIPAddress()
    {
        return long2ip((float)$this->ip_address);
    }

    public function setUserIPAddress()
    {
        $this->ip_address = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
    }



    public function getUserStatus()
    {
        return $this->user_status;
    }

    public function setUserStatus($value)
    {
        $this->user_status = $value;
    }



    public function getUserCreationTime()
    {
        return $this->created;
    }

    public function setUserCreationTime()
    {
        $this->created = strtotime('now');
    }



    public function getUserLastUpdateTime()
    {
        return $this->last_updated;
    }

    public function setUserLastUpdateTime()
    {
        $this->last_updated = strtotime('now');
    }
}