-- Author                   :   Chukky Nze
-- Company                  :   Nomsterz.com
-- Email                    :   chukkynze@nomsterz.com
-- Date                     :   1/24/14 11:34 PM
-- Description              :   EnterDescriptionHere
-- Database                 :   EnterDatabaseNamesHere
-- Table                    :   EnterTableNamesHere
-- Undo Script provided     :   reset-sql-work.undo

START TRANSACTION;

USE nomsterz_db;

DROP TABLE IF EXISTS nomsterz_db.user;

CREATE TABLE nomsterz_db.user
(
    id 			    int(16) unsigned NOT NULL AUTO_INCREMENT,
    hash 		    varchar(160) NOT NULL DEFAULT '',
    user_type 		varchar(24) NOT NULL DEFAULT '',
    member_id  		int(16) unsigned NOT NULL DEFAULT 0,
    agent 			varchar(240) NOT NULL DEFAULT '',
    ip_address 		int(11) unsigned NOT NULL DEFAULT 0,
	user_status     enum
	                (
		                'Open',
		                'Locked:Excessive-Login-Attempts',
		                'Locked:Excessive-Signup-Attempts',
		                'Locked:Excessive-ForgotLogin-Attempts',
			            'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts',
			            'Locked:Excessive-ChangeOldPassword-Attempts',
			            'Locked:Excessive-LostSignupVerification-Attempts'
	                ) NOT NULL DEFAULT 'Open',
    created 		int(11) unsigned NOT NULL DEFAULT 0,
    last_updated 	int(11) unsigned NOT NULL DEFAULT 0,

    PRIMARY KEY (id),

	KEY ndx1 (hash),
    KEY ndx3 (user_type),
    KEY ndx4 (member_id),
    KEY ndx5 (ip_address),

    KEY ndx4_5 (id,member_id),
    KEY ndx5_3 (id,agent),
    KEY ndx5_6 (id,created),
    KEY ndx5_8 (ip_address,agent)
)
ENGINE=InnoDB
AUTO_INCREMENT=1
DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci
;


DROP TABLE IF EXISTS nomsterz_db.member_details;

