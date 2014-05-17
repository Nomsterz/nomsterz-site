<?php
/**
 * Class EmailStatusTable
 *
 * filename:   EmailStatusTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       3/7/14 5:40 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Auth\Model\EmailStatus;
 
class EmailStatusTable
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

    public function getEmailStatus($id)
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

    public function getEmailStatusByEmail($EmailAddress)
    {
        $EmailID   	=   (string) $EmailAddress;
        $select  	=   $this->tableGateway->getSql()->select();
        $select->columns(array('*'))
               ->where(array('email_address'       =>  $EmailID))
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
                throw new \Exception("Could not find row with Email as $EmailID");
            }
            return FALSE;
        }
        return $row;
    }

    public function saveEmailStatus(EmailStatus $EmailStatus)
    {
        $data   =   array
                    (
                        'email_address'     	=>	$EmailStatus->email_address,
                        'email_address_status'  =>  $EmailStatus->email_address_status,
                        'created'       		=>  $EmailStatus->created,
                    );
        $id     =   (int) $EmailStatus->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            if ($this->getEmailStatus($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            }
            else
            {
                throw new \Exception('EmailStatus id does not exist');
            }
        }
    }

    public function deleteEmailStatus($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
