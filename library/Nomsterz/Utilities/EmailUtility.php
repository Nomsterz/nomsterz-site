<?php
/**
 * Class EmailUtility
 *
 * filename:   EmailUtility.php
 * 
 * @author      Chukwuma J. Nze <chukkynze@nomsterz.com>
 * @since       1/24/14 5:36 PM
 * 
 * @copyright   Copyright (c) 2014 www.Nomsterz.com
 */ 
namespace Nomsterz\Library\Utilities;

use Zend\Session\Container;

class EmailUtility
{
    // Replaceable Variables
    private $fb_ogs             			=  "<meta property=\"og:title\"          	content=\"Welcome to Nomsterz.com\" />
                                    			<meta property=\"og:type\"            	content=\"website\" />
												<meta property=\"og:url\"             	content=\"https://www.nomsterz.com\" />
												<meta property=\"og:image\"           	content=\"\" />
												<meta property=\"og:site_name\"       	content=\"Nomsterz.com\" />
												<meta property=\"og:description\"     	content=\"Nomsterz.com offers a web based business suite designed for todays mobile notary. Without duplicating the features of other software, NotaryTools analyses, tracks and helps you visualize the overall status of your mobile notary business from discovering your average signing fee in the month of December to calculating how many signings have been taxed according to your settings. We even include monthly automatic fax invoicing! Nomsterz.com is poised to make your mobile notary business a success!\" />
												<meta property=\"og:locality\"        	content=\"Los Angeles\" />
												<meta property=\"og:region\"          	content=\"CA\" />
												<meta property=\"og:country-name\"    	content=\"USA\" />
												<meta property=\"og:email\"           	content=\"welcome@nomsterz.com\" />";
												//<meta property=\"og:phone_number\"    	content=\"+1-310-402-8018\" />";
												// [[**FB_OGS**]]

    private $pre_hdr1           			=   "Nomsterz.com - Track, Analyze, Visualize Your Notary Business";    						// [[**PRE_HDR1**]]
    private $pre_hdr2           			=   "Use this email to activate your Nomsterz.com account.";    								// [[**PRE_HDR2**]]

    private $eml_main_logo      			=   "http://www.nomsterz.com/images/site/logos/paypal_header_image.png";      	            // [[**EML_MAIN_LOGO**]]

    private $fb_link_logo       			=   "http://www.nomsterz.com/images/site/buttons/sfs_icon_facebook.png";      	            // [[**FB_LINK_LOGO**]]
    private $fb_link            			=   "http://www.facebook.com/NotaryToolsNet";                                                 		// [[**FB_LINK**]]

    private $twt_link_logo      			=   "http://www.nomsterz.com/images/site/buttons/sfs_icon_twitter.png";       	            // [[**TWT_LINK_LOGO**]]
    private $twt_link           			=   "https://www.twitter.com/notarytools";                                                 	    // [[**TWT_LINK**]]

    private $mail_addy          			=   "";             																			// [[**MAIL_ADDY**]]
    private $vrfy_email_link      			=   "http://www.nomsterz.net/email-verification/";             								// [[**VRFY_EML_LINK**]]
    private $vrfy_pwd_link      			=   "http://www.nomsterz.net/reset-password/";             									// [[**VRFY_EML_LINK**]]

	private $break_character    			=   "\\n";             																			// [[**BREAK**]]
    private $customer_service_number      	=   "(000) 000-0000";             									                            // [[**CUSTOMER_SERVICE_NUMBER**]]

	private $templateFolder					=	"email/db_templates/";

	private $mbr_type                       =   "Notary";
	private $email_subject                  =   "";

    protected $serviceManager;



    public function getEmailTemplate($emailIdentifier, $emailVariables)
    {
        switch($emailIdentifier)
        {
            case 'verify-new-member'    							:   if(!isset($emailVariables['verifyEmailLink']) || !array_key_exists('verifyEmailLink', $emailVariables))
																		{
																			throw new \Exception('missing verifyEmailLink key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'auth/email/template/verify-new-member/text';
																		$emailHTMLLink      =   'auth/email/template/verify-new-member/html';
																		$emailSubject       =   'Verify Your Email Address';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'VRFY_EML_LINK'             =>  $emailVariables['verifyEmailLink'],
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																								);
																		break;


            case 'verify-new-member-again'    						:   if(!isset($emailVariables['verifyEmailLink']) || !array_key_exists('verifyEmailLink', $emailVariables))
																		{
																			throw new \Exception('missing verifyEmailLink key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'auth/email/template/verify-new-member-again/text';
																		$emailHTMLLink      =   'auth/email/template/verify-new-member-again/html';
																		$emailSubject       =   'Verify Your Email Address';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'VRFY_EML_LINK'             =>  $emailVariables['verifyEmailLink'],
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																								);
																		break;


            case 'excessive-logins'  								:   $emailTextLink      =   'auth/email/template/excessive-logins/text';
																		$emailHTMLLink      =   'auth/email/template/excessive-logins/html';
																		$emailSubject       =   'Nomsterz.com Access Issues';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																								);
																		break;


            case 'excessive-signups'  								:   $emailTextLink      =   'auth/email/template/excessive-signups/text';
																		$emailHTMLLink      =   'auth/email/template/excessive-signups/html';
																		$emailSubject       =   'Nomsterz.com Access Issues';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																								);
																		break;


            case 'excessive-forgot-logins'  						:   $emailTextLink      =   'auth/email/template/excessive-forgot-logins/text';
																		$emailHTMLLink      =   'auth/email/template/excessive-forgot-logins/html';
																		$emailSubject       =   'Nomsterz.com Access Issues';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																								);
																		break;


            case 'excessive-change-verified-member-password' 		:   $emailTextLink      =   'auth/email/template/excessive-change-verified-member-password/text';
																		$emailHTMLLink      =   'auth/email/template/excessive-change-verified-member-password/html';
																		$emailSubject       =   'Nomsterz.com Access Issues';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																								);
																		break;


