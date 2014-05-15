-- Author                   :   Chukky Nze
-- Company                  :   NotaryToolz.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/7/14 2:17 PM
-- Description              :   Creates the faqhit table
-- Database                 :   notrytlz_utils
-- Table                    :   faqhit
-- Undo Script provided     :   2014-01-15--22-17_create-faq-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS notrytlz_utils.faqhit;

CREATE TABLE notrytlz_utils.faqhit
(
  `id` 						int(16) unsigned NOT NULL AUTO_INCREMENT,
  `faq_id` 				int(16) unsigned NOT NULL ,
  `hit_time` 			int(11) unsigned DEFAULT 0,
  `user_id` 			int(16) unsigned NOT NULL DEFAULT 0,

  PRIMARY KEY (`id`),

	KEY `ndx1` (`faq_id`),
	KEY `ndx2` (`hit_time`),
	KEY `ndx3` (`user_id`),

  KEY `ndx1_2` (`faq_id`, `hit_time`),
  KEY `ndx1_3` (`faq_id`, `user_id`),
  KEY `ndx1_3_2` (`faq_id`, `user_id`, `hit_time`)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

COMMIT;

-- EOF