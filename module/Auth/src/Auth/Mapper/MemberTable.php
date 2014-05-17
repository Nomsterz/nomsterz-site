<?php
/**
 * Class MemberTable
 *
 * filename:   MemberTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/22/14 11:39 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Auth\Model\Member;
 
class MemberTable extends AbstractMapper
{
    protected $Config;
    protected $tableGateway;

    /**
     * @param $username
     * @param $password
     * @return string
     */
    public function generateLoginCredentials($username, $password)
    {
        $serviceManager     =   $this->getServiceLocator();
        $config             =   $serviceManager->get('config');
        $siteSalt           =   $config['encryptionKeys']['Nomsterz']['siteSalt'];

        $memberSalt1        =   uniqid(mt_rand(0, 61), true);
        $memberSalt2        =   uniqid(mt_rand(0, 61), true);
        $memberSalt3        =   uniqid(mt_rand(0, 61), true);

        $loginCredentials   =   $this->createHash($memberSalt1 . $username . $siteSalt . $password . $memberSalt2, $siteSalt . $memberSalt3);
        return  array
                (
                    $loginCredentials,
                    $memberSalt1,
                    $memberSalt2,
                    $memberSalt3,
                );
    }

    public function generateMemberLoginCredentials($username, $password, $memberSalt1, $memberSalt2, $memberSalt3)
    {
        $serviceManager     =   $this->getServiceLocator();
        $config             =   $serviceManager->get('config');
        $siteSalt           =   $config['encryptionKeys']['Nomsterz']['siteSalt'];
        $loginCredentials   =   $this->createHash($memberSalt1 . $username . $siteSalt . $password . $memberSalt2, $siteSalt . $memberSalt3);

        return $loginCredentials;
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

    public function getMember($id)
    {
        $id         =   (int) $id;
        $rowset     =   $this->tableGateway->select(array('id' => $id));
        $row        =   $rowset->current();
        if (!$row)
        {
            $this->_writeLog('crit','Could not find row ' . $id . '.');
            return FALSE;
        }
        return $row;
    }

    public function saveMember(Member $Member)
    {
        $data           =   array
                            (
                                'member_type'       =>  $Member->member_type,
                                'login_credentials' =>  $Member->login_credentials,
                                'salt1'             =>  $Member->salt1,
                                'salt2'             =>  $Member->salt2,
                                'salt3'             =>  $Member->salt3,
                                'created'           =>  $Member->created,
                                'paused'            =>  $Member->paused,
                                'cancelled'         =>  $Member->cancelled,
                                'last_updated'      =>  $Member->last_updated,
                            );
        $id             =   (int) $Member->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            if ($this->getMember($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
				return $id;
            }
            else
            {
                throw new \Exception('Member id does not exist');
            }
        }
    }

    public function deleteMember($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }


}
