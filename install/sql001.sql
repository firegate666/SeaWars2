-- Erstinstalltion
-- grundlegende Datenbankstruktur

CREATE TABLE `_parameter` (
  `name` varchar(100) NOT NULL default '',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`name`)
) TYPE=MyISAM;

INSERT INTO `_parameter` VALUES ('spielgeschwindigkeit', '1');
INSERT INTO `_parameter` VALUES ('rohstoffwachstum', '1');

CREATE TABLE `allianz` (
  `id` bigint(20) NOT NULL auto_increment,
  `tag` varchar(10) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `kurzbeschreibung` text NOT NULL,
  `beschreibung` longtext NOT NULL,
  `nimmtauf` tinyint(4) NOT NULL default '1',
  `bildurl` varchar(200) default NULL,
  `leader_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`tag`,`name`)
) TYPE=MyISAM;

CREATE TABLE `archipel` (
  `id` bigint(20) NOT NULL default '0',
  `x_pos` bigint(20) NOT NULL default '0',
  `y_pos` bigint(20) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `kartenabschnitt_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `x_pos` (`x_pos`,`y_pos`,`kartenabschnitt_id`)
) TYPE=MyISAM;

CREATE TABLE `bauplan` (
  `id` bigint(20) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `bauplan_enthaelt` (
  `bauplan_id` bigint(20) NOT NULL default '0',
  `tech_id` bigint(20) NOT NULL default '0',
  `anzahl` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`bauplan_id`,`tech_id`)
) TYPE=MyISAM;

CREATE TABLE `flotte` (
  `id` bigint(20) NOT NULL auto_increment,
  `auftrag` bigint(20) NOT NULL default '0',
  `x_pos` bigint(20) NOT NULL default '0',
  `y_pos` bigint(20) NOT NULL default '0',
  `ankunft` timestamp NOT NULL,
  `timestamp` timestamp NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `hatbuendnissmit` (
  `antragsteller_id` bigint(20) NOT NULL default '0',
  `antragempfaenger_id` bigint(20) NOT NULL default '0',
  `typ` tinyint(4) NOT NULL default '0',
  `commit` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`antragsteller_id`,`antragempfaenger_id`)
) TYPE=MyISAM;

CREATE TABLE `hatquest` (
  `quest_id` bigint(20) NOT NULL default '0',
  `spieler_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`quest_id`,`spieler_id`)
) TYPE=MyISAM;

CREATE TABLE `image` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `url` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) TYPE=MyISAM;

CREATE TABLE `insel` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `groesse` int(11) NOT NULL default '0',
  `x_pos` bigint(20) NOT NULL default '0',
  `y_pos` bigint(20) NOT NULL default '0',
  `spieler_id` bigint(20) NOT NULL default '0',
  `archipel_id` bigint(20) NOT NULL default '0',
  `timestamp` timestamp NOT NULL,
  `lager_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `lager_id` (`lager_id`)
) TYPE=MyISAM;

CREATE TABLE `istinallianz` (
  `allianz_id` bigint(20) NOT NULL default '0',
  `spieler_id` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`allianz_id`,`spieler_id`)
) TYPE=MyISAM;

CREATE TABLE `kartenabschnitt` (
  `id` bigint(20) NOT NULL default '0',
  `links_id` bigint(20) NOT NULL default '0',
  `rechts_id` bigint(20) NOT NULL default '0',
  `welt_id` bigint(20) NOT NULL default '0',
  `kartennummer` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `links_id` (`links_id`,`rechts_id`,`welt_id`,`kartennummer`)
) TYPE=MyISAM;

CREATE TABLE `lager` (
  `id` bigint(20) NOT NULL auto_increment,
  `kapazitaet` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `lagerenthaelt` (
  `lager_id` bigint(20) NOT NULL default '0',
  `rohstoff_id` bigint(20) NOT NULL default '0',
  `anzahl` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`lager_id`,`rohstoff_id`)
) TYPE=MyISAM;

