<?
	$template_classes[] = 'inselliste';

/**
 * list of islands, islandlistview
 */
class Inselliste extends AbstractClass {
	
	/**
	 * there are no views in database, only single islands
	 */
	function load() {
	}
	
	function acl(){
          return Login::isLoggedIn();
        }

	function show(&$vars) {
		$mode = "OWN";
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
	        global $mysql;
       		$result = $mysql->select("SELECT i.id, i.name, i.groesse, a.name
                                          FROM insel i, archipel a
                                          WHERE a.id = i.archipel_id;");
       		$rows = '';
       		foreach($result as $row) {
                    $array['id']      = $row[0];
                    $array['name']    = $row[1];
                    $array['groesse'] = $row[2];
                    $array['archipel']= $row[3];
                    $rows .= $this->getLayout($array, "row");
       		}
		$array = array('inseln' => $rows);
		return $this->getLayout($array, "page");
	}

	function show_region(&$vars) {
		$kartenabschnitt_id = $vars["kartenabschnitt_id"];
	}

	function show_archipel(&$vars) {
		$archipel_id = $vars["archipel_id"];
	}

	function show_own(&$vars) {
	        global $mysql;
       		$result = $mysql->select("SELECT i.id, i.name, i.groesse, a.name
                                          FROM insel i, archipel a
                                          WHERE a.id = i.archipel_id AND i.spieler_id=".Session::getCookie('spieler_id').";");
       		$rows = '';
       		foreach($result as $row) {
                    $array['id']      = $row[0];
                    $array['name']    = $row[1];
                    $array['groesse'] = $row[2];
                    $array['archipel']= $row[3];
                    $rows .= $this->getLayout($array, "row");
       		}
		$array = array('inseln' => $rows);
		return $this->getLayout($array, "page");
	}
}
?>