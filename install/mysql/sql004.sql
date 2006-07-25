-- add imagecategory
ALTER TABLE `image` ADD `imagecategoryid` BIGINT;

CREATE TABLE `imagecategory` (
	`id` BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`name` VARCHAR( 100 ) NOT NULL UNIQUE
);