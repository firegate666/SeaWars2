-- RELEASE of Module
-- W40K

CREATE TABLE `army` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  `userid` bigint(20) NOT NULL default '0',
  `commander` varchar(100) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `codex` bigint(20) NOT NULL default '0',
  `comment` text NOT NULL,
  `points` int(11) default NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `battle` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(100) NOT NULL default '',
  `comment` text NOT NULL,
  `points` int(11) default NULL,
  `player1` bigint(20) NOT NULL default '0',
  `player2` bigint(20) NOT NULL default '0',
  `winner` tinyint(4) NOT NULL default '0',
  `mission` bigint(20) NOT NULL default '0',
  `day` int(11) NOT NULL default '0',
  `month` int(11) NOT NULL default '0',
  `year` int(11) NOT NULL default '0',
  `vp1` int(11) NOT NULL default '0',
  `vp2` int(11) NOT NULL default '0',
  `userid` bigint(20) NOT NULL default '0',
  `battletypeid` bigint(20) NOT NULL default '0',
  `impdate` varchar(20) NOT NULL default '',
  `realdate` date NOT NULL default '0000-00-00',
  `multibattle` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `multibattle` (`multibattle`)
);

CREATE TABLE `battletype` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(100) NOT NULL default '',
  `comment` text NOT NULL,
  `parent` bigint(20) NOT NULL default '0',
  `sortfirst` varchar(10) NOT NULL default '',
  `sortsecond` varchar(10) NOT NULL default '',
  `sortthird` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `codex` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(100) NOT NULL default '',
  `comment` text NOT NULL,
  `commander` varchar(100) NOT NULL default '',
  `founding` varchar(100) NOT NULL default '',
  `homeplanet` varchar(100) NOT NULL default '',
  `followers` text NOT NULL,
  `colors` text NOT NULL,
  `specialized` text NOT NULL,
  `favunit` varchar(100) NOT NULL default '',
  `favweapon` varchar(100) NOT NULL default '',
  `reputation` text NOT NULL,
  `actstrength` text NOT NULL,
  `archenemy` varchar(100) NOT NULL default '',
  `groupname` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `mission` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(100) NOT NULL default '',
  `comment` text NOT NULL,
  `category` text NOT NULL,
  `ruleset` text NOT NULL,
  `rounds` varchar(100) NOT NULL default '0',
  `source` text NOT NULL,
  PRIMARY KEY  (`id`)
);

CREATE TABLE `multibattle` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
);

CREATE TABLE `multibattlearmy` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  `multibattle` bigint(20) NOT NULL default '0',
  `army_id` bigint(20) NOT NULL default '0',
  `player` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `multibattle` (`multibattle`)
);

INSERT INTO dbversion(sql_id, sql_subid) VALUES (22, 0);