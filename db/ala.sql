-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2019 at 08:51 PM
-- Server version: 5.5.36
-- PHP Version: 5.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ala`
--

-- --------------------------------------------------------

--
-- Table structure for table `ala_actions`
--

CREATE TABLE IF NOT EXISTS `ala_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `action_type_id` int(11) NOT NULL,
  `action_title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_finished` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_action_goal_mapping`
--

CREATE TABLE IF NOT EXISTS `ala_action_goal_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `action_id` int(11) NOT NULL,
  `goal_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_action_reminders`
--

CREATE TABLE IF NOT EXISTS `ala_action_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_id` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_action_types`
--

CREATE TABLE IF NOT EXISTS `ala_action_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ala_action_types`
--

INSERT INTO `ala_action_types` (`id`, `title`, `status`) VALUES
(1, 'One Time', 1),
(2, 'Daily', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ala_action_user_notes`
--

CREATE TABLE IF NOT EXISTS `ala_action_user_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `feedback_node` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_admin`
--

CREATE TABLE IF NOT EXISTS `ala_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_goals`
--

CREATE TABLE IF NOT EXISTS `ala_goals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_post_meeting`
--

CREATE TABLE IF NOT EXISTS `ala_post_meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `general_topic` text NOT NULL,
  `session_value` text NOT NULL,
  `notes` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_post_meeting_action_mapping`
--

CREATE TABLE IF NOT EXISTS `ala_post_meeting_action_mapping` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_meeting_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `is_finished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_pre_meeting`
--

CREATE TABLE IF NOT EXISTS `ala_pre_meeting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `general_happiness_level` int(11) NOT NULL DEFAULT '0',
  `acknowledgment` text NOT NULL,
  `obstacles` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_user`
--

CREATE TABLE IF NOT EXISTS `ala_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT '0',
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ala_user`
--

INSERT INTO `ala_user` (`id`, `admin_id`, `first_name`, `last_name`, `email`, `phone`, `password`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'asd', 'asdf', 'admin@bk.com', '9923179374', '47bce5c74f589f4867dbd57e9ca9f808', 1, '2019-05-21 06:23:25', '2019-05-21 07:18:23');

-- --------------------------------------------------------

--
-- Table structure for table `ala_value_identifiers`
--

CREATE TABLE IF NOT EXISTS `ala_value_identifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ala_value_identifier_details`
--

CREATE TABLE IF NOT EXISTS `ala_value_identifier_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `value_identifier_id` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `current_happiness_level` int(11) NOT NULL DEFAULT '0',
  `expected_happiness_level` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `last_activity_idx` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`id`, `ip_address`, `user_agent`, `timestamp`, `data`) VALUES
('0b7430747ac58f80e759607a50768baa6155e12d', '::1', '', 1558463259, '__ci_last_regenerate|i:1558462945;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('32868e2df9b0fd688977cf81f60ad7fd67afd25e', '::1', '', 1558462090, '__ci_last_regenerate|i:1558461924;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('3f86e24a5bab8ca2c7fe6f6fb1581a428e715949', '::1', '', 1558464667, '__ci_last_regenerate|i:1558463707;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('43edf294438c3cfa6902cc43127929b62f37011a', '::1', '', 1558462942, '__ci_last_regenerate|i:1558462941;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('58262c5fa55ac66f9d49b6c6dbe8dfe2981fd855', '::1', '', 1558463706, '__ci_last_regenerate|i:1558463260;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('ab8da1f37d64e3f5848404fb91da45ce2c834dac', '::1', '', 1558462940, '__ci_last_regenerate|i:1558462940;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('ea96f86a5735a55734e154c44c85b0217b072226', '::1', '', 1558462942, '__ci_last_regenerate|i:1558462552;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
