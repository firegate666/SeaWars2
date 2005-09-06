CREATE  TABLE guestbook {
  id bigint( 20  )  NOT  NULL  AUTO_INCREMENT ,
 `timestamp` timestamp NOT  NULL,
name varchar( 200  )  NOT  NULL default  '',
email varchar( 200  ) default NULL ,
 `subject` varchar( 200  )  NOT  NULL default  '',
content text NOT  NULL ,
deleted tinyint( 4  )  NOT  NULL default  '0',
 PRIMARY  KEY ( id )  )ENGINE =  MYISAM DEFAULT CHARSET  = latin1;