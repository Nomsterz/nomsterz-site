-- Author                   :   Chukky Nze
-- Company                  :   Nomsterz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/18/14 11:111 PM
-- Description              :   Creates the member table
-- Database                 :   nomsterz_db
-- Table                    :   member
-- Undo Script provided     :   2014-01-18--23-11_create-member-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS nomsterz_db.member;

CREATE TABLE nomsterz_db.member
(
  id 								int(16) NOT NULL AUTO_INCREMENT,
  member_type 			enum('unknown', 'notary','employee','signing agent','lender', 'client') NOT NULL,
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

COMMIT;

-- EOF