<?
class Navigation extends AbstractClass {
	function show() {
		$links[0]["lbl"] = "Startseite"; 		
		$links[0]["lnk"] = ""; 		
		$links[1]["lbl"] = "Logout"; 		
		$links[1]["lnk"] = "index.php?class=login&method=logout";
		
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