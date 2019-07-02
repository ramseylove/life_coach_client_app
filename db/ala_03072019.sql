-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2019 at 08:34 PM
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
(1, 1, 2, 'App One123', 1, 1, '2019-05-29 00:29:48', '2019-06-27 00:33:48'),
(2, 1, 2, 'App One1234', 1, 1, '2019-05-31 00:09:09', '2019-06-27 23:24:10'),
(3, 1, 1, 'hj', 1, 1, '2019-06-12 23:16:45', '2019-06-27 23:23:26');

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
  `identifier` varchar(255) NOT NULL DEFAULT 'action_complete_0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `ala_action_user_notes`
--

INSERT INTO `ala_action_user_notes` (`id`, `user_id`, `action_id`, `feedback_node`, `identifier`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'dsg', 'action_complete_0', '2019-06-27 00:33:48', '2019-06-27 00:33:48'),
(2, 1, 1, 'dfsfsd', 'action_complete_1', '2019-06-27 00:33:48', '2019-06-27 00:33:48'),
(3, 1, 1, 'fsfsdfs', 'action_complete_2', '2019-06-27 00:33:48', '2019-06-27 00:33:48'),
(4, 1, 3, 'rgsdfs', 'action_complete_0', '2019-06-27 23:23:26', '2019-06-27 23:23:26'),
(5, 1, 3, 'dfsdfsfs', 'action_complete_1', '2019-06-27 23:23:26', '2019-06-27 23:23:26'),
(6, 1, 3, 'fsdsfsd', 'action_complete_2', '2019-06-27 23:23:26', '2019-06-27 23:23:26'),
(7, 1, 2, 'erwrwe', 'action_complete_0', '2019-06-27 23:24:10', '2019-06-27 23:24:10'),
(8, 1, 2, 'rwerwerw', 'action_complete_1', '2019-06-27 23:24:10', '2019-06-27 23:24:10'),
(9, 1, 2, 'werwerwre', 'action_complete_2', '2019-06-27 23:24:10', '2019-06-27 23:24:10');

-- --------------------------------------------------------

--
-- Table structure for table `ala_admin`
--

CREATE TABLE IF NOT EXISTS `ala_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ala_admin`
--

INSERT INTO `ala_admin` (`id`, `first_name`, `last_name`, `email`, `phone`, `password`, `status`) VALUES
(1, 'prajyot', 'prabhu', 'admin@ala.com', '9885888888', '21232f297a57a5a743894a0e4a801fc3', 1);

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
  `photo` varchar(255) NOT NULL DEFAULT 'none.jpg',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `archived` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ala_user`
--

INSERT INTO `ala_user` (`id`, `admin_id`, `first_name`, `last_name`, `email`, `phone`, `password`, `photo`, `status`, `archived`, `created_at`, `updated_at`) VALUES
(1, 0, 'asd', 'asdf', 'admin@bk.com', '9923179374', '47bce5c74f589f4867dbd57e9ca9f808', 'none.jpg', 1, 0, '2019-05-21 06:23:25', '2019-05-21 07:18:23'),
(2, 1, 'prajyot', 'prabhu', 'prajyotpprabhu91@gmail.com', '9922540749', 'ca218f728c1fe21d925f4515bd688011', '1562090605_thumb.jpg', 0, 0, '2019-07-02 23:31:51', '2019-07-02 23:57:29');

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
('0df927ad43dced1ab41786bc01efd51dc5b1e17b', '::1', '', 1562090026, '__ci_last_regenerate|i:1562089716;admin_id|s:1:"1";fname|s:7:"prajyot";lname|s:6:"prabhu";email|s:13:"admin@ala.com";phone|s:10:"9885888888";'),
('2577f13a8bee2ac37832fc3b61ff2fbf1cc98d2a', '::1', '', 1562089428, '__ci_last_regenerate|i:1562089408;admin_id|s:1:"1";fname|s:7:"prajyot";lname|s:6:"prabhu";email|s:13:"admin@ala.com";phone|s:10:"9885888888";'),
('6f279401328adc3919371426a10e4d756e669544', '::1', '', 1562092061, ''),
('85515fb53a30f876873dd6f4e25c39cc596523c9', '::1', '', 1562092126, '__ci_last_regenerate|i:1562092117;'),
('9a0f42c8d75735d57360e4842edbb38da2e60c16', '::1', '', 1562088867, '__ci_last_regenerate|i:1562088867;'),
('d2cadb40ced19c2a1f73290497a1d4132e199a75', '::1', '', 1562090401, '__ci_last_regenerate|i:1562090027;admin_id|s:1:"1";fname|s:7:"prajyot";lname|s:6:"prabhu";email|s:13:"admin@ala.com";phone|s:10:"9885888888";'),
('d985d56afd23f658916a466473c1fae94740e899', '::1', '', 1562091865, '__ci_last_regenerate|i:1562090400;admin_id|s:1:"1";fname|s:7:"prajyot";lname|s:6:"prabhu";email|s:13:"admin@ala.com";phone|s:10:"9885888888";'),
('f4b65e2a37de45105664082fa88fa1b62b46f337', '::1', '', 1562089407, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
