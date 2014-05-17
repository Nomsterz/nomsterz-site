<?php
/**
 * Class MemberDetailsTable
 *
 * filename:   MemberDetailsTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/30/14 7:39 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Mapper;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Auth\Model\MemberDetails;
 
class MemberDetailsTable extends AbstractMapper
{
    protected $_schema  =   'nomsterz_db';
    protected $_name    =   'member_details';
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

    public function getMemberDetails($id)
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

    public function getMemberDetailsByMemberID($memberID)
    {
        $memberID   =   (int) $memberID;
        $rowset     =   $this->tableGateway->select(array('member_id' => $memberID));
        $row        =   $rowset->current();
        if (!$row)
        {
            return FALSE;
        }
        return $row;
    }

    public function saveMemberDetails(MemberDetails $MemberDetails)
    {
        $data   =   array
                    (
                        'member_id'         	=>  $MemberDetails->member_id,
                        'prefix'            	=>  $MemberDetails->prefix,
                        'first_name'        	=>  $MemberDetails->first_name,
                        'mid_name1'         	=>  $MemberDetails->mid_name1,
                        'mid_name2'         	=>  $MemberDetails->mid_name2,
                        'last_name'         	=>  $MemberDetails->last_name,
                        'display_name'         	=>  $MemberDetails->display_name,
                        'suffix'            	=>  $MemberDetails->suffix,
                        'gender'            	=>  $MemberDetails->gender,
                        'birth_date'        	=>  $MemberDetails->birth_date,
                        'zipcode'           	=>  $MemberDetails->zipcode,
						'personal_summary'      =>  $MemberDetails->personal_summary,
						'profile_pic_url'       =>  $MemberDetails->profile_pic_url,
						'personal_website_url'  =>  $MemberDetails->personal_website_url,
						'linkedin_url'          =>  $MemberDetails->linkedin_url,
						'google_plus_url'      	=>  $MemberDetails->google_plus_url,
						'twitter_url'           =>  $MemberDetails->twitter_url,
						'facebook_url'          =>  $MemberDetails->facebook_url,
                        'created'           	=>  $MemberDetails->created,
                        'last_updated'      	=>  $MemberDetails->last_updated,
                    );

        $id     =   (int) $MemberDetails->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            if ($this->getMemberDetails($id))
            {
                $this->tableGateway->update($data, array('id' => $id));
                return $id;
            }
            else
            {
                throw new \Exception('MemberDetails id does not exist');
            }
        }
    }

    public function deleteMemberDetails($id)
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}
