<?php

require_once dirname(__FILE__).'/database/All.inc.php';
require_once dirname(__FILE__).'/base/All.inc.php';
	
if(get_config("cms", false)) {
	require_once dirname(__FILE__).'/cms/All.inc.php';
}

if(get_config("game", false)) {
	require_once dirname(__FILE__).'/game/All.inc.php';
}

if(get_config("questionaire", false)) {
	require_once dirname(__FILE__).'/questionaire/All.inc.php';
}

require_once dirname(__FILE__).'/lib/All.inc.php';
?>