-- Author                   :   Chukky Nze
-- Company                  :   Nomsterz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/7/14 2:17 PM
-- Description              :   Creates the pagehit table
-- Database                 :   nomsterz_db
-- Table                    :   pagehit
-- Undo Script provided     :   2014-01-07--14-17_create-pagehit-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS nomsterz_db.pagehit;

CREATE TABLE nomsterz_db.pagehit
(
	`id` 									int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` 						int(16) unsigned DEFAULT 0,
  `cookies` 						text ,
  `url_location` 				varchar(255) NOT NULL DEFAULT '',
  `client_time` 				int(11) unsigned DEFAULT 0,
  `server_time` 				int(11) unsigned DEFAULT 0,
  `screen_size` 				varchar(255) NOT NULL DEFAULT '',
  `avail_screen_size` 	varchar(255) NOT NULL DEFAULT '',
  `kvpid` 							int(16) unsigned DEFAULT 0,

  PRIMARY KEY (`id`),

	KEY `ndx1` (`user_id`),
  KEY `ndx3` (`url_location`),
  KEY `ndx4` (`client_time`),
  KEY `ndx5` (`server_time`),
  KEY `ndx6` (`kvpid`),

  KEY `ndx4_5` (`client_time`,`server_time`),
  KEY `ndx5_3` (`server_time`,`user_id`),
  KEY `ndx5_6` (`server_time`,`kvpid`),
  KEY `ndx5_8` (`server_time`,`url_location`),

  KEY `ndx1_s_a` (`user_id`,`screen_size`,`avail_screen_size`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

COMMIT;

-- EOF