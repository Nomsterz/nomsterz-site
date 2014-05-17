<?php
/**
 * Class UserTable
 *
 * filename:   UserTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/6/14 5:39 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Application\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\User;
 
class UserTable extends AbstractMapper
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

    public function getUser($id)
    {
        $id  	= 	(int) $id;
        $rowset = 	$this->tableGateway->select(array('id' => $id));
        $row 	= 	$rowset->current();
        if (!$row)
        {
            if($this->areExceptionsAllowed())
            {
                $this->_writeLog('CRIT', "Could not find row $id");
            }
            return FALSE;
        }
        return $row;
    }

    public function saveUser(User $User)
    {
        $data   =   array
                    (
                        'user_type'     =>  $User->user_type,
                        'hash'          =>  $User->hash,
                        'member_id'     =>  $User->member_id,
                        'agent'         =>  $User->agent,
                        'user_status'   =>  $User->user_status,
                        'ip_address'    =>  $User->ip_address,
                        'created'       =>  $User->created,
                        'last_updated'  =>  $User->last_updated,
                    );
        $id     =   (int) $User->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            if ($this->getUser($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            }
            else
            {
                throw new \Exception('User id does not exist');
            }
        }
    }

    public function deleteUser($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
