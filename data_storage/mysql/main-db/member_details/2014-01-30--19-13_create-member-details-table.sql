-- Author                   :   Chukky Nze
-- Company                  :   notrytlz_db.com
-- Email                    :   chukkynze@notarytoolz.com
-- Date                     :   1/30/14 7:13 PM
-- Description              :   EnterDescriptionHere
-- Database                 :   notrytlz_db
-- Table                    :   EnterTableNamesHere
-- Undo Script provided     :   2014-01-30--19-13_create-member-details-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS notrytlz_db.member_details;

CREATE TABLE IF NOT EXISTS notrytlz_db.member_details
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


INSERT INTO notrytlz_db.member_details
(id, member_id, prefix, first_name, mid_name1, mid_name2, last_name, suffix, gender, birth_date, zipcode, created, last_updated)
VALUES
(1, 1, '', 'Chukwuma', '', '', 'Nze', '', 2, '0000-00-00', '91607', 1395088040, 1395088040);

COMMIT;

-- EOF