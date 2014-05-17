-- Author                   :   Chukky Nze
-- Company                  :   AkadaLMS.com
-- Email                    :   chukkynze@gmail.com
-- Date                     :   1/7/14 2:17 PM
-- Description              :   Creates the user
-- Database                 :   *
-- Table                    :   *

START TRANSACTION;

CREATE USER 'nomsterz_dba'@'localhost' IDENTIFIED BY 'Q4qWrq_a!.tf;lCR3Z';
GRANT ALL PRIVILEGES ON * . * TO 'nomsterz_dba'@'localhost';

CREATE USER 'nomsterz_dba'@'*' IDENTIFIED BY 'Q4qWrq_a!.tf;lCR3Z';
GRANT ALL PRIVILEGES ON * . * TO 'nomsterz_dba'@'*';

COMMIT;

-- EOF