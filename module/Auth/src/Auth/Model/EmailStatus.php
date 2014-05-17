<?php
/**
 * Class EmailStatus
 *
 * filename:   EmailStatus.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       3/7/14 5:40 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Model;
 

class EmailStatus
{
    public $id;

    public $email_address   		=   NULL;
    public $email_address_status    =   NULL;
    public $created     			=   NULL;


    public function exchangeArray($data)
    {
        $this->id               		=   (!empty($data['id']))           		?   $data['id']             		:   NULL;
        $this->email_address        	=   (!empty($data['email_address']))    	?   $data['email_address']      	:   NULL;
        $this->email_address_status     =   (!empty($data['email_address_status'])) ?   $data['email_address_status']   :   NULL;
        $this->created          		=   (!empty($data['created']))      		?   $data['created']        		:   NULL;
    }

    /**
     * Getters and Setters for Errors
     */



    public function getEmailStatusId()
    {
        return $this->id;
    }



    public function getEmailStatusEmailAddress()
    {
        return $this->email_address;
    }

    public function setEmailStatusEmailAddress($value)
    {
        $this->email_address = $value;
    }



    public function getEmailStatusStatus()
    {
        return $this->email_address_status;
    }

    public function setEmailStatusStatus($value)
    {
        $this->email_address_status = $value;
    }



    public function getEmailStatusCreationTime()
    {
        return $this->created;
    }

    public function setEmailStatusCreationTime()
    {
        $this->created = strtotime('now');
    }
}