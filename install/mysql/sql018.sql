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

-- default user
INSERT INTO `usergroup` (__createdon, __changedon, name) VALUES (
	'2006-03-30 15:49:41',
	'2006-04-07 16:49:02',
	'Administrator'
);

INSERT INTO `user` (__createdon, __changedon, login, email, password, groupid, signature, show_email) VALUES (
	'2006-04-06 18:12:16',
	'2006-05-29 13:09:52',
	'administrator',
	'administrator',
	'b3aca92c793ee0e9b1a9b0a5f5fc044e05140df3',
	1,
	'',
	0
);

INSERT INTO `userrights` (__createdon, __changedon, userright, usergroupid) VALUES ('2006-04-07 16:49:02', '2006-04-07 16:49:02', 'templateadmin', 1);
INSERT INTO `userrights` (__createdon, __changedon, userright, usergroupid) VALUES ('2006-04-07 16:49:02', '2006-04-07 16:49:02', 'useradmin', 1);
INSERT INTO `userrights` (__createdon, __changedon, userright, usergroupid) VALUES ('2006-04-07 16:49:02', '2006-04-07 16:49:02', 'admin', 1);
INSERT INTO `userrights` (__createdon, __changedon, userright, usergroupid) VALUES ('2006-04-07 16:49:02', '2006-04-07 16:49:02', 'configadmin', 1);
INSERT INTO `userrights` (__createdon, __changedon, userright, usergroupid) VALUES ('2006-04-07 16:49:02', '2006-04-07 16:49:02', 'settingsadmin', 1);


INSERT INTO dbversion(sql_id, sql_subid) VALUES (18, 0);