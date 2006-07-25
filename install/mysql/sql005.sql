ALTER TABLE `mitteilung` DROP `uhrzeit` ;
ALTER TABLE `mitteilung` CHANGE `datum` `datum` TIMESTAMP NOT NULL ;
ALTER TABLE `mitteilung` ADD `art` TINYINT( 4 ) NOT NULL AFTER `inhalt` ;