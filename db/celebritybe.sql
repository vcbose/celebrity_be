-- Adminer 4.5.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL DEFAULT '',
  `all_access` tinyint(1) NOT NULL DEFAULT '0',
  `controller` varchar(50) NOT NULL DEFAULT '',
  `date_created` datetime DEFAULT NULL,
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cb_activities`;
CREATE TABLE `cb_activities` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `activity_type` int(11) NOT NULL COMMENT 'interview etc',
  `activity_desc` text NOT NULL,
  `activity_organizer` varchar(255) NOT NULL,
  `activity_contact` text NOT NULL,
  `activity_location` varchar(255) NOT NULL,
  `activity_venue` text NOT NULL,
  `activity_reference_links` int(11) NOT NULL,
  `activity_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cb_notification_map`;
CREATE TABLE `cb_notification_map` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trigger` int(11) NOT NULL COMMENT 'relation from cb_settings tbl notification value setting_type column ',
  `action` int(11) NOT NULL COMMENT 'relation from cb_settings tbl notification value setting_type column',
  `notification_type` int(11) NOT NULL COMMENT 'relation from cb_settings tbl notification value setting_type column',
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_notification_map` (`id`, `trigger`, `action`, `notification_type`, `status`) VALUES
(1,	32,	34,	40,	1),
(2,	33,	34,	40,	1),
(3,	33,	35,	37,	1),
(4,	33,	36,	38,	1),
(5,	86,	34,	39,	1),
(6,	86,	35,	37,	1);

DROP TABLE IF EXISTS `cb_offers`;
CREATE TABLE `cb_offers` (
  `offer_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(18) NOT NULL,
  `discount` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `added_on` datetime NOT NULL,
  `active_on` datetime NOT NULL,
  `expiry_on` datetime NOT NULL,
  `code_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cb_plans`;
