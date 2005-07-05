ALTER TABLE `ttentry`
  DROP `res1_abs`,
  DROP `res2_abs`,
  DROP `res3_abs`,
  DROP `res4_abs`,
  DROP `res5_abs`,
  DROP `res6_abs`,
  DROP `res7_abs`,
  DROP `res8_abs`,
  DROP `res9_abs`,
  DROP `res10_abs`;
ALTER TABLE `ttentry`
	CHANGE `morale_pc` `morale_pc` TINYINT( 4 ) DEFAULT '0' NOT NULL ,
	CHANGE `money_abs` `money_build` TINYINT( 4 ) DEFAULT '0' NOT NULL ,
	CHANGE `maxpopulation_pc` `maxpopulation_pc` TINYINT( 4 ) DEFAULT '0' NOT NULL 
ALTER TABLE `ttentry`
	ADD `money_cost` BIGINT NOT NULL AFTER `money_build` ;
ALTER TABLE `ttentry`
	ADD `max_per_island` INT DEFAULT '0' NOT NULL ,
	ADD `max_per_player` INT DEFAULT '0' NOT NULL ,
	ADD `max_per_archipel` INT DEFAULT '0' NOT NULL ,
	ADD `max_per_ship` INT DEFAULT '0' NOT NULL ;
  
ALTER TABLE `ttexplored`
	ADD `finished` TINYINT DEFAULT '0' NOT NULL ;
ALTER TABLE `ttexplored` ADD INDEX ( `finished` ) ;

ALTER TABLE `ttentryrohstoff`
	CHANGE `ttentry_resid` `ttentry_id` BIGINT DEFAULT '0' NOT NULL;
ALTER TABLE `ttentryrohstoff`
	ADD	`res_cost` BIGINT DEFAULT '0' NOT NULL ,
	ADD `res_build` BIGINT DEFAULT '0' NOT NULL ;