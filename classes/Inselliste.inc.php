<?
class Inselliste extends AbstractClass {
	function load() {
	}
	
	function show(&$vars) {
		$mode = "ALL";
		if(isset($vars["mode"])) {
			$mode = $vars["mode"];
		}
		switch(strtoupper($mode)) {
			case ("ALL") : return $this->show_all(&$vars); break;
			case ("REGION") : return $this->show_region(&$vars); break;
			case ("ARCHIPEL") : return $this->show_archipel(&$vars); break;
			case ("OWN") : return $this->show_own(&$vars); break;
			default      : return $this->show_all(&$vars);	
		}
		return $this->show_all();
	}
	
	function show_all(&$vars) {
	}

	function show_region(&$vars) {
		$kartenabschnitt_id = $vars["kartenabschnitt_id"];
	}

	function show_archipel(&$vars) {
		$archipel_id = $vars["archipel_id"];
	}

	function show_own(&$vars) {
	}
}
?>