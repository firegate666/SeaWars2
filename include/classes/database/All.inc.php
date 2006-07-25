<?php
	$d = dirname(__FILE__).'/';

	require_once $d.'SQL.inc.php';

	$mysql = null;

	if(get_config("dbsystem", false) == 'mysql') {
		require_once $d.'MySQL.inc.php';
		require_once $d.'MySQLInterface.inc.php';
	    $mysql = new MySQL();
	} else if(get_config("dbsystem", false) == 'postgres') {
		require_once $d.'Postgres.inc.php';
	    $mysql = new Postgres();
	} else
		die("No database system set");

?>
