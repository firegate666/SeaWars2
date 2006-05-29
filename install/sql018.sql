CREATE TABLE `user` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime default NULL,
  `__changedon` datetime default NULL,
  `login` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `groupid` int(20) NOT NULL default '0',
  `signature` varchar(100) NOT NULL default '',
  `show_email` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `login` (`login`)
);

CREATE TABLE `usergroup` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime default NULL,
  `__changedon` datetime default NULL,
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
);

CREATE TABLE `userrights` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime default NULL,
  `__changedon` datetime default NULL,
  `userright` varchar(100) NOT NULL default '',
  `usergroupid` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

ALTER TABLE `template` ADD `backup` TEXT NOT NULL ;

ALTER TABLE `image` ADD `parent` VARCHAR( 100 ) NOT NULL ,
	ADD `parentid` BIGINT NOT NULL ,
	ADD `size` BIGINT NOT NULL ,
	ADD `type` VARCHAR( 100 ) NOT NULL ,
	ADD `prio` BIGINT NOT NULL ;

ALTER TABLE `image` DROP INDEX `name` ,
ADD UNIQUE `name` ( `name` , `parent` , `parentid` ) 

CREATE TABLE `extendible` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  `parent` varchar(100) NOT NULL default '',
  `parentid` bigint(20) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`parent`,`parentid`,`name`)
);

INSERT INTO dbversion(sql_id, sql_subid) VALUES (18, 0);