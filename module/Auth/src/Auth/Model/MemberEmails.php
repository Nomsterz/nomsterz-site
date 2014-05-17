<?php
/**
 * Class MemberEmails
 *
 * filename:   MemberEmails.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/22/14 5:40 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Model;
 
class MemberEmails
{
    public $id;

    public $member_id               =   NULL;
    public $email_address           =   NULL;
    public $verification_sent       =   NULL;
    public $verification_sent_on    =   NULL;
    public $verified                =   NULL;
    public $verified_on             =   NULL;
    public $created                 =   NULL;
    public $last_updated            =   NULL;


    public function exchangeArray($data)
    {
        $this->id                       =   (!empty($data['id']))                   ?   $data['id']                     :   NULL;
        $this->member_id                =   (!empty($data['member_id']))            ?   $data['member_id']              :   NULL;
        $this->email_address            =   (!empty($data['email_address']))        ?   $data['email_address']          :   NULL;
        $this->verification_sent        =   (!empty($data['verification_sent']))    ?   $data['verification_sent']      :   NULL;
        $this->verification_sent_on     =   (!empty($data['verification_sent_on'])) ?   $data['verification_sent_on']   :   NULL;
        $this->verified                 =   (!empty($data['verified']))             ?   $data['verified']               :   NULL;
        $this->verified_on              =   (!empty($data['verified_on']))          ?   $data['verified_on']            :   NULL;
        $this->created                  =   (!empty($data['created']))              ?   $data['created']                :   NULL;
        $this->last_updated             =   (!empty($data['last_updated']))         ?   $data['last_updated']           :   NULL;
    }

    /**
     * Getters and Setters for Errors
     */



    public function getMemberEmailsId()
    {
        return $this->id;
    }



    public function getMemberEmailsMemberID()
    {
        return $this->member_id;
    }

    public function setMemberEmailsMemberID($value)
    {
        $this->member_id = $value;
    }



    public function getMemberEmailsEmailAddress()
    {
        return $this->email_address;
    }

    public function setMemberEmailsEmailAddress($value)
    {
        $this->email_address = $value;
    }



    public function getMemberEmailsVerificationSent()
    {
        return $this->verification_sent;
    }

    public function setMemberEmailsVerificationSent($value)
    {
        $this->verification_sent = $value;
    }



    public function getMemberEmailsVerificationSentOn()
    {
        return $this->verification_sent_on;
    }

    public function setMemberEmailsVerificationSentOn($value)
    {
        $this->verification_sent_on = $value;
    }



    public function getMemberEmailsVerified()
    {
        return $this->verified;
    }

    public function setMemberEmailsVerified($value)
    {
        $this->verified = $value;
    }



    public function getMemberEmailsVerifiedOn()
    {
        return $this->verified_on;
    }

    public function setMemberEmailsVerifiedOn($value)
    {
        $this->verified_on = $value;
    }



    public function getMemberEmailsCreationTime()
    {
        return $this->created;
    }

    public function setMemberEmailsCreationTime()
    {
        $this->created = strtotime('now');
    }



    public function getMemberEmailsLastUpdateTime()
    {
        return $this->last_updated;
    }

    public function setMemberEmailsLastUpdateTime()
    {
        $this->last_updated = strtotime('now');
    }
}