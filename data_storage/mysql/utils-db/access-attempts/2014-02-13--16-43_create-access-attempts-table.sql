-- Author                   :   Chukky Nze
-- Company                  :   Nomsterz.com
-- Email                    :   chukkynze@nomsterz.com
-- Date                     :   2/13/14 4:42 PM
-- Description              :   Create a table to monitor access into the application (login, signup, forgot, etc)
-- Database                 :   nomsterz_utils
-- Table                    :   access_attempt
-- Undo Script provided     :   2014-02-13--16-43_create-access-attempts-table.undo

START TRANSACTION;

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

desc nomsterz_utils.access_attempt;

show index in nomsterz_utils.access_attempt;

COMMIT;

-- EOF