CREATE TABLE IF NOT EXISTS nomsterz_db.member_details
(
    id     					INT(16) PRIMARY KEY NOT NULL AUTO_INCREMENT ,
    member_id               INT(16) NOT NULL,
    prefix           		varchar (60) DEFAULT '' NOT NULL,
    first_name              varchar (60) NOT NULL,
    mid_name1               varchar (60) DEFAULT '' NOT NULL,
    mid_name2               varchar (60) DEFAULT '' NOT NULL,
    last_name               varchar (60) NOT NULL,
    display_name            varchar (60) DEFAULT '' NOT NULL,
    suffix          		varchar (60) DEFAULT '' NOT NULL,
    gender                  int(1) NOT NULL default 0 COMMENT 'Female => 1, Male => 2, Others => empty|0|, Anything Else => suspicious',
    birth_date              date NOT NULL default '0000-00-00',
    zipcode           	    varchar(8) NOT NULL default '00000',

	personal_summary		varchar (256) DEFAULT '' NOT NULL,
    profile_pic_url			varchar (256) DEFAULT '' NOT NULL,
    personal_website_url	varchar (256) DEFAULT '' NOT NULL,
    linkedin_url			varchar (256) DEFAULT '' NOT NULL,
    google_plus_url			varchar (256) DEFAULT '' NOT NULL,
    twitter_url				varchar (256) DEFAULT '' NOT NULL,
    facebook_url            varchar (256) DEFAULT '' NOT NULL,

    created               int(11) NOT NULL,
    last_updated          int(11) NOT NULL,

	UNIQUE KEY ndx1 (member_id),
	KEY ndx2 (`first_name`, `last_name`),
	KEY ndx3 (`prefix`, `first_name`, `mid_name1`, `mid_name2`, `last_name`, `suffix`),
	KEY ndx4 (`gender`),
	KEY ndx5 (`birth_date`),
	KEY ndx6 (created),
	KEY ndx7 (last_updated)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

DROP TABLE IF EXISTS nomsterz_db.member_status;

CREATE TABLE nomsterz_db.member_status
(
  id 						int(16) NOT NULL AUTO_INCREMENT,
  member_id 				int(16) NOT NULL,
  status 					enum
				            (
					            'ValidMember',
					            'Successful-Signup',
					            'VerifiedEmail',
					            'VerifiedStartupDetails',
					            'BeginFirst90Days',
					            'First90DaysPlus30',
					            'TrialPeriodExpired',
					            'Premium',
					            'Standard',
					            'Basic',
					            'ChangedPassword',
					            'Paused-Member',
					            'Cancelled-Member',
					            'Cancelled-Financial',
					            'Locked:Excessive-Login-Attempts',
					            'Locked:Excessive-Signup-Attempts',
					            'Locked:Excessive-ForgotLogin-Attempts',
					            'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts',
					            'Locked:Excessive-ChangeOldPassword-Attempts',
					            'Locked:Excessive-LostSignupVerification-Attempts'
				            ) NOT NULL,
  created 					int(11) NOT NULL,

  PRIMARY KEY (id),
  KEY ndx1 (member_id),
  KEY ndx2 (created),

  KEY ndx1_s (member_id, status),
  KEY ndx1_2 (member_id, created),

  KEY ndx1_s_2 (member_id, status, created)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;


DROP TABLE IF EXISTS nomsterz_db.member;

CREATE TABLE nomsterz_db.member
(
  id 								int(16) NOT NULL AUTO_INCREMENT,
  member_type 			enum('unknown', 'business','customer','agent','guest', 'employee') NOT NULL,
  login_credentials varchar(256) NOT NULL,
  salt1 						varchar(32) NOT NULL,
  salt2 						varchar(32) NOT NULL,
  salt3 						varchar(32) NOT NULL,
  created 					int(11) NOT NULL,
  paused 						int(11) NOT NULL DEFAULT 0,
  cancelled 				int(11) NOT NULL DEFAULT 0,
  last_updated 			int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY ndx2 (created),
  KEY ndx3 (last_updated),
  KEY ndx2m (created,member_type),
  KEY ndx3m (last_updated,member_type),
  KEY ndx4 (salt1,salt2,salt3)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

DROP TABLE IF EXISTS nomsterz_db.member_emails;

CREATE TABLE IF NOT EXISTS nomsterz_db.member_emails
(
    id 				        int(16) NOT NULL AUTO_INCREMENT,
  	member_id 				int(16) NOT NULL,
  	email_address 			varchar(255) NOT NULL,

	verification_sent		tinyint (1) NOT NULL DEFAULT 0,
	verification_sent_on    int(11) NOT NULL DEFAULT 0,

	verified				tinyint (1) NOT NULL DEFAULT 0,
	verified_on 			int(11) NOT NULL DEFAULT 0,

  	created 				int(11) NOT NULL,
  	last_updated			int(11) NOT NULL,

	PRIMARY KEY (id),
  	UNIQUE KEY `ndx1` (member_id,`email_address`),
  	UNIQUE KEY `ndx2` (`email_address`),

  	KEY `ndx3` (`verification_sent`),
  	KEY `ndx4` (`verification_sent_on`),
  	KEY `ndx5` (`verified`),
  	KEY `ndx6` (`verified_on`),

  	KEY `ndx7` (`created`),
  	KEY `ndx8` (`created`,`email_address`),

  	KEY `ndx9` (`last_updated`),
  	KEY `ndx10` (`last_updated`,`email_address`),

  	KEY `ndx11` (`email_address`,`verified`,`verification_sent`,`verification_sent_on`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

DROP TABLE IF EXISTS nomsterz_db.email_status;

CREATE TABLE nomsterz_db.email_status
(
    id 						int(16) NOT NULL AUTO_INCREMENT,
    email_address 			varchar(256) NOT NULL,
    email_address_status	enum
				            (
					            'AddedUnverified',
					            'VerificationSent',
					            'VerificationSentAgain',
					            'Verified',
					            'Forgot',
					            'LostSignupVerification',
					            'Remembered',
					            'Paused',
					            'MadeDefault',
					            'Deleted',
					            'ChangedPassword',
					            'Locked:Excessive-Login-Attempts',
					            'Locked:Excessive-Signup-Attempts',
					            'Locked:Excessive-ForgotLogin-Attempts',
					            'Locked:Excessive-ChangeVerifiedLinkPassword-Attempts',
					            'Locked:Excessive-ChangeOldPassword-Attempts',
					            'Locked:Excessive-LostSignupVerification-Attempts'
				            ) NOT NULL,
    created 				int(11) NOT NULL,

    PRIMARY KEY (id),
  	KEY ndx1 (email_address),
    KEY ndx2 (created),

  	KEY ndx1_s (email_address,email_address_status),
    KEY ndx1_2 (email_address, created),

    KEY ndx1_s_2 (email_address, email_address_status, created)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

#############################################################################################################

USE nomsterz_utils;

DROP TABLE IF EXISTS nomsterz_utils.ip_bin;

CREATE TABLE nomsterz_utils.ip_bin
(
    id 			    int(16) NOT NULL AUTO_INCREMENT,
    user_id 	    int(16) NOT NULL,
	member_id 		int(16) NOT NULL,
    ip_address 		int(11) unsigned NOT NULL DEFAULT 0,
	ip_status       varchar(160) NOT NULL,
	created 		int(11) NOT NULL,
    last_updated 	int(11) NOT NULL,

    PRIMARY KEY (id),
    KEY ndx1 (user_id),
    KEY ndx2 (member_id),
    KEY ndx3 (ip_address),
    KEY ndx4 (created),
    KEY ndx5 (last_updated),

    KEY ndx3_is (ip_address, ip_status)
)
ENGINE=InnoDB
AUTO_INCREMENT=1
DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci
;

DROP TABLE IF EXISTS nomsterz_utils.access_attempt;

CREATE TABLE nomsterz_utils.access_attempt
(
	id 					int(11) unsigned NOT NULL AUTO_INCREMENT,
    user_id 			int(16) unsigned DEFAULT 0,
    attempt_type 		enum
		                (
			                'LoginForm',
			                'LoginCaptchaForm',
			                'SignupForm',
			                'ForgotForm',
			                'LostSignupVerificationForm',
			                'ChangePasswordWithVerifyLinkForm',
			                'ChangePasswordWithOldPasswordForm'
		                ) NOT NULL COMMENT 'This should be the form name. Basically, these are the forms that control access to the application',
    success 			varchar(160) NOT NULL DEFAULT 0 COMMENT '1 => Success, 0 => Fail, Anything else is suspicious...for now',
    attempted_at 		int(11) unsigned NOT NULL DEFAULT 0,

    PRIMARY KEY (id),

	KEY ndx1 (user_id),
	KEY ndx2 (attempt_type),
	KEY ndx3 (success),
	KEY ndx4 (attempted_at),

    KEY ndx1_2 (user_id,attempt_type),
    KEY ndx1_3 (user_id,success),
    KEY ndx2_3 (attempt_type,success),
    KEY ndx2_4 (attempt_type,attempted_at),

    KEY ndx1_2_3 (user_id,attempt_type,success),
    KEY ndx1_2_3_4 (user_id,attempt_type,success,attempted_at)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
	COMMENT 'Inserts only'
;

DROP TABLE IF EXISTS nomsterz_utils.session;

CREATE TABLE IF NOT EXISTS nomsterz_utils.session
(
    id 				char(32) NOT NULL DEFAULT '',
    name 			char(32) NOT NULL,
    modified 	int(11) DEFAULT NULL,
    lifetime 	int(11) DEFAULT NULL,
    data 			text ,

    PRIMARY KEY (id),

		KEY `ndx1` (`name`),
		KEY `ndx2` (`modified`),
		KEY `ndx3` (`lifetime`),
		KEY `ndx1_2` (`name`,`modified`),
		KEY `ndx1_3` (`name`,`lifetime`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;


DROP TABLE IF EXISTS nomsterz_utils.error;

CREATE TABLE nomsterz_utils.error
(
	`id` 								int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` 					int(16) unsigned DEFAULT 0,
  `err_message` 			varchar(255) NOT NULL DEFAULT '',
  `mvc_namespace` 		varchar(160) NOT NULL DEFAULT '',
  `mvc_controller` 		varchar(160) NOT NULL DEFAULT '',
  `mvc_action` 				varchar(160) NOT NULL DEFAULT '',
  `script_name` 			varchar(240) NOT NULL DEFAULT '',
  `uri` 							varchar(240) NOT NULL DEFAULT '',
  `error_level`  			varchar(8) NOT NULL DEFAULT '',
  `error_time` 				int(11) unsigned DEFAULT 0,
  `cookie_name` 			varchar(24) DEFAULT '',
  `cookie_value` 			varchar(240) DEFAULT '',

  PRIMARY KEY (`id`),

	KEY `ndx1` (`user_id`),
  KEY `ndx2` (`mvc_namespace`),
  KEY `ndx3` (`mvc_controller`),
  KEY `ndx4` (`mvc_action`),
  KEY `ndx5` (`script_name`),
  KEY `ndx6` (`uri`),
  KEY `ndx7` (`error_time`),
  KEY `ndx8` (`cookie_name`),

  KEY `ndx7_2` (`error_time`,`mvc_namespace`),
  KEY `ndx7_3` (`error_time`,`mvc_controller`),
  KEY `ndx7_4` (`error_time`,`mvc_action`),
  KEY `ndx7_6` (`error_time`,`uri`),
  KEY `ndx7_8` (`error_time`,`cookie_name`),

  KEY `ndx7_2_3_4` (`error_time`,`mvc_namespace`,`mvc_controller`,`mvc_action`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;


DROP TABLE IF EXISTS nomsterz_utils.benchmarks;

CREATE TABLE nomsterz_utils.benchmarks
(
	id 						int(11) unsigned NOT NULL AUTO_INCREMENT,
  name 					varchar(160) NOT NULL DEFAULT '',
  value 				varchar(24) NOT NULL DEFAULT '0',
  description 	varchar(160) NOT NULL DEFAULT '',

  PRIMARY KEY (id),

	UNIQUE ndx1 (name),
	KEY ndx2 (name, value)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;


DROP TABLE IF EXISTS nomsterz_utils.faq;

CREATE TABLE nomsterz_utils.faq
(
  `id` 						int(16) unsigned NOT NULL AUTO_INCREMENT,
  `category` 			varchar(120)  DEFAULT 'General',
  `title` 				varchar(120),
  `answer`  			text,
  `created` 			int(11) unsigned DEFAULT 0,
  `last_updated` 	int(11) unsigned DEFAULT 0,

  PRIMARY KEY (`id`),

	KEY `ndx1` (`title`),

  KEY `ndx1_c` (`title`, `category`),
  KEY `ndx0_t` (`id`,`created`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;


DROP TABLE IF EXISTS nomsterz_utils.faqhit;

CREATE TABLE nomsterz_utils.faqhit
(
  `id` 						int(16) unsigned NOT NULL AUTO_INCREMENT,
  `faq_id` 				int(16) unsigned NOT NULL ,
  `hit_time` 			int(11) unsigned DEFAULT 0,
  `user_id` 			int(16) unsigned NOT NULL DEFAULT 0,

  PRIMARY KEY (`id`),

	KEY `ndx1` (`faq_id`),
	KEY `ndx2` (`hit_time`),
	KEY `ndx3` (`user_id`),

  KEY `ndx1_2` (`faq_id`, `hit_time`),
  KEY `ndx1_3` (`faq_id`, `user_id`),
  KEY `ndx1_3_2` (`faq_id`, `user_id`, `hit_time`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;




##########################################

DROP TABLE nomsterz_legal.policy;

CREATE TABLE nomsterz_legal.policy
(
  `id`              int(16) NOT NULL AUTO_INCREMENT,
  `content_id`      int(16) NOT NULL,
  `title`           VARCHAR(180) NOT NULL,
  `created`         int(11) NOT NULL,
  `last_updated`    int(11) NOT NULL,

  PRIMARY KEY (`id`),
  KEY `ndx2` (`created`),
  KEY `ndx3` (`last_updated`),
  KEY `ndx2c` (`created`,`content_id`),
  KEY `ndx3c` (`last_updated`,`content_id`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

DROP TABLE nomsterz_legal.policy_content;

CREATE TABLE nomsterz_legal.policy_content
(
  `id`              int(16) NOT NULL AUTO_INCREMENT,
  `policy_id`       int(16) NOT NULL,
  `heading`         text NOT NULL,
  `content_html`    text NOT NULL,
  `content_text`    text NOT NULL,
  `sequence`        int(3) NOT NULL,
  `created`         int(11) NOT NULL,
  `last_updated`    int(11) NOT NULL,

  PRIMARY KEY (`id`),
  KEY `ndx2` (`created`),
  KEY `ndx3` (`last_updated`),
  KEY `ndx2p` (`created`,`policy_id`),
  KEY `ndx3p` (`last_updated`,`policy_id`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;


COMMIT;

-- EOF