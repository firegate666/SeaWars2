ALTER TABLE guestbook ADD COLUMN `ip` varchar(15) NOT NULL default '';

INSERT INTO dbversion(sql_id, sql_subid) VALUES (17, 0);
