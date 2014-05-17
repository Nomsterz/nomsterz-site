<?php
/**
 * Class Pagehit
 *
 * filename:   Pagehit.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/6/14 11:37 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Application\Model;
 
/**
 * @ORM\Entity
 * @ORM\Table
 * (
 *      name="pagehit",
 *      indexes=
 *      {
 *          @ORM\Index( name="ndx1", columns={"user_id"}),
 *          @ORM\Index( name="ndx2", columns={"cookie_tracker_id"}),
 *          @ORM\Index( name="ndx4", columns={"client_time"}),
 *          @ORM\Index( name="ndx5", columns={"server_time"}),
 *          @ORM\Index( name="ndx6", columns={"kvp"}),
 *
 *          @ORM\Index( name="ndx1_5", columns={"user_id","server_time"}),
 *          @ORM\Index( name="ndx6_5", columns={"kvp","server_time"}),
 *      }
 * )
 *
 */
class Pagehit
{
    const POLICY_CookiePrefix   =   'nomsterz_';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $user_id = 0;

    /**
     *
     * @ORM\Column(type="string", length=16)
     */
    public $cookies = '';

    /**
     * @ORM\Column(type="string")
     */
    public $url_location             =   '';

    /**
     * The time from the client. The difference between this and server time is an accurate measure of location
     *
     * @ORM\Column(type="integer", length=24)
     */
    public $client_time              =   0;

    /**
     * @ORM\Column(type="integer", length=24)
     */
    public $server_time              =   0;

    /**
     * @ORM\Column(type="string", length=11)
     */
    public $screen_size              =   '';

    /**
     * @ORM\Column(type="string", length=11)
     */
    public $avail_screen_size        =   '';

    /**
     * @ORM\Column(type="string", length=500)
     */
    public $kvpid                      =   0;


    public function exchangeArray($data)
    {
        $this->id                   =   (!empty($data['id']))                   ?   $data['id']                 :   null;
        $this->user_id              =   (!empty($data['user_id']))              ?   $data['user_id']            :   0;
        $this->cookies              =   (!empty($data['cookies']))              ?   $data['cookies']            :   '';
        $this->url_location         =   (!empty($data['url_location']))         ?   $data['url_location']       :   '';
        $this->client_time          =   (!empty($data['client_time']))          ?   $data['client_time']        :   0;
        $this->server_time          =   (!empty($data['server_time']))          ?   $data['server_time']        :   0;
        $this->screen_size          =   (!empty($data['screen_size']))          ?   $data['screen_size']        :   '';
        $this->avail_screen_size    =   (!empty($data['avail_screen_size']))    ?   $data['avail_screen_size']  :   '';
        $this->kvpid                =   (!empty($data['kvpid']))                ?   $data['kvpid']              :   0;
    }

    /**
     * Getters and Setters for PageHit
     */

    public function getPageHitId()
    {
        return $this->id;
    }


    public function getPageHitUserId()
    {
        return $this->user_id;
    }

    public function setPageHitUserId($value)
    {
        $this->user_id = $value;
    }




    public function getPageHitCookies()
    {
        return $this->cookies;
    }

    public function setPageHitCookies()
    {
        $akadaCookies   =   array();
        foreach($_COOKIE as $cKey => $cValue)
        {
            if(strpos($cKey,self::POLICY_CookiePrefix, 0) >= 0)
            {
                $akadaCookies[$cKey]    =   $cValue;
            }
        }
        $this->cookies = json_encode($akadaCookies);
    }



    public function getPageHitURLLocation()
    {
        return $this->url_location;
    }

    public function setPageHitURLLocation($value)
    {
        $this->url_location = $value;
    }



    public function getPageHitClientTime()
    {
        return $this->client_time;
    }

    public function setPageHitClientTime($value)
    {
        $this->client_time = $value;
    }




    public function getPageHitServerTime()
    {
        return $this->server_time;
    }

    public function setPageHitServerTime()
    {
        $this->server_time = strtotime('now');
    }



    public function getPageHitScreenSize()
    {
        return $this->screen_size;
    }

    public function setPageHitScreenSize($value)
    {
        $this->screen_size = $value;
    }



    public function getPageHitAvailScreenSize()
    {
        return $this->avail_screen_size;
    }

    public function setPageHitAvailScreenSize($value)
    {
        $this->avail_screen_size = $value;
    }



    public function getPageHitKvp()
    {
        return $this->kvpid;
    }

    public function setPageHitKvp($value)
    {
        $this->kvpid = $value;
    }
    
}