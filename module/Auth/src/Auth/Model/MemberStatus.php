<?php
/**
 * Class MemberStatus
 *
 * filename:   MemberStatus.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/6/14 5:40 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Model;
 

class MemberStatus
{
    public $id;

    public $member_id   =   NULL;
    public $status      =   NULL;
    public $created     =   NULL;


    public function exchangeArray($data)
    {
        $this->id               =   (!empty($data['id']))           ?   $data['id']             :   NULL;
        $this->member_id        =   (!empty($data['member_id']))    ?   $data['member_id']      :   NULL;
        $this->status           =   (!empty($data['status']))       ?   $data['status']         :   NULL;
        $this->created          =   (!empty($data['created']))      ?   $data['created']        :   NULL;
    }

    /**
     * Getters and Setters for Errors
     */



    public function getMemberStatusId()
    {
        return $this->id;
    }



    public function getMemberStatusMemberID()
    {
        return $this->member_id;
    }

    public function setMemberStatusMemberID($value)
    {
        $this->member_id = $value;
    }



    public function getMemberStatusStatus()
    {
        return $this->status;
    }

    public function setMemberStatusStatus($value)
    {
        $this->status = $value;
    }



    public function getMemberStatusCreationTime()
    {
        return $this->created;
    }

    public function setMemberStatusCreationTime()
    {
        $this->created = strtotime('now');
    }
}