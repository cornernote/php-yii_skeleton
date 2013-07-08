-- phpMyAdmin SQL Dump
-- version 4.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 08, 2013 at 02:09 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE IF NOT EXISTS `audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_trail_id` int(11) NOT NULL,
  `old_value` text NOT NULL,
  `new_value` text NOT NULL,
  `action` varchar(20) NOT NULL,
  `model` varchar(255) NOT NULL,
  `model_id` varchar(64) NOT NULL,
  `field` varchar(64) NOT NULL,
  `created` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `model_id` (`model_id`),
  KEY `model` (`model`),
  KEY `field` (`field`),
  KEY `action` (`action`),
  KEY `page_trail_id` (`page_trail_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

CREATE TABLE IF NOT EXISTS `log` (
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
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `model_cache`
--

CREATE TABLE IF NOT EXISTS `model_cache` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `model_id` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `cache` longtext NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `model_name` (`model`),
  KEY `model_value` (`key`),
  KEY `created` (`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_trail`
--

CREATE TABLE IF NOT EXISTS `page_trail` (
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
  `preserve` int(11) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'manager'),
(3, 'staff'),
(4, 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `setting`
--

CREATE TABLE IF NOT EXISTS `setting` (
  `id` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting`
--

INSERT INTO `setting` (`id`) VALUES
('app'),
('core'),
('user');

-- --------------------------------------------------------

--
-- Table structure for table `setting_eav`
--

CREATE TABLE IF NOT EXISTS `setting_eav` (
  `entity` varchar(64) NOT NULL,
  `attribute` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`entity`,`attribute`),
  KEY `value` (`value`(32)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `setting_eav`
--

INSERT INTO `setting_eav` (`entity`, `attribute`, `value`) VALUES
('app', 'allowAutoLogin', '1'),
('app', 'dateFormat', 'Y-m-d'),
('app', 'dateTimeFormat', 'Y-m-d H:i:s'),
('app', 'defaultPageSize', '10'),
('app', 'domain', 'localhost'),
('app', 'email', 'support@localhost'),
('app', 'language', 'en'),
('app', 'name', 'App Name'),
('app', 'phone', '1800 000 000'),
('app', 'recaptcha', '1'),
('app', 'recaptchaPrivate', '6LeBItQSAAAAALA4_G05e_-fG5yH_-xqQIN8AfTD'),
('app', 'recaptchaPublic', '6LeBItQSAAAAAG_umhiD0vyxXbDFbVMPA0kxZUF6'),
('app', 'rememberMe', '1'),
('app', 'theme', 'admingrey'),
('app', 'webmaster', 'webmaster@localhost'),
('app', 'website', 'www.localhost'),
('core', 'app_version', 'app-1.dev'),
('core', 'debug', '1'),
('core', 'debug_db', '0'),
('core', 'debug_email', '1'),
('core', 'debug_levels', 'error,warning'),
('core', 'debug_toolbar', '0'),
('core', 'id', 'app'),
('core', 'memory_limit', '512M'),
('core', 'timezone', 'Australia/Adelaide'),
('core', 'time_limit', '600'),
('core', 'yii_lite', '0'),
('core', 'yii_version', 'yii-1.1.12.b600af');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `fax` varchar(255) NOT NULL,
  `web_status` int(11) NOT NULL,
  `api_status` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `deleted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `created` (`created`,`deleted`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `password`, `name`, `phone`, `fax`, `web_status`, `api_status`, `api_key`, `created`, `deleted`) VALUES
(1, 'admin@localhost', 'admin', '$2a$08$b.5MVtbgKv4Dvf/M3AFKKuga4pxptFOsmu7gkN.QOH5yvws6Ks03i', '', '', '', 1, 0, '', '2012-07-10 01:40:42', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_eav`
--

CREATE TABLE IF NOT EXISTS `user_eav` (
  `entity` int(11) unsigned NOT NULL,
  `attribute` varchar(64) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`entity`,`attribute`),
  KEY `value` (`value`(32)) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_to_role`
--

CREATE TABLE IF NOT EXISTS `user_to_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT 'FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)',
  `role_id` int(11) unsigned NOT NULL COMMENT 'FOREIGN KEY (`role_id`) REFERENCES `role` (`id`)',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user_to_role`
--

INSERT INTO `user_to_role` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
