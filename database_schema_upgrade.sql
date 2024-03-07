SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';


ALTER TABLE devices ADD COLUMN dev_updatetime bigint(20) NOT NULL;
UPDATE devices SET dev_updatetime=UNIX_TIMESTAMP() where 1;