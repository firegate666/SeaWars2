-- removed
ALTER TABLE `spieler` DROP `bezahlt_bis`;
ALTER TABLE 'archipel' ADD 'groessenklasse' bigint(20) NOT NULL default '1';
ALTER TABLE 'archipel' MODIFY auto_increment;