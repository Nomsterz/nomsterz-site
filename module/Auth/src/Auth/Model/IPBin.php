<?php
/**
 * Class IPBin
 *
 * filename:   IPBin.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       2/21/14 4:47 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Model;
 
class IPBin
{
    public $id;

    public $user_id         =   NULL;
    public $member_id   	=   NULL;
    public $ip_address      =   NULL;
    public $ip_status       =   NULL;
    public $created         =   NULL;
    public $last_updated 	=   NULL;


    public function exchangeArray($data)
    {
        $this->id             	=   (!empty($data['id']))               ?   $data['id']             :   NULL;
        $this->user_id          =   (!empty($data['user_id']))          ?   $data['user_id']        :   NULL;
        $this->member_id    	=   (!empty($data['member_id']))    	?   $data['member_id']  	:   NULL;
        $this->ip_address    	=   (!empty($data['ip_address']))       ?   $data['ip_address']     :   NULL;
        $this->ip_status        =   (!empty($data['ip_status']))        ?   $data['ip_status']      :   NULL;
        $this->created     		=   (!empty($data['created']))          ?   $data['created']        :   NULL;
        $this->last_updated   	=   (!empty($data['last_updated']))     ?   $data['last_updated']   :   NULL;
    }

    /**
     * Getters and Setters for Errors
     */



    public function getIPBinId()
    {
        return $this->id;
    }



    public function getIPBinUserID()
    {
        return $this->user_id;
    }

    public function setIPBinUserID($value)
    {
        $this->user_id = $value;
    }



    public function getIPBinMemberID()
    {
        return $this->member_id;
    }

    public function setIPBinMemberID($value)
    {
        $this->member_id = $value;
    }



    public function getIPBinIPAddress()
    {
        return long2ip((float)$this->ip_address);
    }

    public function setIPBinIPAddress()
    {
        $this->ip_address = sprintf('%u', ip2long($_SERVER['REMOTE_ADDR']));
    }



    public function getIPBinIPAddressStatus()
    {
        return $this->ip_status;
    }

    public function setIPBinIPAddressStatus($value)
    {
        $this->ip_status = $value;
    }



    public function getIPBinCreationTime()
    {
        return $this->created;
    }

    public function setIPBinCreationTime()
    {
        $this->created = strtotime('now');
    }



    public function getIPBinLastUpdateTime()
    {
        return $this->last_updated;
    }

    public function setIPBinLastUpdateTime()
    {
        $this->last_updated = strtotime('now');
    }
}