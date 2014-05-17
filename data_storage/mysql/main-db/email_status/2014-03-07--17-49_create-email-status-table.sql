-- Author                   :   Chukky Nze
-- Company                  :   Nomsterz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   3/07/14 5:19 PM
-- Description              :   Creates the email status table
-- Database                 :   nomsterz_db
-- Table                    :   email_status
-- Undo Script provided     :   2014-03-07--17-19_create-email-status-table.sql

START TRANSACTION;

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

COMMIT;

-- EOF