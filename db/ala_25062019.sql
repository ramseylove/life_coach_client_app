-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2019 at 09:34 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ala_actions`
--

INSERT INTO `ala_actions` (`id`, `user_id`, `action_type_id`, `action_title`, `status`, `is_finished`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'App One123', 1, 0, '2019-05-29 00:29:48', '2019-05-29 00:29:48'),
(2, 1, 2, 'App One1234', 1, 0, '2019-05-31 00:09:09', '2019-05-31 23:24:45'),
(3, 1, 1, 'hj', 1, 0, '2019-06-12 23:16:45', '2019-06-12 23:16:45');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `ala_action_goal_mapping`
--

INSERT INTO `ala_action_goal_mapping` (`id`, `user_id`, `action_id`, `goal_id`) VALUES
(1, 1, 1, 2),
(2, 1, 1, 3),
(45, 1, 2, 2),
(46, 1, 2, 3),
(47, 1, 3, 2),
(48, 1, 3, 4);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=74 ;

--
-- Dumping data for table `ala_action_reminders`
--

INSERT INTO `ala_action_reminders` (`id`, `action_id`, `date`, `time`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '00:29:00', 1, '2019-05-29 00:29:48', '2019-05-29 00:29:48'),
(2, 1, NULL, '03:20:00', 1, '2019-05-29 00:29:48', '2019-05-29 00:29:48'),
(3, 1, NULL, '04:30:00', 1, '2019-05-29 00:29:48', '2019-05-29 00:29:48'),
(68, 2, NULL, '23:24:00', 1, '2019-05-31 23:24:45', '2019-05-31 23:24:45'),
(69, 2, NULL, '23:24:00', 1, '2019-05-31 23:24:45', '2019-05-31 23:24:45'),
(70, 2, NULL, '23:24:00', 1, '2019-05-31 23:24:45', '2019-05-31 23:24:45'),
(71, 2, NULL, '23:24:00', 1, '2019-05-31 23:24:45', '2019-05-31 23:24:45'),
(72, 2, NULL, '23:24:00', 1, '2019-05-31 23:24:45', '2019-05-31 23:24:45'),
(73, 3, '2019-06-12', '23:16:00', 1, '2019-06-12 23:16:45', '2019-06-12 23:16:45');

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
  `is_secondary` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `ala_goals`
--

INSERT INTO `ala_goals` (`id`, `user_id`, `title`, `description`, `is_secondary`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'App One1', '<p>43</p>', 0, 1, '2019-05-23 23:02:13', '2019-05-24 00:09:07'),
(2, 1, 'se', '<p>ssese</p>', 0, 1, '2019-05-23 23:03:18', '2019-05-23 23:03:18'),
(3, 1, 'dsd', '<p>dsd</p>', 0, 1, '2019-05-23 23:04:02', '2019-05-23 23:04:02'),
(4, 1, 'zsczsz', '<p>asdadad</p>', 0, 1, '2019-05-24 21:05:56', '2019-05-24 21:05:56');

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
  `current_happiness_level` int(11) NOT NULL DEFAULT '0',
  `expected_happiness_level` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `ala_value_identifiers`
--

INSERT INTO `ala_value_identifiers` (`id`, `user_id`, `title`, `current_happiness_level`, `expected_happiness_level`, `created_at`, `updated_at`) VALUES
(2, 1, 'App One1', 0, 0, '2019-05-23 23:34:58', '2019-05-23 23:34:58'),
(3, 1, 'test', 0, 0, '2019-05-24 20:56:55', '2019-05-24 20:57:14'),
(5, 1, 'zsczsz', 0, 0, '2019-05-24 21:02:35', '2019-05-24 21:02:35'),
(6, 1, 'asasasas', 0, 0, '2019-05-24 21:03:08', '2019-05-24 21:03:08'),
(7, 1, 'huiid', 3, 4, '2019-05-24 21:05:34', '2019-06-25 01:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `ala_value_identifier_details`
--

CREATE TABLE IF NOT EXISTS `ala_value_identifier_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `value_identifier_id` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT 'description_0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `ala_value_identifier_details`
--

INSERT INTO `ala_value_identifier_details` (`id`, `user_id`, `value_identifier_id`, `description`, `identifier`, `created_at`, `updated_at`) VALUES
(2, 1, 2, '<p>fgcfh</p>', 'description_0', '2019-05-23 23:34:58', '2019-05-23 23:34:58'),
(3, 1, 3, '<p>asd</p>', 'description_0', '2019-05-24 20:56:55', '2019-05-24 20:57:14'),
(5, 1, 5, '<p>saasa</p>', 'description_0', '2019-05-24 21:02:36', '2019-05-24 21:02:36'),
(6, 1, 6, '<p>asasasa</p>', 'description_0', '2019-05-24 21:03:08', '2019-05-24 21:03:08'),
(20, 1, 7, 'sgesdgsgd0', 'description_0', '2019-06-25 01:01:45', '2019-06-25 01:01:45'),
(21, 1, 7, 'sdgsdgsgs1', 'description_1', '2019-06-25 01:01:45', '2019-06-25 01:01:45'),
(22, 1, 7, 'sdgsasdfa2', 'description_2', '2019-06-25 01:01:45', '2019-06-25 01:01:45'),
(23, 1, 7, 'afasfasfaf3', 'description_3', '2019-06-25 01:01:45', '2019-06-25 01:01:45');

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
('147ed3d60757f2e496555182c22b7f7822d1c385', '::1', '', 1560364236, '__ci_last_regenerate|i:1560363584;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('16141fd5146dbbcd401b3fed1d7de667f3c3328f', '::1', '', 1560360656, '__ci_last_regenerate|i:1560360332;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('18206270eb76cc7fc6e0ff078abb37fc4402a653', '::1', '', 1560359906, '__ci_last_regenerate|i:1560359601;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('1fac57edeb939c084f2b150c3f3f6631cb1d24b7', '::1', '', 1560361796, '__ci_last_regenerate|i:1560361586;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('31567b3831933570cd27824cc670b08d0fe8d636', '::1', '', 1560360332, '__ci_last_regenerate|i:1560359914;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('37d96e47334aa028a335119f0650dcac15a0506d', '::1', '', 1560363583, '__ci_last_regenerate|i:1560362883;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('3a2bd4eee58b2773c297489507d5227217a105d1', '::1', '', 1561404883, '__ci_last_regenerate|i:1561404690;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('50bf403f9c46bf32b1c174c733f2d92028af6250', '::1', '', 1561401411, '__ci_last_regenerate|i:1561401411;'),
('6973ad92d6075b5abb7132320e1c3e25d459979f', '::1', '', 1560361585, '__ci_last_regenerate|i:1560360658;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('6a53b02236681640219413fbac603e90b3a19201', '::1', '', 1561404324, '__ci_last_regenerate|i:1561402585;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";'),
('7fea428f752e30e06cc5591cf307c975c976f566', '::1', '', 1560359914, ''),
('ce3fccefb2fbc87b28a9e4ac5d538a19baaa53b0', '::1', '', 1561404689, '__ci_last_regenerate|i:1561404325;user_id|s:1:"1";fname|s:3:"asd";lname|s:4:"asdf";email|s:12:"admin@bk.com";phone|s:10:"9923179374";');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
