-- Author                   :   Chukky Nze
-- Company                  :   NotaryToolz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/7/14 2:17 PM
-- Description              :   Creates the faq table
-- Database                 :   notrytlz_utils
-- Table                    :   faq
-- Undo Script provided     :   2014-01-15--22-17_create-faq-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS notrytlz_utils.faq;

CREATE TABLE notrytlz_utils.faq
(
  `id` 						int(16) unsigned NOT NULL AUTO_INCREMENT,
  `category` 			varchar(120)  DEFAULT 'General',
  `title` 				varchar(120),
  `answer`  			text,
  `created` 			int(11) unsigned DEFAULT 0,
  `last_updated` 	int(11) unsigned DEFAULT 0,

  PRIMARY KEY (`id`),

	KEY `ndx1` (`title`),

  KEY `ndx1_c` (`title`, `category`),
  KEY `ndx0_t` (`id`,`created`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

COMMIT;

-- EOF