<?
	$template_classes[] = 'navigation';

/**
 * Navigation bar... someday there this class won't be anymore
 * as it is useless
 */
class Navigation extends AbstractClass {
	
	function show(&$vars) {
		//$links[] = array("lbl" => "Startseite", "lnk" => "");
		$links[] = array("lbl" => "Inselliste", "lnk" => "index.php?class=inselliste&method=show&mode=own");
		$links[] = array("lbl" => "Logout",     "lnk" => "index.php?class=login&method=logout");

		$rows = '';
		foreach($links as $link) {
			$array = array("lbl"=>$link["lbl"], "lnk"=>$link["lnk"]);
			$rows .= $this->getLayout($array, "main_bar_row", $vars);
		} 		
		
		$array2["links"] = $rows;
		$o = $this->getLayout($array2, "main_bar",$vars);
		return $o;
	}
	
	function Navigation() {
	}
}
?>