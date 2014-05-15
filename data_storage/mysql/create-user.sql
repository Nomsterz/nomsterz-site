-- Author                   :   Chukky Nze
-- Company                  :   AkadaLMS.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/7/14 2:17 PM
-- Description              :   Creates the user
-- Database                 :   *
-- Table                    :   *

START TRANSACTION;

CREATE USER 'notrytlz_dba'@'localhost' IDENTIFIED BY '~1aDo1q#s}K+';
GRANT ALL PRIVILEGES ON * . * TO 'notrytlz_dba'@'localhost';

CREATE USER 'notrytlz_dba'@'*' IDENTIFIED BY '~1aDo1q#s}K+';
GRANT ALL PRIVILEGES ON * . * TO 'notrytlz_dba'@'*';

COMMIT;

-- EOF