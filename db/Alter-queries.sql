
--vivek-- 11/02/2018 --
-- Added moderate field for photos and videos in user details table
ALTER TABLE `cb_user_details`  ADD `photos_moderate` TINYINT NOT NULL  AFTER `videos`,  ADD `videos_moderate` TINYINT NOT NULL  AFTER `photos_moderate`;
ALTER TABLE `cb_user_details` CHANGE `photos_moderate` `photos_moderate` TINYINT(4) NOT NULL DEFAULT '0', CHANGE `videos_moderate` `videos_moderate` TINYINT(4) NOT NULL DEFAULT '0';

ALTER TABLE `cb_user_notifications`
DROP `trigger`,
DROP `notification_action`,
DROP `notification_type`;ALTER TABLE `cb_user_details` CHANGE `photos_moderate` `photos_moderate` TINYINT(4) NOT NULL DEFAULT '0', CHANGE `videos_moderate` `videos_moderate` TINYINT(4) NOT NULL DEFAULT '0';

--vivek-- 23/02/2018 --
-- Spelling correction experience field
ALTER TABLE `cb_user_details` CHANGE `experiance` `experience` INT(11) NOT NULL