
--vivek-- 11/02/2018 --
-- Added moderate field for photos and videos in user details table
ALTER TABLE `cb_user_details`  ADD `photos_moderate` TINYINT NOT NULL  AFTER `videos`,  ADD `videos_moderate` TINYINT NOT NULL  AFTER `photos_moderate`;
ALTER TABLE `cb_user_details` CHANGE `photos_moderate` `photos_moderate` TINYINT(4) NOT NULL DEFAULT '0', CHANGE `videos_moderate` `videos_moderate` TINYINT(4) NOT NULL DEFAULT '0';

ALTER TABLE `cb_user_notifications`
DROP `trigger`,
DROP `notification_action`,
DROP `notification_type`;

ALTER TABLE `cb_user_details` CHANGE `photos_moderate` `photos_moderate` TINYINT(4) NOT NULL DEFAULT '0', CHANGE `videos_moderate` `videos_moderate` TINYINT(4) NOT NULL DEFAULT '0';

--vivek-- 23/02/2018 --
-- Spelling correction experience field
ALTER TABLE `cb_user_details` CHANGE `experiance` `experience` INT(11) NOT NULL

--vivek-- 28/02/2018 --
-- cb_user_medias table create query --
CREATE TABLE `cb_user_medias` (
 `media_id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `media_type` int(11) NOT NULL COMMENT '1: image, 2 : video',
 `media_name` varchar(255) NOT NULL,
 `moderate_status` int(11) NOT NULL DEFAULT '0',
 `status` tinyint(4) NOT NULL DEFAULT '1',
 PRIMARY KEY (`media_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8

--vcb-- 04/03/2018 --
-- cb_user_medias table create query --
ALTER TABLE `cb_user_medias`
ADD `uploaded_on` datetime NOT NULL AFTER `media_name`,
ADD `moderate_on` datetime NOT NULL AFTER `moderate_status`;

ALTER TABLE `cb_user_medias`
ADD `dp` tinyint NOT NULL AFTER `media_name`;

ALTER TABLE `cb_user_medias`
ADD `modified_on` datetime NOT NULL AFTER `uploaded_on`;

--vcb-- 05/03/2018 --
-- cb_user_medias table create query --
ALTER TABLE `cb_user_medias`
ADD `in_plan` int(11) NOT NULL AFTER `modified_on`;

--vivek-- 07/03/2018 --
-- cb_user_medias table added dp_image field --
ALTER TABLE `cb_user_medias`  ADD `dp_image` TINYINT NOT NULL DEFAULT '0'  AFTER `media_name`;

