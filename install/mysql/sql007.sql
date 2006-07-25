--- first work
--- ttentryrohtoff has to be optimized

CREATE TABLE `ttcategory` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
);

CREATE TABLE `ttentry` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL default '',
  `description` text NOT NULL,
  `tttypeid` bigint(20) NOT NULL default '0',
  `imageid` bigint(20) NOT NULL default '0',
  `ttcategoryid` bigint(20) NOT NULL default '0',
  `aufwand` bigint(20) NOT NULL default '100',
  `res1_abs` tinyint(4) NOT NULL default '0',
  `res2_abs` tinyint(4) NOT NULL default '0',
  `res3_abs` tinyint(4) NOT NULL default '0',
  `res4_abs` tinyint(4) NOT NULL default '0',
  `res5_abs` tinyint(4) NOT NULL default '0',
  `res6_abs` tinyint(4) NOT NULL default '0',
  `res7_abs` tinyint(4) NOT NULL default '0',
  `res8_abs` tinyint(4) NOT NULL default '0',
  `res9_abs` tinyint(4) NOT NULL default '0',
  `res10_abs` tinyint(4) NOT NULL default '0',
  `morale_pc` tinyint(4) NOT NULL default '0',
  `money_abs` tinyint(4) NOT NULL default '0',
  `maxpopulation_pc` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
);

CREATE TABLE `ttentrydependson` (
  `id` bigint(20) NOT NULL,
  `entry_id` bigint(20) NOT NULL default '0',
  `dependson_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `entry_id` (`entry_id`,`dependson_id`)
);

CREATE TABLE `ttentryrohstoff` (
  `rohstoffid` bigint(20) NOT NULL default '0',
  `ttentry_resid` tinyint(3) NOT NULL default '0',
  `id` bigint(20) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `rohstoffid` (`rohstoffid`)
);

CREATE TABLE `ttexplored` (
  `id` bigint(20) NOT NULL,
  `spieler_id` bigint(20) NOT NULL default '0',
  `techtree_entry_id` bigint(20) NOT NULL default '0',
  `start` timestamp NOT NULL default '0000-00-00 00:00:00',
  `end` timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `spieler_id` (`spieler_id`,`techtree_entry_id`)
);

CREATE TABLE `tttype` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL default '',
  `beschreibung` text NOT NULL,
  PRIMARY KEY  (`id`)
);