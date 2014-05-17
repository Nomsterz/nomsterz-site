-- Author                   :   Chukky Nze
-- Company                  :   Nomsterz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/6/14 10:17 PM
-- Description              :   Creates the error table
-- Database                 :   nomsterz_utils
-- Table                    :   error
-- Undo Script provided     :   2014-01-06--22-17_create-error-table.undo

START TRANSACTION;

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

desc nomsterz_utils.error;

show index in nomsterz_utils.error;

COMMIT;

-- EOF