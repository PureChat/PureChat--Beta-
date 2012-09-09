SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `pc_groups` (
  `id_group` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) NOT NULL,
  `group_type` tinyint(1) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id_group`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `pc_groups` (`id_group`, `group_name`, `group_type`) VALUES
(1, 'Administrators', 1),
(2, 'Moderators', 1),
(3, 'Registered Users', 1),
(4, 'Cool People', 2);

CREATE TABLE IF NOT EXISTS `pc_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `poster` int(11) DEFAULT NULL,
  `text` text,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=315 ;

INSERT INTO `pc_messages` (`id`, `poster`, `text`, `time`) VALUES
(1, 1, 'Welcome to PureChat!', '2012-06-18 23:08:16');;

CREATE TABLE IF NOT EXISTS `pc_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

CREATE TABLE IF NOT EXISTS `pc_reg_activations` (
  `id_user` int(11) NOT NULL,
  `activation_key` varchar(16) NOT NULL,
  UNIQUE KEY `id_user` (`id_user`),
  KEY `activation_key` (`activation_key`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `pc_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting` varchar(25) DEFAULT NULL,
  `value` text,
  `last_updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

INSERT INTO `pc_settings` (`id`, `setting`, `value`, `last_updated`) VALUES
(1, 'theme', 'default', NULL),
(2, 'language', 'english', NULL);

CREATE TABLE IF NOT EXISTS `pc_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `approved` tinyint(1) DEFAULT '1',
  `user_group` int(1) NOT NULL DEFAULT '3',
  `additional_groups` varchar(255) NOT NULL,
  `email` varchar(80) DEFAULT NULL,
  `first_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `display_name` varchar(35) DEFAULT NULL,
  `password` text,
  `password_salt` varchar(9) DEFAULT NULL,
  `avatar` text,
  `status` varchar(15) NOT NULL DEFAULT 'available',
  `total_posts` int(11) DEFAULT NULL,
  `user_ip` varchar(128) DEFAULT NULL,
  `hostname` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

INSERT INTO `pc_users` (`id_user`, `approved`, `user_group`, `additional_groups`, `email`, `first_name`, `last_name`, `display_name`, `password`, `password_salt`, `avatar`, `status`, `total_posts`, `user_ip`, `hostname`) VALUES
(1, 1, 1, '', 'admin@yoursite.com', NULL, NULL, 'Admin', '777a97bd32657dbbb2da51ab0a4ef5f12b4ad129ff932648b4409b0609b9455beb8bc849aa36ef0656ebc41026c7cce3273ce1cba2aa0035f030c0956206a9be', '100_50', NULL, 'available', NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `pc_bans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `user_name` varchar(50) DEFAULT 'guest',
  `user_ip` text,
  `user_hostname` text,
  PRIMARY KEY (`id`)
);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