CREATE TABLE `mitteilung` (
  `id` bigint(20) NOT NULL auto_increment,
  `sender` bigint(20) NOT NULL default '0',
  `empfaenger` bigint(20) NOT NULL default '0',
  `datum` date NOT NULL default '0000-00-00',
  `uhrzeit` time NOT NULL default '00:00:00',
  `betreff` varchar(100) default 'kein Betreff',
  `inhalt` longtext,
  `gelesen` tinyint(4) NOT NULL default '0',
  `geloescht_sender` tinyint(4) NOT NULL default '0',
  `geloescht_empfaenger` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `page` (
  `id` bigint(20) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `quest` (
  `id` bigint(20) NOT NULL auto_increment,
  `kurzbeschreibung` text NOT NULL,
  `beschreibung` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `rohstoff` (
  `id` bigint(20) NOT NULL auto_increment,
  `sem_id` varchar(10) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  `beschreibung` text,
  `grafik_url` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `sem_id` (`sem_id`),
  UNIQUE KEY `sem_id_2` (`sem_id`)
) TYPE=MyISAM;

CREATE TABLE `rohstoffproduktion` (
  `insel_id` bigint(20) NOT NULL default '0',
  `rohstoff_id` bigint(20) NOT NULL default '0',
  `wachstum_prozent` int(11) NOT NULL default '0',
  `produktion_stunde` int(11) NOT NULL default '0',
  PRIMARY KEY  (`insel_id`,`rohstoff_id`)
) TYPE=MyISAM;

CREATE TABLE `rohstoffshop` (
  `id` bigint(20) NOT NULL default '0',
  `insel_id` bigint(20) NOT NULL default '0',
  `lager_id` bigint(20) NOT NULL default '0',
  `umsatz` double NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `insel_id` (`insel_id`,`lager_id`)
) TYPE=MyISAM;

CREATE TABLE `rohstoffverkauf` (
  `rohstoffshop_id` bigint(20) NOT NULL default '0',
  `rohstoff_id` bigint(20) NOT NULL default '0',
  `minBestand` bigint(20) NOT NULL default '0',
  `preis` double NOT NULL default '0',
  PRIMARY KEY  (`rohstoffshop_id`,`rohstoff_id`)
) TYPE=MyISAM;

CREATE TABLE `schiff` (
  `id` bigint(20) NOT NULL auto_increment,
  `flotten_id` bigint(20) NOT NULL default '0',
  `wendigkeit` double NOT NULL default '0',
  `geschwindigkeit` double NOT NULL default '0',
  `gefechtsgeschwindigkeit` double NOT NULL default '0',
  `enterkampfwert` double NOT NULL default '0',
  `lager_id` bigint(20) NOT NULL default '0',
  `erfahrungsstufe` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `flotten_id` (`flotten_id`)
) TYPE=MyISAM;

CREATE TABLE `seawars` (
  `id` bigint(20) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `session` (
  `sid` bigint(20) NOT NULL auto_increment,
  `uid` bigint(20) NOT NULL default '0',
  `client_ip` varchar(15) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `time` time NOT NULL default '00:00:00',
  PRIMARY KEY  (`sid`)
) TYPE=MyISAM;

CREATE TABLE `spieler` (
  `id` bigint(20) NOT NULL auto_increment,
  `email` varchar(200) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `name` varchar(200) NOT NULL default '',
  `vorname` varchar(200) NOT NULL default '',
  `telefonnummer` varchar(50) NOT NULL default '',
  `bezahlt_bis` date NOT NULL default '0000-00-00',
  `welt_id` bigint(20) NOT NULL default '1',
  `avatar` varchar(200) default NULL,
  `signatur` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`,`welt_id`)
) TYPE=MyISAM;

CREATE TABLE `template` (
  `id` bigint(20) NOT NULL auto_increment,
  `class` varchar(25) NOT NULL default '',
  `layout` varchar(25) NOT NULL default '',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `class` (`class`,`layout`)
) TYPE=MyISAM;

CREATE TABLE `welt` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;