ALTER TABLE `template` ADD `contenttype` VARCHAR( 100 ) NOT NULL DEFAULT 'text/html';

INSERT INTO dbversion(sql_id, sql_subid) VALUES (24, 0);