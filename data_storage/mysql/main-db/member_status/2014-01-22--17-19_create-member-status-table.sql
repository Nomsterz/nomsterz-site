-- Author                   :   Chukky Nze
-- Company                  :   Nomsterz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/22/14 5:19 PM
-- Description              :   Drops the member status table
-- Database                 :   nomsterz_db
-- Table                    :   member_status
-- Actual Script provided   :   2014-01-22--17-19_create-member-status-table.undo

START TRANSACTION;

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

COMMIT;

-- EOF