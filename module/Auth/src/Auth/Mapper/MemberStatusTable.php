<?php
/**
 * Class MemberStatusTable
 *
 * filename:   MemberStatusTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/22/14 11:39 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Auth\Model\MemberStatus;
 
class MemberStatusTable
{
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

    public function getMemberStatus($id)
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

    public function getMemberStatusByMemberID($memberID)
    {
        $memberID   =   (int) $memberID;
        $select     =   $this->tableGateway->getSql()->select();
        $select->columns(array('*'))
               ->where(array('member_id'       =>  $memberID))
               ->order('created DESC')
               ->limit(1)
            ;
        $resultSet  =   $this->tableGateway->selectWith($select);
        $row        =   $resultSet->current();
        $count      =   $resultSet->count();

        if (!$row)
        {
            if($this->areExceptionsAllowed())
            {
                throw new \Exception("Could not find row with member id as $memberID");
            }
            return FALSE;
        }
        return $row;
    }

    public function saveMemberStatus(MemberStatus $MemberStatus)
    {
        $data   =   array
                    (
                        'member_id'     =>  $MemberStatus->member_id,
                        'status'        =>  $MemberStatus->status,
                        'created'       =>  $MemberStatus->created,
                    );
        $id     =   (int) $MemberStatus->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            if ($this->getMemberStatus($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            }
            else
            {
                throw new \Exception('MemberStatus id does not exist');
            }
        }
    }

    public function deleteMemberStatus($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
