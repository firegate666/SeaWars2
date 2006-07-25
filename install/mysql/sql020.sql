ALTER TABLE `question` ADD INDEX ( `blockname` );
ALTER TABLE `question` ADD INDEX ( `groupname` ); 
ALTER TABLE `question` ADD INDEX ( `questionaireid` );

ALTER TABLE `questionaireuser` ADD INDEX ( `email` );
ALTER TABLE `questionaireuser` ADD INDEX ( `password` );

ALTER TABLE `questionaireuser` ADD UNIQUE (	`email` );

ALTER TABLE `questionanswer` ADD INDEX ( `questionid` );

ALTER TABLE `questionaireanswers` ADD INDEX ( `questionanswerid` );
ALTER TABLE `questionaireanswers` ADD INDEX ( `quserid` );

INSERT INTO dbversion(sql_id, sql_subid) VALUES (20, 0);