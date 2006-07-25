CREATE TABLE `gamesystem` (
  `id` bigint(20) NOT NULL auto_increment,
  `__createdon` datetime NOT NULL default '0000-00-00 00:00:00',
  `__changedon` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(100) NOT NULL default '',
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`)
);

ALTER TABLE `codex` ADD `gamesystem` BIGINT NOT NULL ;
ALTER TABLE `mission` ADD `gamesystem` BIGINT NOT NULL ;
ALTER TABLE `army` ADD `gamesystem` BIGINT NOT NULL ;
ALTER TABLE `battle` ADD `gamesystem` BIGINT NOT NULL ;

INSERT INTO dbversion(sql_id, sql_subid) VALUES (23, 0);