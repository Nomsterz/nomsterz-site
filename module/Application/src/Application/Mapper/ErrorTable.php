<?php
/**
 * Class ErrorTable
 *
 * filename:   ErrorTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/6/14 9:43 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Application\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Error;
 
class ErrorTable
{
    protected $tableGateway;


    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getError($id)
    {
        $id         =   (int) $id;
        $rowset     =   $this->tableGateway->select(array('id' => $id));
        $row        =   $rowset->current();
        if (!$row)
        {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveError(Error $Error)
    {
        $data   =   array
                    (
                        'user_id'           =>  $Error->user_id,
                        'cookie_name'       =>  $Error->cookie_name,
                        'cookie_value'      =>  $Error->cookie_value,
                        'mvc_namespace'     =>  $Error->mvc_namespace,
                        'mvc_controller'    =>  $Error->mvc_controller,
                        'mvc_action'        =>  $Error->mvc_action,
                        'script_name'       =>  $Error->script_name,
                        'uri'               =>  $Error->uri,
                        'error_time'        =>  $Error->error_time,
                        'error_level'       =>  $Error->error_level,
                        'err_message'       =>  $Error->err_message,
                    );
        $id     =   (int) $Error->id;

        if ($id == 0)
        {
            $rowID = $this->tableGateway->insert($data);
            return $rowID;
        }
        else
        {
            if ($this->getError($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else
            {
                throw new \Exception('User id does not exist');
            }
        }
    }

    public function deleteError($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