            case 'excessive-change-old-member-password' 			:   $emailTextLink      =   'auth/email/template/excessive-change-old-member-password/text';
																		$emailHTMLLink      =   'auth/email/template/excessive-change-old-member-password/html';
																		$emailSubject       =   'Nomsterz.com Access Issues';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																								);
																		break;


            case 'excessive-lost-signup-verification' 				:   $emailTextLink      =   'auth/email/template/excessive-lost-signup-verification/text';
																		$emailHTMLLink      =   'auth/email/template/excessive-lost-signup-verification/html';
																		$emailSubject       =   'Nomsterz.com Access Issues';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),
																								);
																		break;


            case 'forgot-logins-success'  							:   if(!isset($emailVariables['verifyEmailLink']) || !array_key_exists('verifyEmailLink', $emailVariables))
																		{
																			throw new \Exception('missing verifyEmailLink key for ' . $emailIdentifier . ' template');
																		}

																		if(!isset($emailVariables['first_name']) || !array_key_exists('first_name', $emailVariables))
																		{
																			throw new \Exception('missing first_name key for ' . $emailIdentifier . ' template');
																		}

																		if(!isset($emailVariables['last_name']) || !array_key_exists('last_name', $emailVariables))
																		{
																			throw new \Exception('missing last_name key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'auth/email/template/forgot-logins-success/text';
																		$emailHTMLLink      =   'auth/email/template/forgot-logins-success/html';
																		$emailSubject       =   'Nomsterz.com Access Issues';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'VRFY_EML_LINK'             =>  $emailVariables['verifyEmailLink'],
																									'CURR_YR'                   =>  date('Y'),

																									'FIRST_NAME'                =>  ucwords(strtolower($emailVariables['first_name'])),
																									'LAST_NAME'                 =>  ucwords(strtolower($emailVariables['last_name'])),
																									'FULL_NAME'                 =>  ucwords(strtolower($emailVariables['first_name'])) . " " . ucwords(strtolower($emailVariables['last_name'])),
																								);
																		break;


            case 'genericProfileInformationChange'  				:   if(!isset($emailVariables['first_name']) || !array_key_exists('first_name', $emailVariables))
																		{
																			throw new \Exception('missing first_name key for ' . $emailIdentifier . ' template');
																		}

																		if(!isset($emailVariables['last_name']) || !array_key_exists('last_name', $emailVariables))
																		{
																			throw new \Exception('missing last_name key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'auth/email/template/generic-profile-information-change/text';
																		$emailHTMLLink      =   'auth/email/template/generic-profile-information-change/html';
																		$emailSubject       =   'Nomsterz.com Profile Change Notification';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),

																									'FIRST_NAME'                =>  ucwords(strtolower($emailVariables['first_name'])),
																									'LAST_NAME'                 =>  ucwords(strtolower($emailVariables['last_name'])),
																									'FULL_NAME'                 =>  ucwords(strtolower($emailVariables['first_name'])) . " " . ucwords(strtolower($emailVariables['last_name'])),
																								);
																		break;


            case 'genericPasswordChange'  							:   if(!isset($emailVariables['first_name']) || !array_key_exists('first_name', $emailVariables))
																		{
																			throw new \Exception('missing first_name key for ' . $emailIdentifier . ' template');
																		}

																		if(!isset($emailVariables['last_name']) || !array_key_exists('last_name', $emailVariables))
																		{
																			throw new \Exception('missing last_name key for ' . $emailIdentifier . ' template');
																		}

																		$emailTextLink      =   'auth/email/template/generic-password-change/text';
																		$emailHTMLLink      =   'auth/email/template/generic-password-change/html';
																		$emailSubject       =   'Nomsterz.com Password Change Notification';
																		$templateVariables  =   array
																								(
																									'FB_OGS'                    =>  $this->fb_ogs,
																									'EML_MAIN_LOGO'             =>  $this->eml_main_logo,
																									'FB_LINK_LOGO'              =>  $this->fb_link_logo,
																									'FB_LINK'                   =>  $this->fb_link,
																									'TWT_LINK_LOGO'             =>  $this->twt_link_logo,
																									'TWT_LINK'                  =>  $this->twt_link,
																									'RESET_PWD_LINK'            =>  $this->vrfy_pwd_link,
																									'TEXT_BREAK'                =>  $this->break_character,
																									'CUSTOMER_SERVICE_NUMBER'   =>  $this->customer_service_number,
																									'CURR_YR'                   =>  date('Y'),

																									'FIRST_NAME'                =>  ucwords(strtolower($emailVariables['first_name'])),
																									'LAST_NAME'                 =>  ucwords(strtolower($emailVariables['last_name'])),
																									'FULL_NAME'                 =>  ucwords(strtolower($emailVariables['first_name'])) . " " . ucwords(strtolower($emailVariables['last_name'])),
																								);
																		break;

            default : throw new \Exception('Need an email template identifier to continue.');
        }

        return  array
                (
                    'text'              =>  $emailTextLink,
                    'html'              =>  $emailHTMLLink,
                    'subject'           =>  $emailSubject,
                    'templateVariables' =>  $templateVariables
                );
    }
}
