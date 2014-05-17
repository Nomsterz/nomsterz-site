<?php
/**
 * Class MemberEmailsTable
 *
 * filename:   MemberEmailsTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/22/14 11:39 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Mapper;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Auth\Model\MemberEmails;
 
class MemberEmailsTable extends AbstractMapper
{
    protected $_schema  =   'nomsterz_db';
    protected $_name    =   'member_emails';
    protected $tableGateway;

    public function areExceptionsAllowed()
    {
        $domainSegments     =   explode(".", $_SERVER['SERVER_NAME']);
        return $domainSegments[0] == 'www' ? FALSE : TRUE;
    }



    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }




    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getMemberEmails($id)
    {
        $id         =   (int) $id;
        $rowset     =   $this->tableGateway->select(array('id' => $id));
        $row        =   $rowset->current();
        if (!$row)
        {
            if($this->areExceptionsAllowed())
            {
                throw new \Exception("Could not find row $id");
            }
            return FALSE;
        }
        return $row;
    }

    public function getMemberEmailsByEmail($email)
    {
        $email      =   (string) $email;
        $rowset     =   $this->tableGateway->select(array('email_address' => $email));
        $row        =   $rowset->current();
        if (!$row)
        {
            if($this->areExceptionsAllowed())
            {
                // todo: custom error
                #throw new \Exception("Could not find row by $email");
            }
            return FALSE;
        }
        return $row;
    }

    public function saveMemberEmails(MemberEmails $MemberEmails)
    {
        $data   =   array
                    (
                        'member_id'             =>  $MemberEmails->member_id,
                        'email_address'         =>  $MemberEmails->email_address,
                        'verification_sent'     =>  $MemberEmails->verification_sent,
                        'verification_sent_on'  =>  $MemberEmails->verification_sent_on,
                        'verified'              =>  $MemberEmails->verified,
                        'verified_on'           =>  $MemberEmails->verified_on,
                        'created'               =>  $MemberEmails->created,
                        'last_updated'          =>  $MemberEmails->last_updated,
                    );
        $id     =   (int) $MemberEmails->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            if ($this->getMemberEmails($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            }
            else
            {
                throw new \Exception('MemberEmails id does not exist');
            }
        }
    }

    public function deleteMemberEmails($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

    public function getVerifyEmailLink($emailAddress, $memberID, $route='')
    {
        $serviceManager     =   $this->getServiceLocator();
        $config             =   $serviceManager->get('config');
        $siteSalt           =   $config['encryptionKeys']['Nomsterz']['siteSalt'];

        $a                  =   base64_encode($this->twoWayCrypt('e',$emailAddress,$siteSalt)); // email address
        $b                  =   base64_encode($this->createHash($memberID,$siteSalt)); // one-way hashed mid
        $c                  =   base64_encode($this->twoWayCrypt('e',strtotime("now"),$siteSalt)); // vcode creation time
        $addOn              =   str_replace("/", "--::--", $a . ":ntz:". $b . ":ntz:". $c);
        $addOn              =   str_replace("+", "--:::--", $addOn);

		switch($route)
		{
			case 'verify-new-member'		:	$router	=	'email-verification';
												break;

			case 'forgot-logins-success'	:	$router	=	'change-password-verification';
												break;

			default : throw new \Exception('Invalid Email route passed (' . $route . '.');
		}
        $verifyEmailLink    =   "http://www.nomsterz.com/" . $router . "/" . $addOn;

        return $verifyEmailLink;
    }

    public function verifyEmailByLinkAndGetMemberIDArray($passedVCode, $verificationFormName='')
    {
        $serviceManager     =   $this->getServiceLocator();
        $config             =   $serviceManager->get('config');
        $siteSalt           =   $config['encryptionKeys']['Nomsterz']['siteSalt'];

        $vCode              =   str_replace("--::--", "/", $passedVCode);
        $vCode              =   str_replace("--:::--", "+", $vCode);
        $getTokens          =   explode(':ntz:', $vCode);
        $emailFromVcode     =   $this->twoWayCrypt('d',base64_decode($getTokens[0]),$siteSalt);
        $vcodeCreateTime    =   $this->twoWayCrypt('d',base64_decode($getTokens[2]),$siteSalt);
        $memberIDHash       =   base64_decode($getTokens[1]);
        $memberID           =   $this->isVerifyLinkValid($emailFromVcode, $memberIDHash);

        if(isset($memberID) && !is_bool($memberID) && $memberID > 0)
        {
			switch($verificationFormName)
			{
				case 'VerificationDetailsForm'				:	// Check if email from vcode has already been validated and verified (user that clicks the link twice+)
            													$emailIsAlreadyVerified     =   ($this->isEmailVerified($emailFromVcode) ? 1 : 0);
																break;

				case 'ChangePasswordWithVerifyLinkForm'		:	// Check ... something
            													$emailIsAlreadyVerified     =   1;
																break;


				default :	throw new \Exception('Invalid verification link form.');
			}


            return  array
                    (
                        'statusMsg'         =>  '',
                        'memberID'          =>  $memberID,
                        'email'             =>  $emailFromVcode,
                        'vcodeCreateTime'   =>  $vcodeCreateTime,
                        'alreadyVerified'   =>  (int) $emailIsAlreadyVerified,
                    );
        }
        else
        {
            // custom error
            $errorMsg   =   "Error #1 - MemberEmailsTable->isVerifyLinkValid returned an invalid member id.";
            $this->_writeLog('info', $errorMsg);
            return  array
                    (
                        'errorNbr'  =>  '1',
                        'errorMsg'  =>  $errorMsg,
                    );
        }
    }

    public function isEmailVerified($email)
    {
        $select     =   $this->tableGateway->getSql()->select();
        $select->columns(array('email_address'))
                ->where(array('email_address'       =>  $email))
                ->where(array('verified'            =>  1))
                ->where->greaterThan('verified_on',0)
        ;
        #echo $select->getSqlString() . "<br>";
        $resultSet          =   $this->tableGateway->selectWith($select);
        $resultsCount       =   $resultSet->count();
        #echo "<pre>" . print_r($resultsCount, 1) . "</pre>";

        return ($resultsCount == 1 ? TRUE : FALSE);
    }

    public function isVerifyLinkValid($emailAddress, $memberIDHash)
    {
        $select     =   $this->tableGateway->getSql()->select();
        $select->columns(array('member_id', 'id'))
                ->where(array('email_address'       =>  $emailAddress))
                #->where(array('verified'            =>  0))
                #->where(array('verified_on'         =>  0))
                ->where(array('verification_sent'   =>  1))
                ->where->lessThan('verification_sent_on', strtotime('now'))
        ;
        #echo $select->getSqlString() . "<br>";
        $resultSet              =   $this->tableGateway->selectWith($select);
        $MemberEmailsObject     =   $resultSet->current();
        $count                  =   $resultSet->count();

        if(isset($count) && $count === 1)
        {
            if(isset($MemberEmailsObject->id) && isset($MemberEmailsObject->member_id))
            {
                // Email Address is good. Now check the member id
                $serviceManager     =   $this->getServiceLocator();
                $config             =   $serviceManager->get('config');
                $siteSalt           =   $config['encryptionKeys']['Nomsterz']['siteSalt'];

                if($memberIDHash === $this->createHash($MemberEmailsObject->member_id,$siteSalt))
                {
                    return $MemberEmailsObject->member_id;
                }
                else
                {
                    // custom error
                    return FALSE;
                }
            }
            else
            {
                // custom error
                return FALSE;
            }
        }
        else
        {
            // custom error
            return FALSE;
        }
    }
}
