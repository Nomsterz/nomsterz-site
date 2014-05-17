<?php
/**
 * Class AccessAttempt
 *
 * filename:   AccessAttempt.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       2/13/14 5:22 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Model;
 
class AccessAttempt
{
    public $id;

    public $user_id         =   NULL;
    public $attempt_type    =   NULL;
    public $success         =   NULL;
    public $attempted_at    =   NULL;


    public function exchangeArray($data)
    {
        $this->id               =   (!empty($data['id']))               ?   $data['id']             :   NULL;
        $this->user_id          =   (!empty($data['user_id']))          ?   $data['user_id']        :   NULL;
        $this->attempt_type     =   (!empty($data['attempt_type']))     ?   $data['attempt_type']   :   NULL;
        $this->success          =   (!empty($data['success']))          ?   $data['success']        :   NULL;
        $this->attempted_at     =   (!empty($data['attempted_at']))     ?   $data['attempted_at']   :   NULL;
    }

    /**
     * Getters and Setters for Errors
     */



    public function getAccessAttemptId()
    {
        return $this->id;
    }



    public function getAccessAttemptUserID()
    {
        return $this->user_id;
    }

    public function setAccessAttemptUserID($value)
    {
        $this->user_id = $value;
    }



    public function getAccessAttemptType()
    {
        return $this->attempt_type;
    }

    public function setAccessAttemptType($value)
    {
        $this->attempt_type = $value;
    }



    public function getAccessAttemptSuccess()
    {
        return $this->success;
    }

    public function setAccessAttemptSuccess($value)
    {
        $this->success = $value;
    }



    public function getAccessAttemptTime()
    {
        return $this->attempted_at;
    }

    public function setAccessAttemptTime()
    {
        $this->attempted_at = strtotime('now');;
    }
}
