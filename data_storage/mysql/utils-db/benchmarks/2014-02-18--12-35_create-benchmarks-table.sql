-- Author                   :   Chukky Nze
-- Company                  :   NotaryToolz.com
-- Email                    :   chukkynze@notarytoolz.com
-- Date                     :   2/18/14 12:34 PM
-- Description              :   List of Benchmarks
-- Database                 :   notrytlz_utils
-- Table                    :   benchmarks
-- Undo Script provided     :   2014-02-18--12-35_create-benchmarks-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS notrytlz_utils.benchmarks;

CREATE TABLE notrytlz_utils.benchmarks
(
	id 						int(11) unsigned NOT NULL AUTO_INCREMENT,
  name 					varchar(160) NOT NULL DEFAULT '',
  value 				varchar(24) NOT NULL DEFAULT '0',
  description 	varchar(160) NOT NULL DEFAULT '',

  PRIMARY KEY (id),

	UNIQUE ndx1 (name),
	KEY ndx2 (name, value)
)
	ENGINE=InnoDB
	AUTO_INCREMENT=1
	DEFAULT CHARSET=utf8
	COLLATE=utf8_unicode_ci
;

desc notrytlz_utils.benchmarks;

show index in notrytlz_utils.benchmarks;


COMMIT;

-- EOF