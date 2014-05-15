-- Author                   :   Chukky Nze
-- Company                  :   NotaryToolz.com
-- Email                    :   chukkynze@notarytoolz.com
-- Date                     :   1/29/14 11:59 AM
-- Description              :   EnterDescriptionHere
-- Database                 :   EnterDatabaseNamesHere
-- Table                    :   EnterTableNamesHere
-- Undo Script provided     :   2014-01-29--12-00_create-policy-table.undo

START TRANSACTION;

CREATE TABLE notrytlz_legal.policy
(
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `content_id` int(16) NOT NULL,
  `policy_type` enum('terms','privacy') COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ndx2` (`created`),
  KEY `ndx3` (`last_updated`),
  KEY `ndx2c` (`created`,`content_id`),
  KEY `ndx3c` (`last_updated`,`content_id`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

CREATE TABLE notrytlz_legal.policy_content
(
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `policy_id` int(16) NOT NULL,
  `heading` text NOT NULL,
  `content_html` text NOT NULL,
  `content_text` text NOT NULL,
  `sequence` int(3) NOT NULL,
  `created` int(11) NOT NULL,
  `last_updated` int(11) NOT NULL,

  PRIMARY KEY (`id`),
  KEY `ndx2` (`created`),
  KEY `ndx3` (`last_updated`),
  KEY `ndx2p` (`created`,`policy_id`),
  KEY `ndx3p` (`last_updated`,`policy_id`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci;

COMMIT;

-- EOF