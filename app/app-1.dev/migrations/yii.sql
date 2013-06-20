/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50516
Source Host           : localhost:3306
Source Database       : keyvault

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2012-07-10 03:53:04
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `audit_trail`
-- ----------------------------
DROP TABLE IF EXISTS `audit_trail`;
CREATE TABLE `audit_trail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `old_value` text NOT NULL,
  `new_value` text NOT NULL,
  `action` varchar(20) NOT NULL,
  `model` varchar(255) NOT NULL,
  `field` varchar(64) NOT NULL,
  `created` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `model_id` varchar(65) NOT NULL,
  `page_trail_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `model_id` (`model_id`),
  KEY `model` (`model`),
  KEY `field` (`field`),
  KEY `action` (`action`),
  KEY `page_trail_id` (`page_trail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of audit_trail
-- ----------------------------

-- ----------------------------
-- Table structure for `log`
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `model` varchar(255) NOT NULL,
  `model_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `details` text NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model` (`model`,`model_id`),
  KEY `created_dt` (`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of log
-- ----------------------------

-- ----------------------------
-- Table structure for `model_cache`
-- ----------------------------
DROP TABLE IF EXISTS `model_cache`;
CREATE TABLE `model_cache` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `model_id` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `cache` text NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `model_name` (`model`),
  KEY `model_value` (`key`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of model_cache
-- ----------------------------

-- ----------------------------
-- Table structure for `page_trail`
-- ----------------------------
DROP TABLE IF EXISTS `page_trail`;
CREATE TABLE `page_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(1000) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `post` text NOT NULL,
  `get` text NOT NULL,
  `files` text NOT NULL,
  `session` text NOT NULL,
  `server` text NOT NULL,
  `cookie` text NOT NULL,
  `referrer` varchar(1000) NOT NULL,
  `redirect` varchar(1000) NOT NULL,
  `app_version` varchar(255) NOT NULL,
  `yii_version` varchar(255) NOT NULL,
  `audit_trail_count` int(11) NOT NULL,
  `start_time` decimal(14,4) NOT NULL,
  `end_time` decimal(14,4) NOT NULL,
  `total_time` decimal(14,4) NOT NULL,
  `memory_usage` int(11) NOT NULL,
  `memory_peak` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stamp` (`created`),
  KEY `user_id` (`user_id`),
  KEY `url` (`link`(255)),
  KEY `audit_trail_count` (`audit_trail_count`),
  KEY `redirect` (`redirect`(255)),
  KEY `app_version` (`app_version`),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`),
  KEY `total_time` (`total_time`),
  KEY `memory_usage` (`memory_usage`),
  KEY `memory_peak` (`memory_peak`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of page_trail
-- ----------------------------

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES ('1', 'admin');
INSERT INTO `role` VALUES ('2', 'reseller');
INSERT INTO `role` VALUES ('3', 'locksmith');
INSERT INTO `role` VALUES ('4', 'staff');
INSERT INTO `role` VALUES ('5', 'manager');
INSERT INTO `role` VALUES ('6', 'customer');

-- ----------------------------
-- Table structure for `setting`
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting` (
  `id` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of setting
-- ----------------------------
INSERT INTO `setting` VALUES ('app');
INSERT INTO `setting` VALUES ('core');

-- ----------------------------
-- Table structure for `setting_eav`
-- ----------------------------
DROP TABLE IF EXISTS `setting_eav`;
CREATE TABLE `setting_eav` (
  `entity` varchar(64) NOT NULL,
  `attribute` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`entity`,`attribute`),
  KEY `value` (`value`(32)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of setting_eav
-- ----------------------------
INSERT INTO `setting_eav` VALUES ('app', 'allowAutoLogin', '1');
INSERT INTO `setting_eav` VALUES ('app', 'dateFormat', 'Y-m-d');
INSERT INTO `setting_eav` VALUES ('app', 'dateTimeFormat', 'Y-m-d H:i:s');
INSERT INTO `setting_eav` VALUES ('app', 'defaultPageSize', '10');
INSERT INTO `setting_eav` VALUES ('app', 'domain', 'keyvault.local');
INSERT INTO `setting_eav` VALUES ('app', 'email', 'support@keyvault.local');
INSERT INTO `setting_eav` VALUES ('app', 'language', 'en');
INSERT INTO `setting_eav` VALUES ('app', 'name', 'Key Vault');
INSERT INTO `setting_eav` VALUES ('app', 'phone', '1800 000 000');
INSERT INTO `setting_eav` VALUES ('app', 'recaptcha', '1');
INSERT INTO `setting_eav` VALUES ('app', 'recaptchaPrivate', '');
INSERT INTO `setting_eav` VALUES ('app', 'recaptchaPublic', '');
INSERT INTO `setting_eav` VALUES ('app', 'rememberMe', '1');
INSERT INTO `setting_eav` VALUES ('app', 'theme', 'admingrey');
INSERT INTO `setting_eav` VALUES ('app', 'webmaster', 'webmaster@keyvault.local');
INSERT INTO `setting_eav` VALUES ('app', 'website', 'http://www.keyvault.local');
INSERT INTO `setting_eav` VALUES ('core', 'app_version', 'app-1.dev');
INSERT INTO `setting_eav` VALUES ('core', 'debug', '1');
INSERT INTO `setting_eav` VALUES ('core', 'debug_db', '0');
INSERT INTO `setting_eav` VALUES ('core', 'debug_levels', 'error,warning');
INSERT INTO `setting_eav` VALUES ('core', 'debug_toolbar', '0');
INSERT INTO `setting_eav` VALUES ('core', 'id', 'keyvault');
INSERT INTO `setting_eav` VALUES ('core', 'memory_limit', '512M');
INSERT INTO `setting_eav` VALUES ('core', 'timezone', 'Australia/Sydney');
INSERT INTO `setting_eav` VALUES ('core', 'time_limit', '600');
INSERT INTO `setting_eav` VALUES ('core', 'yii_lite', '0');
INSERT INTO `setting_eav` VALUES ('core', 'yii_version', 'yii-1.1.10.r3566');

-- ----------------------------
-- Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `locksmith_id` int(11) unsigned NOT NULL,
  `customer_id` int(11) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `api_status` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `created_dt` (`created`,`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES ('1', '1', '1', 'admin@brett.local', 'admin', '0c6076ab9c47d7b86c54f05b17012428d6fc13b0:01', 'Admin', 'User', '', '', '1', '0', '', '2012-07-10 01:40:42', null);

-- ----------------------------
-- Table structure for `user_eav`
-- ----------------------------
DROP TABLE IF EXISTS `user_eav`;
CREATE TABLE `user_eav` (
  `entity` int(11) unsigned NOT NULL,
  `attribute` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`entity`,`attribute`),
  KEY `value` (`value`(32)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_eav
-- ----------------------------

-- ----------------------------
-- Table structure for `user_to_role`
-- ----------------------------
DROP TABLE IF EXISTS `user_to_role`;
CREATE TABLE `user_to_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT 'CONSTRAINT `user_to_role_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)',
  `role_id` int(11) unsigned NOT NULL COMMENT 'CONSTRAINT `user_to_role_role_id` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of user_to_role
-- ----------------------------
INSERT INTO `user_to_role` VALUES ('1', '1', '1');
