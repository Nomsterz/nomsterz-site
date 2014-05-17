<?php
/**
 * Class AccessAttemptTable
 *
 * filename:   AccessAttemptTable.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       2/13/14 5:13 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Mapper;

use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use Auth\Model\AccessAttempt;
 
class AccessAttemptTable extends AbstractMapper
{
    protected $_schema  =   'nomsterz_utils';
    protected $_name    =   'access_attempt';
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

    public function getAccessAttempt($id)
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




    public function getAccessAttemptByUserIDs($accessFormName, $userIDArray, $timeFrame)
    {
        $total      =   FALSE;
        $success    =   FALSE;
        $failures   =   FALSE;

		$Now		=	strtotime('now');

        switch($timeFrame)
        {
            case 'Last1Hour'                :   $startTime  =   $Now - (60 * 60);
                                                $endTime    =   $Now;
                                                break;

            case 'Last12Hours'              :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'Last24Hours'              :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'Today'                    :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'Last7Days'                :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisWeekStartingMonday'   :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisWeekStartingSunday'   :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisMonth'                :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisQuarter'              :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;

            case 'ThisYear'                 :   $startTime  =   0;
                                                $endTime    =   0;
                                                break;


            default : throw new \Exception('An invalid time-frame was specified.');
        }

		$selectTotal     	=   $this->tableGateway->getSql()->select();
        $selectTotal->columns(array('id'))
                	->where(array('attempt_type' 	=>  $accessFormName))
                	->where->In('user_id', $userIDArray)
						   ->greaterThanOrEqualTo('attempted_at', $startTime)
                       	   ->lessThanOrEqualTo('attempted_at', $endTime)
        ;
        $resultSetTotal  	=   $this->tableGateway->selectWith($selectTotal);
        $countTotal      	=   (int) $resultSetTotal->count();


		$selectSuccess   	=   $this->tableGateway->getSql()->select();
        $selectSuccess->columns(array('id'))
                	  ->where(array('attempt_type' 	=>  $accessFormName))
                	  ->where(array('success' 	=>  1))
                	  ->where->In('user_id', $userIDArray)
						     ->greaterThanOrEqualTo('attempted_at', $startTime)
                       		 ->lessThanOrEqualTo('attempted_at', $endTime)
        ;
        $resultSetSuccess  	=   $this->tableGateway->selectWith($selectSuccess);
        $countSuccess      	=   (int) $resultSetSuccess->count();


		$selectFailures   	=   $this->tableGateway->getSql()->select();
        $selectFailures->columns(array('id'))
                	   ->where(array('attempt_type' 	=>  $accessFormName))
                	   ->where(array('success' 	=>  0))
                	   ->where->In('user_id', $userIDArray)
						      ->greaterThanOrEqualTo('attempted_at', $startTime)
                       		  ->lessThanOrEqualTo('attempted_at', $endTime)
        ;
        $countFailures		=   (int) $this->tableGateway->selectWith($selectFailures)->count();
		#echo $selectTotal->getSqlString() . "<br>";
		#echo "countFailures<pre>" . print_r($countFailures, 1) . "</pre>";

        return  array
                (
                    'status'    =>  (isset($countTotal)    && is_int($countTotal)    && $countTotal    >= 0) &&
                     				(isset($countSuccess)  && is_int($countSuccess)  && $countSuccess  >= 0) &&
                   					(isset($countFailures) && is_int($countFailures) && $countFailures >= 0)
										?	TRUE
										:	FALSE,

                    'total'     =>  (isset($countTotal)    && is_int($countTotal)    && $countTotal    >= 0
										?	$countTotal
										:	$total),

                    'success'   =>  (isset($countSuccess)  && is_int($countSuccess)  && $countSuccess  >= 0
										?	$countSuccess
										:	$success),

                    'failures'  =>  (isset($countFailures) && is_int($countFailures) && $countFailures >= 0
										?	$countFailures
										:	$failures),
                );
    }

    public function saveAccessAttempt(AccessAttempt $AccessAttempt)
    {
        $data   =   array
                    (
                        'user_id'       =>  $AccessAttempt->user_id,
                        'attempt_type'  =>  $AccessAttempt->attempt_type,
                        'success'       =>  $AccessAttempt->success,
                        'attempted_at'  =>  $AccessAttempt->attempted_at,
                    );

        $id     =   (int) $AccessAttempt->id;

        if ($id == 0)
        {
            $this->tableGateway->insert($data);
            return $this->tableGateway->lastInsertValue;
        }
        else
        {
            throw new \Exception('Failed insert attempt on AccessAttempt with id = ' . $id . ' . Check if ID already exists.');
        }
    }
}
