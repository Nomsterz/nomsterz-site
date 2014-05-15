-- Author                   :   Chukky Nze
-- Company                  :   NotaryToolz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/7/14 2:17 PM
-- Description              :   Creates the user table
-- Database                 :   notrytlz_db
-- Table                    :   user
-- Undo Script provided     :   2014-01-07--14-17_create-user-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS notrytlz_db.user;

CREATE TABLE notrytlz_db.user
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

COMMIT;

-- EOF