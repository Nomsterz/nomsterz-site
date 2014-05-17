-- Author                   :   Chukky Nze
-- Company                  :   Nomsterz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/19/14 12:53 AM
-- Description              :   Creates the session table according to the DBSessionStorage module specifications
-- Database                 :   nomsterz_utils
-- Table                    :   session
-- Undo Script provided     :   2014-01-19--00-53_create-session-table.undo

START TRANSACTION;

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

COMMIT;

-- EOF