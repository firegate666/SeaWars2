-- we need version information
CREATE TABLE `dbversion` (
`id` BIGINT NOT NULL AUTO_INCREMENT ,
`__createdon` DATETIME,
`__changedon` DATETIME,
`sql_id` BIGINT NOT NULL ,
`sql_subid` BIGINT DEFAULT '0' NOT NULL ,
PRIMARY KEY ( `id` )
);

ALTER TABLE dbversion ADD UNIQUE sql_id(sql_id, sql_subid);

INSERT INTO dbversion(sql_id, sql_subid) VALUES (16, 0);