-- removed
ALTER TABLE `spieler` DROP `bezahlt_bis`;
ALTER TABLE `archipel` ADD `groessenklasse` TINYINT DEFAULT '1' NOT NULL ;
ALTER TABLE `archipel` CHANGE `id` `id` BIGINT( 20 ) NOT NULL AUTO_INCREMENT