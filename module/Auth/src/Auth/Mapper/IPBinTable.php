<?php
/**
 * Class IPBinTable
 *
 * filename:   IPBinTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/22/14 11:39 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Auth\Model\IPBin;
 
class IPBinTable extends AbstractMapper
{
    protected $tableGateway;



    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }




    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getIPBin($id)
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

    public function saveIPBin(IPBin $IPBin)
    {
        $data           =   array
                            (
                                'user_id'       =>  $IPBin->user_id,
                                'member_id' 	=>  $IPBin->member_id,
                                'ip_address'    =>  $IPBin->ip_address,
                                'ip_status'     =>  $IPBin->ip_status,
                                'created'       =>  $IPBin->created,
                                'last_updated'  =>  $IPBin->last_updated,
                            );
        $id             =   (int) $IPBin->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            if ($this->getIPBin($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else
            {
                throw new \Exception('IPBin id does not exist');
            }
        }
    }

    public function deleteIPBin($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }


	public function getIpStatusArrayByIPAddress($IPAddress)
	{
		$IPAddress  =   (int) $IPAddress;

		$select    	=   $this->tableGateway->getSql()->select();
        $select->quantifier('DISTINCT')
        	   ->columns(array('ip_status'))
               ->where(array('ip_address' 	=>  $IPAddress))
        ;
        $resultSet 		=   $this->tableGateway->selectWith($select);
		$returnValue 	=	array();
		if($resultSet->count() > 0)
		{
			foreach($resultSet as $row)
			{
				$returnValue[] 	= 	$row->ip_status;
			}
		}

		return $returnValue;
	}


}
