CREATE TABLE `pagestatistic` (
  `id` bigint(20) NOT NULL auto_increment,
  `template` varchar(200)  NOT NULL,
  `user` bigint(20) default NULL,
  `timestamp` timestamp NOT NULL,
  `ip` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1 ;