<?php
	/**
	 * Forward to a random questionaire
	 */

	// List of questionaire ids
	$ids[] = 21;
	$ids[] = 22;
	$ids[] = 23;
	$ids[] = 24;
	
	$count = count($ids);
	$random = rand(0, $count - 1);
	
	// target system
	$base = "http://fairness-online.virtualid.de/index.php";
	header("Location: ".$base."?questionaire/show/".($ids[$random]));
?>