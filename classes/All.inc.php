<?php
	$d = dirname(__FILE__).'/';

if($_CONFIG["database"]) {
	require_once $d.'SQL.inc.php';
	require_once $d.'MySQL.inc.php';
	require_once $d.'HTML.inc.php';
}
	
if($_CONFIG["base"]) {
	require_once $d.'AbstractNoNavigationClass.inc.php';
	require_once $d.'AbstractClass.inc.php';
	require_once $d.'AbstractTimestampClass.inc.php';
}	

if($_CONFIG["cms"]) {
	require_once $d.'Page.inc.php';
	require_once $d.'Error.inc.php';
	require_once $d.'Template.inc.php';
	require_once $d.'Image.inc.php';
	require_once $d.'PLink.inc.php';
	require_once $d.'Link.inc.php';
}

if($_CONFIG["game"]) {
	require_once $d.'Login.inc.php';
	require_once $d.'Allianz.inc.php';
	require_once $d.'Archipel.inc.php';
	require_once $d.'Bauplan.inc.php';
	require_once $d.'Flotte.inc.php';
	require_once $d.'Insel.inc.php';
	require_once $d.'Inselliste.inc.php';
	require_once $d.'Kartenabschnitt.inc.php';
	require_once $d.'Lager.inc.php';
	require_once $d.'Messenger.inc.php';
	require_once $d.'Mitteilung.inc.php';
	require_once $d.'Navigation.inc.php';
	require_once $d.'Schiff.inc.php';
	require_once $d.'SeaWars.inc.php';
	require_once $d.'Session.inc.php';
	require_once $d.'Spiel.inc.php';
	require_once $d.'Spieler.inc.php';
}
?>
