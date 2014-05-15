-- Author                   :   Chukky Nze
-- Company                  :   NotaryToolz.com
-- Email                    :   chukkynze@notarytoolz.com
-- Date                     :   1/23/14 9:52 PM
-- Description              :   EnterDescriptionHere
-- Database                 :   notrytlz_db
-- Table                    :   member_emails
-- Undo Script provided     :   2014-1-23--21-52_create-member-emails-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS notrytlz_db.member_emails;

CREATE TABLE IF NOT EXISTS notrytlz_db.member_emails
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

COMMIT;

-- EOF