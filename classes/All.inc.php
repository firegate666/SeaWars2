<?php

require_once dirname(__FILE__).'/lib/All.inc.php';
require_once dirname(__FILE__).'/database/All.inc.php';
require_once dirname(__FILE__).'/base/All.inc.php';
	
if($_CONFIG["cms"]) {
	require_once dirname(__FILE__).'/cms/All.inc.php';
}

if($_CONFIG["game"]) {
	require_once dirname(__FILE__).'/game/All.inc.php';
}

?>