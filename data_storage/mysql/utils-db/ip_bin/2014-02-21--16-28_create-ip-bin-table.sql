-- Author                   :   Chukky Nze
-- Company                  :   NotaryToolz.com
-- Email                    :   chukkynze@notarytoolz.com
-- Date                     :   2/21/14 4:28 PM
-- Description              :   Holds IP Addresses we are "interested" in
-- Database                 :   notrytlz_utils
-- Table                    :   ip_bin
-- Undo Script provided     :   2014-02-21--16-28_create-ip-bin-table.undo

START TRANSACTION;

DROP TABLE IF EXISTS notrytlz_utils.ip_bin;

CREATE TABLE notrytlz_utils.ip_bin
(
    id 			    int(16) NOT NULL AUTO_INCREMENT,
    user_id 	    int(16) NOT NULL,
	member_id 		int(16) NOT NULL,
    ip_address 		int(11) unsigned NOT NULL DEFAULT 0,
	ip_status       varchar(160) NOT NULL,
	created 		int(11) NOT NULL,
    last_updated 	int(11) NOT NULL,

    PRIMARY KEY (id),
    KEY ndx1 (user_id),
    KEY ndx2 (member_id),
    KEY ndx3 (ip_address),
    KEY ndx4 (created),
    KEY ndx5 (last_updated),

    KEY ndx3_is (ip_address, ip_status)
)
ENGINE=InnoDB
AUTO_INCREMENT=1
DEFAULT CHARSET=utf8
COLLATE=utf8_unicode_ci
;


COMMIT;

-- EOF