SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

DELIMITER ;;

DROP EVENT IF EXISTS `Clean tmp accounts`;;
CREATE EVENT `Clean tmp accounts` ON SCHEDULE EVERY 1 DAY STARTS '2024-12-08 19:53:01' ON COMPLETION PRESERVE ENABLE DO DELETE FROM `tmpusers` WHERE `tusr_time` < UNIX_TIMESTAMP(DATE_SUB(now(),interval 10 day));;

DELIMITER ;

DROP TABLE IF EXISTS `aircrafts`;
CREATE TABLE `aircrafts` (
  `ac_id` smallint unsigned NOT NULL AUTO_INCREMENT,
  `ac_type` varchar(32) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `ac_cat` tinyint unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ac_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices` (
  `dev_id` varchar(6) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `dev_type` tinyint NOT NULL DEFAULT '2',
  `dev_actype` smallint unsigned NOT NULL,
  `dev_acreg` varchar(7) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `dev_accn` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `dev_userid` mediumint unsigned NOT NULL,
  `dev_notrack` tinyint unsigned NOT NULL DEFAULT '0',
  `dev_noident` tinyint unsigned NOT NULL DEFAULT '0',
  `dev_updatetime` bigint NOT NULL,
  UNIQUE KEY `dev_id` (`dev_id`),
  KEY `dev_notrack` (`dev_notrack`),
  KEY `dev_noident` (`dev_noident`),
  KEY `dev_actype` (`dev_actype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


DROP TABLE IF EXISTS `tmpusers`;
CREATE TABLE `tmpusers` (
  `tusr_adress` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tusr_pw` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tusr_validation` varchar(128) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL,
  `tusr_time` bigint NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `usr_id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `usr_adress` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `usr_pw` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `usr_token_hash` varchar(64) NULL DEFAULT NULL,
  `usr_token_hint` varchar(24) NULL DEFAULT NULL,
  `usr_device_limit` tinyint unsigned NOT NULL DEFAULT 20,
  PRIMARY KEY (`usr_id`),
  UNIQUE KEY `usr_adress` (`usr_adress`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
