<?php
/**
 * Class Member
 *
 * filename:   Member.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/22/14 5:40 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Model;
 
class Member
{
    public $id;

    public $member_type         =   NULL;
    public $login_credentials   =   NULL;
    public $salt1               =   NULL;
    public $salt2               =   NULL;
    public $salt3               =   NULL;
    public $created             =   NULL;
    public $paused              =   NULL;
    public $cancelled           =   NULL;
    public $last_updated        =   NULL;


    public function exchangeArray($data)
    {
        $this->id                   =   (!empty($data['id']))                   ?   $data['id']                 :   NULL;
        $this->member_type          =   (!empty($data['member_type']))          ?   $data['member_type']        :   NULL;
        $this->login_credentials    =   (!empty($data['login_credentials']))    ?   $data['login_credentials']  :   NULL;
        $this->salt1                =   (!empty($data['salt1']))                ?   $data['salt1']              :   NULL;
        $this->salt2                =   (!empty($data['salt2']))                ?   $data['salt2']              :   NULL;
        $this->salt3                =   (!empty($data['salt3']))                ?   $data['salt3']              :   NULL;
        $this->created              =   (!empty($data['created']))              ?   $data['created']            :   NULL;
        $this->paused               =   (!empty($data['paused']))               ?   $data['paused']             :   NULL;
        $this->cancelled            =   (!empty($data['cancelled']))            ?   $data['cancelled']          :   NULL;
        $this->last_updated         =   (!empty($data['last_updated']))         ?   $data['last_updated']       :   NULL;
    }

    /**
     * Getters and Setters for Errors
     */



    public function getMemberId()
    {
        return $this->id;
    }



    public function getMemberType()
    {
        return $this->member_type;
    }

    public function setMemberType($value)
    {
        switch($value)
        {
            case 1  :   $modifiedValue  =   'notary'; break;
            case 2  :   $modifiedValue  =   'signing agent'; break;
            case 3  :   $modifiedValue  =   'lender'; break;
            case 4  :   $modifiedValue  =   'client'; break;
            case 5  :   $modifiedValue  =   'employee'; break;
            case 6  :   $modifiedValue  =   'unknown'; break;

            default : throw new \Exception('Invalid member type set.');
        }
        $this->member_type = $modifiedValue;
    }



    public function getMemberLoginCredentials()
    {
        return $this->login_credentials;
    }

    public function setMemberLoginCredentials($value)
    {
        $this->login_credentials = $value;
    }



    public function getMemberLoginSalt1()
    {
        return $this->salt1;
    }

    public function setMemberLoginSalt1($value)
    {
        $this->salt1 = $value;
    }



    public function getMemberLoginSalt2()
    {
        return $this->salt2;
    }

    public function setMemberLoginSalt2($value)
    {
        $this->salt2 = $value;
    }



    public function getMemberLoginSalt3()
    {
        return $this->salt3;
    }

    public function setMemberLoginSalt3($value)
    {
        $this->salt3 = $value;
    }



    public function getMemberCreationTime()
    {
        return $this->created;
    }

    public function setMemberCreationTime()
    {
        $this->created = strtotime('now');
    }



    public function getMemberPauseTime()
    {
        return $this->paused;
    }

    public function setMemberPauseTime($value)
    {
        $this->paused = $value;
    }



    public function getMemberCancellationTime()
    {
        return $this->cancelled;
    }

    public function setMemberCancellationTime($value)
    {
        $this->cancelled = $value;
    }



    public function getMemberLastUpdateTime()
    {
        return $this->last_updated;
    }

    public function setMemberLastUpdateTime()
    {
        $this->last_updated = strtotime('now');
    }
}