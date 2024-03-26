SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `aircrafts`;
DROP TABLE IF EXISTS `devices`;
DROP TABLE IF EXISTS `tmpusers`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `aircrafts` (
  `ac_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ac_type` varchar(32) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `ac_cat` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`ac_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `devices` (
  `dev_id` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `dev_type` tinyint(4) NOT NULL DEFAULT '2',
  `dev_actype` smallint(5) unsigned NOT NULL,
  `dev_acreg` varchar(7) COLLATE utf8_unicode_ci NOT NULL,
  `dev_accn` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `dev_userid` mediumint(8) unsigned NOT NULL,
  `dev_notrack` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dev_noident` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `dev_updatetime` bigint(20) NOT NULL,
  UNIQUE KEY `dev_id` (`dev_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `tmpusers` (
  `tusr_adress` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tusr_pw` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `tusr_validation` varchar(128) CHARACTER SET utf16 COLLATE utf16_unicode_ci NOT NULL,
  `tusr_time` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `users` (
  `usr_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `usr_adress` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `usr_pw` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`usr_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
