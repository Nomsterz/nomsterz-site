<?php
/**
 * Class PagehitTable
 *
 * filename:   PagehitTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/6/14 5:39 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Application\Mapper;

use Zend\Db\TableGateway\TableGateway;
use Application\Model\Pagehit;
 
class PagehitTable
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

    public function getPagehit($id)
    {
        $id     =   (int) $id;
        $rowset =   $this->tableGateway->select(array('id' => $id));
        $row    =   $rowset->current();
        if (!$row)
        {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePagehit(Pagehit $Pagehit)
    {
        $data   =   array
                    (
                        'user_id'               =>  $Pagehit->user_id,
                        'cookies'               =>  $Pagehit->cookies,
                        'url_location'          =>  $Pagehit->url_location,
                        'client_time'           =>  $Pagehit->client_time,
                        'server_time'           =>  $Pagehit->server_time,
                        'screen_size'           =>  $Pagehit->screen_size,
                        'avail_screen_size'     =>  $Pagehit->avail_screen_size,
                        'kvpid'                 =>  $Pagehit->kvpid,
                    );
        $id     =   (int) $Pagehit->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            if ($this->getPagehit($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
            }
            else
            {
                throw new \Exception('Pagehit id does not exist');
            }
        }
    }

    public function deletePagehit($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
