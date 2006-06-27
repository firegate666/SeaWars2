CREATE TABLE `questionaire` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime default NULL,
  `__changedon` datetime default NULL,
  `name` varchar(100) NOT NULL default '',
  `author` varchar(100) NOT NULL default '',
  `email` varchar(200) NOT NULL default '',
  `shortdesc` varchar(200) NOT NULL default '',
  `longdesc` text NOT NULL,
  `published` tinyint(4) NOT NULL default '0',
  `closed` tinyint(4) NOT NULL default '0',
  `layout_main` bigint(20) NOT NULL default '0',
  `layout_end` bigint(20) NOT NULL default '0',
  `layout_question` bigint(20) NOT NULL default '0',
  `layout_question_alt` bigint(20) default NULL,
  `userid` bigint(20) NOT NULL default '0',
  `randompages` tinyint(4) NOT NULL default '0',
  `deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`)
);

CREATE TABLE `questionaireanswers` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime default NULL,
  `__changedon` datetime default NULL,
  `questionanswerid` bigint(20) NOT NULL default '0',
  `questionanswervalue` text NOT NULL,
  `quserid` bigint(20) NOT NULL default '0',
  `confirmed` tinyint(4) NOT NULL default '0',
  `verified` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `questionaireuser` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime default NULL,
  `__changedon` datetime default NULL,
  `email` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `lastquestionaire` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `questionanswer` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime default NULL,
  `__changedon` datetime default NULL,
  `questionid` bigint(20) NOT NULL default '0',
  `answertype` bigint(20) NOT NULL default '0',
  `sortid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `questionanswertype` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime default NULL,
  `__changedon` datetime default NULL,
  `name` varchar(100) default NULL,
  `layout` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

INSERT INTO dbversion(sql_id, sql_subid) VALUES (19, 0);