CREATE TABLE `cb_plans` (
  `plan_id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(55) NOT NULL,
  `plan_desc` text NOT NULL,
  `plan_duration` int(11) NOT NULL,
  `plan_duration_in` enum('day','month','years') NOT NULL,
  `plan_price` decimal(10,0) NOT NULL,
  `plan_offer` int(11) NOT NULL,
  `plan_added_on` datetime NOT NULL,
  `plan_modified_on` datetime NOT NULL,
  `plan_modified_by` int(11) NOT NULL,
  `plan_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_plans` (`plan_id`, `plan_name`, `plan_desc`, `plan_duration`, `plan_duration_in`, `plan_price`, `plan_offer`, `plan_added_on`, `plan_modified_on`, `plan_modified_by`, `plan_status`) VALUES
(1,	'Basic',	'This is the basic plan of celebritybe',	0,	'',	0,	0,	'2017-11-21 00:00:00',	'2018-01-16 00:00:00',	1,	1),
(2,	'Premium',	'',	1,	'years',	49,	0,	'2018-01-27 10:24:17',	'2018-01-27 10:24:17',	1,	1),
(3,	'Normal',	'',	6,	'month',	29,	0,	'2018-01-27 10:24:17',	'2018-01-27 10:24:17',	1,	1);

DROP TABLE IF EXISTS `cb_plan_meta`;
CREATE TABLE `cb_plan_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plan_id` int(11) NOT NULL,
  `feature_type` int(11) NOT NULL COMMENT 'id from cb_setting',
  `feature_value` text NOT NULL COMMENT 'Settings default value will override here',
  `addded_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `feature_status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_plan_meta` (`id`, `plan_id`, `feature_type`, `feature_value`, `addded_on`, `modified_on`, `feature_status`) VALUES
(1,	1,	41,	'1',	'2018-01-27 10:32:09',	'2018-01-27 10:32:09',	1),
(2,	1,	42,	'1',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(3,	1,	55,	'0',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(4,	1,	56,	'0',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(5,	2,	41,	'5',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(6,	2,	42,	'5',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(7,	2,	55,	'1',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(8,	2,	56,	'1',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(9,	3,	41,	'2',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(10,	3,	42,	'2',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(11,	3,	55,	'0',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(12,	3,	56,	'1',	'2018-01-27 10:34:36',	'2018-01-27 10:34:36',	1),
(13,	2,	87,	'1',	'2018-02-03 18:48:09',	'2018-02-03 18:48:09',	1),
(14,	2,	88,	'1',	'2018-02-03 18:48:16',	'2018-02-03 18:48:16',	1),
(15,	2,	89,	'1',	'2018-02-03 18:48:26',	'2018-02-03 18:48:26',	1),
(16,	3,	87,	'0',	'2018-02-04 20:09:50',	'2018-02-04 20:09:50',	1),
(17,	3,	88,	'1',	'2018-02-04 20:10:03',	'2018-02-04 20:10:03',	1),
(18,	3,	89,	'1',	'2018-02-04 20:10:12',	'2018-02-04 20:10:12',	1),
(19,	1,	87,	'0',	'2018-02-04 20:10:44',	'2018-02-04 20:10:44',	1),
(20,	1,	88,	'0',	'2018-02-04 20:11:15',	'2018-02-04 20:11:15',	1),
(21,	1,	89,	'0',	'2018-02-04 20:11:38',	'2018-02-04 20:11:38',	0);

DROP TABLE IF EXISTS `cb_profile_visits`;
CREATE TABLE `cb_profile_visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `visited_profile_id` int(11) NOT NULL,
  `visited_on` datetime NOT NULL,
  `no_of_visits` int(11) NOT NULL,
  `last_visit_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `interest_status` tinyint(4) NOT NULL,
  `interested_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cb_profile_visits_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `cb_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `cb_setting`;
CREATE TABLE `cb_setting` (
  `setting_id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_type` varchar(255) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `added_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `setting_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`setting_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_setting` (`setting_id`, `setting_type`, `setting_name`, `setting_value`, `added_on`, `modified_on`, `setting_status`) VALUES
(1,	'subscription_type',	'free',	'',	'2017-11-26 17:24:15',	'2017-11-26 17:24:15',	0),
(2,	'subscription_type',	'paid',	'',	'2017-11-26 17:24:55',	'2017-11-26 17:24:55',	0),
(3,	'subscription_type',	'offer',	'',	'2017-11-26 17:24:55',	'2017-11-26 17:24:55',	0),
(4,	'talents_category',	'Actor',	'',	'2017-12-03 16:29:58',	'2017-12-03 16:29:58',	1),
(5,	'talents_category',	'Actress',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(6,	'talents_category',	'Models',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(7,	'talents_category',	'Singer',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(8,	'talents_category',	'Music Director',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(9,	'talents_category',	'Lyricist',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(10,	'talents_category',	'Director',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(11,	'talents_category',	'Cinematographer',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(12,	'talents_category',	'Photographer',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(13,	'talents_category',	'Editor',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(14,	'talents_category',	'DI / Effects',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(15,	'talents_category',	'Foley Artist',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(16,	'talents_category',	'Script Writer',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(17,	'talents_category',	'Story',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(18,	'talents_category',	'TV Show',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(19,	'talents_category',	'TV Artist',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(20,	'talents_category',	'Art Director',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(21,	'talents_category',	'Dancers',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(22,	'talents_category',	'Dance Masters',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(23,	'talents_category',	'Stunt Masters',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(24,	'talents_category',	'Dubbing Artists',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(25,	'talents_category',	'Poster Designer',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(26,	'talents_category',	'Events Anchor',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(27,	'talents_category',	'Makeup Man',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(28,	'talents_category',	'Costume Designer',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(29,	'talents_category',	'Hair Stylist',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(30,	'talents_category',	'PRO',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(31,	'talents_category',	'Movie Promoters',	'',	'2017-12-03 16:38:55',	'2017-12-03 16:38:55',	1),
(32,	'notification',	'trigger',	'89',	'2018-01-06 19:15:06',	'2018-01-06 19:15:06',	1),
(33,	'notification',	'trigger',	'87',	'2018-01-06 19:15:22',	'2018-01-06 19:15:22',	1),
(34,	'notification',	'action',	'added',	'2018-01-06 19:22:50',	'2018-01-06 19:22:50',	1),
(35,	'notification',	'action',	'edited',	'2018-01-06 19:23:31',	'2018-01-06 19:23:31',	1),
(36,	'notification',	'action',	'deleted',	'2018-01-06 19:25:36',	'2018-01-06 19:25:36',	1),
(37,	'notification',	'notification_type',	'alert',	'2018-01-06 19:28:03',	'2018-01-06 19:28:03',	1),
(38,	'notification',	'notification_type',	'warning',	'2018-01-06 19:28:03',	'2018-01-06 19:28:03',	1),
(39,	'notification',	'notification_type',	'info',	'2018-01-06 19:28:03',	'2018-01-06 19:28:03',	1),
(40,	'notification',	'notification_type',	'greetings',	'2018-01-06 19:28:03',	'2018-01-06 19:28:03',	1),
(41,	'feature_types',	'Images',	'1',	'2018-01-16 00:00:00',	'2018-01-16 00:00:00',	1),
(42,	'feature_types',	'Videos',	'1',	'2018-01-16 00:00:00',	'2018-01-16 00:00:00',	1),
(43,	'hair_colour',	'black',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(44,	'hair_colour',	'brown',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(45,	'hair_colour',	'white',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(46,	'body_colour',	'fair',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(47,	'body_colour',	'dark',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(48,	'body_colour',	'normal',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(49,	'eye_colour',	'black',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(50,	'eye_colour',	'brown',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(51,	'eye_colour',	'blue',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(52,	'body_type',	'faty',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(53,	'body_type',	'skinny',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(54,	'body_type',	'fit',	'',	'0000-00-00 00:00:00',	'0000-00-00 00:00:00',	0),
(55,	'feature_types',	'Highlight',	'1',	'2018-01-27 10:27:52',	'2018-01-27 10:27:52',	1),
(56,	'feature_types',	'Chat',	'1',	'2018-01-27 10:28:08',	'2018-01-27 10:28:08',	0),
(57,	'state',	'Andhra Pradesh',	'',	'2018-01-27 13:46:23',	'2018-01-27 13:46:23',	1),
(58,	'state',	'Arunachal Pradesh',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(59,	'state',	'Assam',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(60,	'state',	'Bihar',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(61,	'state',	'Goa',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(62,	'state',	'Gujarat',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(63,	'state',	'Haryana',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(64,	'state',	'Himachal Pradesh',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(65,	'state',	'Jammu & Kashmir',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(66,	'state',	'Karnataka',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(67,	'state',	'Kerala',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(68,	'state',	'Madhya Pradesh',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(69,	'state',	'Maharashtra',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(70,	'state',	'Manipur',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(71,	'state',	'Meghalaya',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(72,	'state',	'Mizoram',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(73,	'state',	'Nagaland',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(74,	'state',	'Orissa',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(75,	'state',	'Punjab',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(76,	'state',	'Rajasthan',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(77,	'state',	'Sikkim',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(78,	'state',	'Tamil Nadu',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(79,	'state',	'Tripura',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(80,	'state',	'Uttar Pradesh',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(81,	'state',	'West Bengal',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(82,	'state',	'Chhattisgarh',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(83,	'state',	'Uttarakhand',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(84,	'state',	'Jharkhand',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(85,	'state',	'Telangana',	'',	'2018-01-27 13:47:49',	'2018-01-27 13:47:49',	1),
(86,	'notification',	'trigger',	'88',	'2018-02-03 11:27:07',	'2018-02-03 11:27:07',	1),
(87,	'feature_types',	'Receive_interests',	'1',	'2018-02-03 15:57:29',	'2018-02-03 15:57:29',	1),
(88,	'feature_types',	'Receive_interview',	'1',	'2018-02-03 15:57:55',	'2018-02-03 15:57:55',	1),
(89,	'feature_types',	'Receive_visits',	'1',	'2018-02-03 16:22:06',	'2018-02-03 16:22:06',	1);

DROP TABLE IF EXISTS `cb_subscriptions`;
CREATE TABLE `cb_subscriptions` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `purchased_on` datetime NOT NULL,
  `started_on` datetime NOT NULL,
  `ends_on` datetime NOT NULL,
  `subscription_type` int(11) NOT NULL COMMENT 'this value is based on offers, values from configs config_type subscription_type',
  `payment_method` int(11) NOT NULL,
  `subscription_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`subscription_id`),
  KEY `user_id` (`user_id`),
  KEY `plan_id` (`plan_id`),
  CONSTRAINT `cb_subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `cb_users` (`user_id`),
  CONSTRAINT `cb_subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `cb_plans` (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_subscriptions` (`subscription_id`, `user_id`, `plan_id`, `purchased_on`, `started_on`, `ends_on`, `subscription_type`, `payment_method`, `subscription_status`) VALUES
(1,	2,	1,	'2018-01-27 10:36:06',	'2018-01-27 10:36:06',	'0000-00-00 00:00:00',	1,	1,	0),
(2,	2,	2,	'2018-01-27 10:37:01',	'2018-01-27 10:37:01',	'0000-00-00 00:00:00',	2,	1,	1),
(3,	4,	3,	'2018-01-27 08:11:08',	'2018-01-27 08:11:08',	'1970-01-01 08:11:08',	0,	0,	1),
(4,	5,	2,	'2018-01-27 08:24:42',	'2018-01-27 08:24:42',	'1970-01-01 08:24:42',	0,	0,	1),
(5,	6,	3,	'2018-01-27 08:27:53',	'2018-01-27 08:27:53',	'2018-07-27 08:27:53',	0,	0,	1);

DROP TABLE IF EXISTS `cb_users`;
CREATE TABLE `cb_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(55) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` int(11) NOT NULL COMMENT 'Specify the type user',
  `created_on` datetime NOT NULL,
  `login_attempts` int(11) NOT NULL,
  `ip_address` varchar(55) NOT NULL,
  `browser_type` varchar(255) NOT NULL,
  `is_deleted` tinyint(4) NOT NULL,
  `user_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_users` (`user_id`, `user_name`, `password`, `user_type`, `created_on`, `login_attempts`, `ip_address`, `browser_type`, `is_deleted`, `user_status`) VALUES
(1,	'admin',	'$2y$10$zpVO0/XW2SkWsVV29.9UEu0yBoK9jCtTfPYIPNbHFRqzFpitI6Gt.',	1,	'2018-01-27 09:38:10',	0,	'',	'',	0,	1),
(2,	'vcbose',	'$2y$10$scKoD3RnxJBFk5OtG/Wlhubc0ugkPH0LkWHlCFXDgvIzc4Jgg9vaa',	3,	'2018-01-27 10:14:10',	0,	'',	'',	0,	1),
(3,	'vivek',	'$2y$10$grDYN4SnlnckhvMl3VpB2O/ppww1mfR1sk1/LaQwJ5NFkuIlkHUYe',	2,	'2018-01-27 10:40:26',	0,	'',	'',	0,	1),
(4,	'vipuld',	'$2y$10$XRKgd60hUNb9WrPuEnjkNONt.Rtk6TqM18FKeSfqMzVqdWZCb/J4O',	3,	'2018-01-27 10:40:26',	0,	'',	'',	0,	0),
(5,	'jina',	'$2y$10$ZQsY02qeDFzti9ctfW4jv.wS2emMFBRTRERw7AiKIddbOmHFKNH3a',	3,	'2018-01-27 10:40:26',	0,	'',	'',	0,	0),
(6,	'reema',	'$2y$10$tW/nnwu8KLPSSuC7n8/MuO975jq0vcTUhiZyYO5jb39aNTXzmWyqS',	3,	'2018-01-27 10:40:26',	0,	'',	'',	0,	0);

DROP TABLE IF EXISTS `cb_user_chats`;
CREATE TABLE `cb_user_chats` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_from` int(11) NOT NULL,
  `chat_to` int(11) NOT NULL,
  `chat_text` text NOT NULL COMMENT 'encrypted data',
  `chat_lock` tinyint(4) NOT NULL,
  `chat_on` datetime NOT NULL,
  `chat_read_on` datetime NOT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_user_chats` (`chat_id`, `chat_from`, `chat_to`, `chat_text`, `chat_lock`, `chat_on`, `chat_read_on`) VALUES
(1,	3,	4,	'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',	0,	'2018-01-28 17:19:32',	'0000-00-00 00:00:00'),
(2,	3,	4,	'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',	0,	'2018-01-28 17:22:17',	'0000-00-00 00:00:00'),
(3,	3,	4,	'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat',	0,	'2018-01-28 18:02:31',	'0000-00-00 00:00:00'),
(4,	3,	4,	'Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.',	0,	'2018-01-28 18:02:58',	'0000-00-00 00:00:00'),
(5,	3,	4,	'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',	0,	'2018-01-28 18:19:37',	'0000-00-00 00:00:00'),
(6,	3,	4,	'Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',	0,	'2018-01-28 18:23:03',	'0000-00-00 00:00:00'),
(7,	3,	4,	'ping me when you are free',	0,	'2018-01-28 18:35:15',	'0000-00-00 00:00:00'),
(8,	3,	4,	'Ok',	0,	'2018-01-28 18:36:35',	'0000-00-00 00:00:00'),
(9,	3,	4,	'okokok',	0,	'2018-01-28 18:36:55',	'0000-00-00 00:00:00');

DROP TABLE IF EXISTS `cb_user_details`;
CREATE TABLE `cb_user_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(55) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `nationality` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `city` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(55) NOT NULL,
  `mobile` varchar(55) NOT NULL,
  `email` varchar(255) NOT NULL,
  `associations` varchar(255) NOT NULL COMMENT 'this will hold the different film industries in india',
  `talent_category` varchar(55) NOT NULL COMMENT 'type of talent',
  `description` text NOT NULL COMMENT 'profile notes',
  `tags_interest` text NOT NULL,
  `photos` text NOT NULL COMMENT 'photo links',
  `videos` text NOT NULL COMMENT 'video links',
  `links` text NOT NULL COMMENT 'external reference links',
  `experiance` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cb_user_details_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `cb_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_user_details` (`id`, `user_id`, `first_name`, `middle_name`, `last_name`, `display_name`, `dob`, `gender`, `nationality`, `state`, `city`, `location`, `address`, `phone`, `mobile`, `email`, `associations`, `talent_category`, `description`, `tags_interest`, `photos`, `videos`, `links`, `experiance`, `subscription_id`, `modified_on`, `modified_by`) VALUES
(1,	1,	'celebritybe',	'',	'admin',	'',	'',	'',	0,	0,	'0',	'',	'',	'',	'',	'',	'',	'0',	'',	'',	'',	'',	'',	0,	0,	'2018-01-27 09:39:29',	'2018-01-27 09:39:29'),
(3,	2,	'Vishnu',	'C',	'Bose',	'vcbose',	'1990-12-03',	'male',	1,	1,	'1',	'vennala',	'Revathy 65, EVRA vennala P.O Kochi',	'9048809094',	'9048809094',	'vishnuc.bose@yahoo.in',	'',	'0',	'Ask Different is a question and answer site for power users of Apple hardware and software. Join them; it only takes a minute',	'indiab,radio mango, vj, rj',	'',	'',	'',	0,	0,	'2018-01-27 10:20:17',	'2018-01-27 10:20:17'),
(4,	3,	'Vivek',	'',	'PP',	'vivek',	'1990-12-03',	'male',	1,	1,	'1',	'kaloor',	'Sample addres,Kaloor,Kochi',	'9048809095',	'9048809095',	'vivek@gmail.com',	'',	'10',	'Ask Different is a question and answer site for power users of Apple hardware and software. Join them; it only takes a minute',	'film making, short films, photography',	'',	'',	'',	5,	0,	'2018-01-27 10:42:50',	'2018-01-27 10:42:50'),
(5,	4,	'Vipul',	'',	'D',	'Vipul  D',	'1992-01-01',	'male',	0,	0,	'0',	'',	'Sample address,\r\nKaloor, Kochi',	'',	'9856122356',	'vipuld@yahoo.in',	'',	'4,5',	'Lorem ipsum dolor sit amet, cu omnesque principes has. Te posse adipisci interesset nam, facete iracundia qui eu. In usu case liber conceptam. Cu modus lucilius accusata usu. Nam epicuri periculis eu.',	'',	'[\"download_1517037067.png\"]',	'[\"\"]',	'',	0,	0,	'2018-01-27 08:11:00',	'0000-00-00 00:00:00'),
(6,	5,	'Jina',	'',	'Hosh',	'Jina  Hosh',	'1985-01-01',	'female',	0,	0,	'0',	'',	'Lorem ipsum dolor sit amet, cu omnesque principes has. Te posse adipisci interesset nam, facete iracundia qui eu. In usu case liber conceptam. Cu modus lucilius accusata usu. Nam epicuri periculis eu.',	'',	'9856122356',	'jina95@gmail.com',	'',	'5,6',	'Lorem ipsum dolor sit amet, cu omnesque principes has. Te posse adipisci interesset nam, facete iracundia qui eu. In usu case liber conceptam. Cu modus lucilius accusata usu. Nam epicuri periculis eu.',	'',	'[\"download (1)_1517037882.png\"]',	'[\"https:\\/\\/youtu.be\\/VJHaIJPf-ss\"]',	'',	0,	0,	'2018-01-27 08:24:00',	'0000-00-00 00:00:00'),
(7,	6,	'Reema',	'',	'K',	'Reema  K',	'2018-12-25',	'female',	0,	0,	'0',	'',	'Lorem ipsum dolor sit amet, cu omnesque principes has. Te posse adipisci interesset nam, facete iracundia qui eu. In usu case liber conceptam. Cu modus lucilius accusata usu. Nam epicuri periculis eu.',	'',	'9856122356',	'jina95@gmail.com',	'',	'5,6',	'Lorem ipsum dolor sit amet, cu omnesque principes has. Te posse adipisci interesset nam, facete iracundia qui eu. In usu case liber conceptam. Cu modus lucilius accusata usu. Nam epicuri periculis eu.',	'',	'[\"download (1)_1517038073.png\"]',	'[\"https:\\/\\/youtu.be\\/VJHaIJPf-ss\"]',	'',	0,	0,	'2018-01-27 08:27:00',	'0000-00-00 00:00:00');

DROP TABLE IF EXISTS `cb_user_details_meta`;
CREATE TABLE `cb_user_details_meta` (
  `meta_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `body_type` int(11) NOT NULL COMMENT 'value from settings tbl',
  `colour` int(11) NOT NULL COMMENT 'value from settings tbl',
  `eye` int(11) NOT NULL COMMENT 'value from settings tbl',
  `hair` int(11) NOT NULL COMMENT 'value from settings tbl',
  `worked_movies` int(11) NOT NULL,
  `worked_brands` int(11) NOT NULL,
  `education` int(11) NOT NULL,
  `other_skills` int(11) NOT NULL,
  PRIMARY KEY (`meta_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cb_user_details_meta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `cb_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_user_details_meta` (`meta_id`, `user_id`, `height`, `weight`, `body_type`, `colour`, `eye`, `hair`, `worked_movies`, `worked_brands`, `education`, `other_skills`) VALUES
(1,	2,	175,	78,	52,	48,	49,	43,	0,	0,	0,	0),
(2,	4,	155,	60,	53,	47,	49,	43,	0,	0,	0,	0),
(3,	5,	0,	0,	0,	46,	50,	44,	0,	0,	0,	0),
(4,	6,	0,	0,	0,	46,	50,	44,	0,	0,	0,	0);

DROP TABLE IF EXISTS `cb_user_interview`;
CREATE TABLE `cb_user_interview` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `intrw_subject` varchar(255) NOT NULL,
  `intrw_location` varchar(100) NOT NULL,
  `intrw_description` text NOT NULL,
  `intrw_on` datetime NOT NULL,
  `intrw_due` datetime NOT NULL,
  `oganizer_name` varchar(55) NOT NULL,
  `oganizer_contact` decimal(10,0) NOT NULL,
  `oganizer_mail` varchar(255) NOT NULL,
  `oganizer_website` varchar(255) NOT NULL,
  `added_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  `intrw_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cb_user_interview_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `cb_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_user_interview` (`id`, `user_id`, `intrw_subject`, `intrw_location`, `intrw_description`, `intrw_on`, `intrw_due`, `oganizer_name`, `oganizer_contact`, `oganizer_mail`, `oganizer_website`, `added_on`, `modified_on`, `intrw_status`) VALUES
(3,	3,	'Atkes Jewellery',	'Lulumall',	'CodeIgniter gives you access to a Query Builder class. This pattern allows information to be retrieved, inserted, and updated in your database with minimal scriptingsafsaf',	'2018-02-03 22:41:27',	'2018-02-03 22:41:27',	'fsafas',	0,	'',	'fsfs',	'2018-02-03 18:09:35',	'2018-02-03 22:41:27',	1),
(4,	3,	'Actor Hunt',	'Lulumall',	'CodeIgniter gives you access to a Query Builder class. This pattern allows information to be retrieved, inserted, and updated in your database with minimal scripting',	'2018-03-31 15:55:00',	'0000-00-00 00:00:00',	'Sam',	0,	'',	'',	'2018-02-03 21:10:40',	'2018-02-04 13:53:32',	1),
(5,	3,	'Model fo popy',	'Oberon',	'CodeIgniter gives you access to a Query Builder class. This pattern allows information to be retrieved, inserted, and updated in your database with minimal scripting',	'2018-02-04 14:41:22',	'2018-02-04 14:41:22',	'Sam',	0,	'98456322333',	'',	'2018-02-04 06:51:16',	'2018-02-04 06:51:16',	1),
(7,	3,	'Lenskart model',	'Lulumal Second floor ',	'Lenskart want a new model',	'2018-02-24 18:55:00',	'0000-00-00 00:00:00',	'Lenskart',	9884563333,	'',	'',	'2018-02-04 13:40:38',	'2018-02-04 13:45:40',	1);

DROP TABLE IF EXISTS `cb_user_meta`;
CREATE TABLE `cb_user_meta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `meta_name` varchar(255) NOT NULL,
  `meta_value` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cb_user_meta_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `cb_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_user_meta` (`id`, `user_id`, `meta_name`, `meta_value`) VALUES
(1,	4,	'talent',	4),
(2,	4,	'talent',	6),
(3,	5,	'talent',	5),
(4,	5,	'talent',	6),
(5,	6,	'talent',	5),
(6,	6,	'talent',	6);

DROP TABLE IF EXISTS `cb_user_notifications`;
CREATE TABLE `cb_user_notifications` (
  `notify_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `triggerd_from` int(11) NOT NULL COMMENT 'user level user_id, reference from  cb_profile_visits ids, system etc',
  `map_id` int(11) NOT NULL COMMENT 'cb_notification_map tbl id',
  `trigger` int(11) NOT NULL COMMENT 'user level visited, interested will keep this values in settings table',
  `notification_action` int(11) NOT NULL COMMENT 'user level add, edit',
  `notification_type` int(11) NOT NULL COMMENT 'user level alert, greetings, info',
  `notification_on` datetime NOT NULL,
  `notification_relation` int(11) NOT NULL DEFAULT '0',
  `notification_note` text NOT NULL COMMENT 'If user levl alter then fill this column value',
  `notification_status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`notify_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `cb_user_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `cb_users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_user_notifications` (`notify_id`, `user_id`, `triggerd_from`, `map_id`, `trigger`, `notification_action`, `notification_type`, `notification_on`, `notification_relation`, `notification_note`, `notification_status`) VALUES
(17,	2,	3,	1,	0,	0,	0,	'2018-02-05 15:28:07',	0,	'{\"visits\":102}',	1),
(19,	2,	3,	2,	0,	0,	0,	'2018-02-03 15:15:40',	0,	'',	1),
(29,	5,	3,	1,	0,	0,	0,	'2018-02-04 18:56:46',	0,	'{\"visits\":75}',	1),
(30,	5,	3,	2,	0,	0,	0,	'2018-02-04 06:50:51',	0,	'',	1),
(32,	6,	3,	1,	0,	0,	0,	'2018-02-04 09:26:16',	0,	'{\"visits\":37}',	1),
(33,	2,	3,	5,	0,	0,	0,	'2018-02-04 13:53:32',	4,	'',	1),
(34,	4,	3,	1,	0,	0,	0,	'2018-02-04 15:51:55',	0,	'{\"visits\":6}',	1),
(37,	4,	3,	2,	0,	0,	0,	'2018-02-04 15:52:01',	0,	'',	0);

DROP TABLE IF EXISTS `cb_user_roles`;
CREATE TABLE `cb_user_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(55) NOT NULL,
  `role_status` tinyint(4) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_user_roles` (`role_id`, `role_name`, `role_status`) VALUES
(1,	'admin',	1),
(2,	'director',	1),
(3,	'talent',	1);

DROP TABLE IF EXISTS `cb_user_roles_permissions`;
CREATE TABLE `cb_user_roles_permissions` (
  `perm_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `resource` int(11) NOT NULL,
  `action` int(11) NOT NULL,
  `permission` tinyint(4) NOT NULL,
  `added_on` datetime NOT NULL,
  PRIMARY KEY (`perm_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `cb_user_roles_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `cb_user_roles` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cb_user_roles_permissions` (`perm_id`, `role_id`, `resource`, `action`, `permission`, `added_on`) VALUES
(1,	1,	1,	1,	1,	'2018-01-27 09:40:23');

DROP TABLE IF EXISTS `cd_user_chats`;
CREATE TABLE `cd_user_chats` (
  `chat_id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_from` int(11) NOT NULL,
  `chat_to` int(11) NOT NULL,
  `chat_text` text NOT NULL COMMENT 'encrypted data',
  `chat_on` datetime NOT NULL,
  `chat_read_on` datetime NOT NULL,
  PRIMARY KEY (`chat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `keys`;
CREATE TABLE `keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `keys` (`id`, `user_id`, `key`, `level`, `ignore_limits`, `is_private_key`, `ip_addresses`, `date_created`) VALUES
(1,	3,	'36763dc79968b808f8f8405e15316fc4',	0,	0,	0,	NULL,	0),
(2,	1,	'1ea798a0fb408884b1540a5c516a97c5',	0,	0,	0,	NULL,	0);

DROP TABLE IF EXISTS `limits`;
CREATE TABLE `limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `count` int(10) NOT NULL,
  `hour_started` int(11) NOT NULL,
  `api_key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2018-02-06 14:36:14
