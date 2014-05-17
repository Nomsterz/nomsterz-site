<?php
/**
 * Class MemberDetails
 *
 * filename:   MemberDetails.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/6/14 5:40 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Auth\Model;
 

class MemberDetails
{
    public $id;

    public $member_id           =   NULL;

    public $prefix              =   '';
    public $first_name          =   NULL;
    public $mid_name1           =   '';
    public $mid_name2           =   '';
    public $last_name           =   NULL;
    public $display_name        =   '';
    public $suffix              =   '';

    public $gender              =   0;
    public $birth_date          =   '0000-00-00';
    public $zipcode             =   '00000';

    public $personal_summary		=	'';
    public $profile_pic_url			=	'';
    public $personal_website_url	=	'';
    public $linkedin_url			=	'';
    public $google_plus_url			=	'';
    public $twitter_url				=	'';
    public $facebook_url			=	'';


    public $created             =   NULL;
    public $last_updated        =   NULL;


    public function exchangeArray($data)
    {
        $this->id               		=   (!empty($data['id']))           			?   $data['id']             		:   NULL;
        $this->member_id        		=   (!empty($data['member_id ']))   			?   $data['member_id ']     		:   NULL;
        $this->prefix           		=   (!empty($data['prefix']))       			?   $data['prefix']         		:   '';
        $this->first_name       		=   (!empty($data['first_name']))   			?   $data['first_name']     		:   NULL;
        $this->mid_name1        		=   (!empty($data['mid_name1']))    			?   $data['mid_name1']      		:   '';
        $this->mid_name2        		=   (!empty($data['mid_name2']))    			?   $data['mid_name2']      		:   '';
        $this->last_name        		=   (!empty($data['last_name']))    			?   $data['last_name']      		:   NULL;
        $this->display_name        		=   (!empty($data['display_name']))    			?   $data['display_name']      		:   NULL;
        $this->suffix           		=   (!empty($data['suffix']))       			?   $data['suffix']         		:   '';
        $this->gender           		=   (!empty($data['gender']))       			?   $data['gender']         		:   0;
        $this->birth_date       		=   (!empty($data['birth_date']))   			?   $data['birth_date']     		:   '0000-00-00';
        $this->zipcode          		=   (!empty($data['zipcode']))      			?   $data['zipcode']        		:   '00000';
		$this->personal_summary         =   (!empty($data['personal_summary']))      	?   $data['personal_summary']       :   '';
		$this->profile_pic_url          =   (!empty($data['profile_pic_url']))      	?   $data['profile_pic_url']        :   '';
		$this->personal_website_url     =   (!empty($data['personal_website_url']))		?   $data['personal_website_url']   :   '';
		$this->linkedin_url          	=   (!empty($data['linkedin_url']))      		?   $data['linkedin_url']        	:   '';
		$this->google_plus_url          =   (!empty($data['google_plus_url']))      	?   $data['google_plus_url']        :   '';
		$this->twitter_url          	=   (!empty($data['twitter_url']))      		?   $data['twitter_url']        	:   '';
		$this->facebook_url          	=   (!empty($data['facebook_url']))      		?   $data['facebook_url']        	:   '';
        $this->created          		=   (!empty($data['created']))      			?   $data['created']        		:   0;
        $this->last_updated     		=   (!empty($data['last_updated'])) 			?   $data['last_updated']   		:   0;
    }

    /**
     * Getters and Setters for Errors
     */



    public function getMemberDetailsId()
    {
        return $this->id;
    }



    public function getMemberDetailsMemberID()
    {
        return $this->member_id;
    }

    public function setMemberDetailsMemberID($value)
    {
        $this->member_id = $value;
    }



    public function getMemberDetailsPrefix()
    {
        return $this->prefix;
    }

    public function setMemberDetailsPrefix($value)
    {
        $this->prefix = $value;
    }



    public function getMemberDetailsFirstName()
    {
        return (isset($this->first_name) ? $this->first_name : "Valued");
    }

    public function setMemberDetailsFirstName($value)
    {
        $this->first_name = $value;
    }



    public function getMemberDetailsMidName1()
    {
        return $this->mid_name1;
    }

    public function setMemberDetailsMidName1($value)
    {
        $this->mid_name1 = $value;
    }



    public function getMemberDetailsMidName2()
    {
        return $this->mid_name2;
    }

    public function setMemberDetailsMidName2($value)
    {
        $this->mid_name2 = $value;
    }



    public function getMemberDetailsLastName()
    {
        return  (isset($this->last_name) ? $this->last_name : "Member");
    }

    public function setMemberDetailsLastName($value)
    {
        $this->last_name = $value;
    }



    public function getMemberDetailsDisplayName()
    {
        return 	(isset($this->display_name) && $this->display_name != ''
					? 	$this->display_name
					:	$this->first_name);
    }

    public function setMemberDetailsDisplayName($value)
    {
        $this->display_name = $value;
    }


    public function getMemberDetailsFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }



    public function getMemberDetailsSuffix()
    {
        return $this->suffix;
    }

    public function setMemberDetailsSuffix($value)
    {
        $this->suffix = $value;
    }



    public function getMemberDetailsGender($format)
    {
		$outputValues	=	array
							(
								0 => 'Other',
								1 => 'Female',
								2 => 'Male',
							);
		switch(trim(strtolower($format)))
		{
			case 'text'	:	$output = $outputValues[$this->gender]; break;
			case 'abbr'	:	$output = $outputValues[$this->gender][0]; break;
			case 'raw'	:	$output = $this->gender; break;
			default		:	$output = $outputValues[$this->gender];
		}

		return $output;
    }

    public function setMemberDetailsGender($value)
    {
        $value  = (int) $value;

        if($value === 1 || $value === 2)
        {
            $modifiedValue  =   $value;
        }
        else
        {
            $modifiedValue  =   0;
        }
        $this->gender = $modifiedValue;
    }



    public function getMemberDetailsBirthDate()
    {
        return $this->birth_date;
    }

    public function setMemberDetailsBirthDate($value)
    {
        $this->birth_date = $value;
    }



    public function getMemberDetailsZipCode()
    {
        return $this->zipcode;
    }

    public function setMemberDetailsZipCode($value)
    {
        $this->zipcode = $value;
    }



    public function getMemberDetailsPersonalSummary()
    {
        return $this->personal_summary;
    }

    public function setMemberDetailsPersonalSummary($value)
    {
        $this->personal_summary = $value;
    }



    public function getMemberDetailsProfilePicUrl()
    {
        return $this->profile_pic_url;
    }

    public function setMemberDetailsProfilePicUrl($value)
    {
        $this->profile_pic_url = $value;
    }



    public function getMemberDetailsPersonalSiteUrl()
    {
        return $this->personal_website_url;
    }

    public function setMemberDetailsPersonalSiteUrl($value)
    {
        $this->personal_website_url = $value;
    }



    public function getMemberDetailsLinkedInUrl()
    {
        return $this->linkedin_url;
    }

    public function setMemberDetailsLinkedInUrl($value)
    {
        $this->linkedin_url = $value;
    }



    public function getMemberDetailsGooglePlusUrl()
    {
        return $this->google_plus_url;
    }

    public function setMemberDetailsGooglePlusUrl($value)
    {
        $this->google_plus_url = $value;
    }



    public function getMemberDetailsTwitterUrl()
    {
        return $this->twitter_url;
    }

    public function setMemberDetailsTwitterUrl($value)
    {
        $this->twitter_url = $value;
    }



    public function getMemberDetailsFacebookUrl()
    {
        return $this->facebook_url;
    }

    public function setMemberDetailsFacebookUrl($value)
    {
        $this->facebook_url = $value;
    }












    public function getMemberDetailsCreationTime()
    {
        return $this->created;
    }

    public function setMemberDetailsCreationTime()
    {
        $this->created = strtotime('now');
    }



    public function getMemberDetailsLastUpdateTime()
    {
        return $this->last_updated;
    }

    public function setMemberDetailsLastUpdateTime()
    {
        $this->last_updated = strtotime('now');
    }
}