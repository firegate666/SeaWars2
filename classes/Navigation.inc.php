<?
class Navigation extends AbstractClass {
	function show() {
		//$links[] = array("lbl" => "Startseite", "lnk" => "");
		$links[] = array("lbl" => "Inselliste", "lnk" => "index.php?class=inselliste&method=show&mode=own");
		$links[] = array("lbl" => "Logout",     "lnk" => "index.php?class=login&method=logout");

		$rows = '';
		foreach($links as $link) {
			$array = array("lbl"=>$link["lbl"], "lnk"=>$link["lnk"]);
			$rows .= $this->getLayout($array, "main_bar_row");
		} 		
		
		$array2["links"] = $rows;
		$o = $this->getLayout($array2, "main_bar");
		return $o;
	}
	
	function Navigation() {
	}
}
?>
