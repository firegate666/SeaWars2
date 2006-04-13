<?	$template_classes[] = 'insel';/** * ressourceproduction for each ressource depending on island */class Rohstoffproduktion extends AbstractClass {		function load(){}	function store(){}		public function getData(){		return $this->data;	}	
	public function Rohstoffproduktion($insel_id) {
		global $mysql;
		$array = $mysql->select("SELECT rp.wachstum_prozent, rp.produktion_stunde, r.sem_id, r.name
	                               FROM rohstoffproduktion rp, rohstoff r
	                               WHERE rp.insel_id=".$insel_id." AND rp.rohstoff_id=r.id;");
		foreach ($array as $item) {
			$this->data[] = array ('name' => $item[3], 'id' => $item[2], 'wp' => $item[0], 'ps' => $item[1]);
		}		if(empty($this->data))			$this->data = array();
	}
}

/** * This class represents an Island */class Insel extends AbstractNavigationClass {
	protected $rohstoffproduktion;
	protected $lager;
	/**	 * all fields used in class	 */	public function getFields() {		$fields[] = array('name' => 'name', 'type' => 'String', 'size' => 100, 'notnull' => true);		$fields[] = array('name' => 'groesse', 'type' => 'integer', 'notnull' => true);		$fields[] = array('name' => 'x_pos', 'type' => 'integer', 'notnull' => true);		$fields[] = array('name' => 'y_pos', 'type' => 'integer', 'notnull' => true);		$fields[] = array('name' => 'spieler_id', 'type' => 'integer', 'notnull' => true);		$fields[] = array('name' => 'archipel_id', 'type' => 'integer', 'notnull' => true);		$fields[] = array('name' => 'lager_id', 'type' => 'integer', 'notnull' => true);		return $fields;	}	/**	 * returns all islands with no owner	 * @return	String[][]	array of islands	 */	function getStartIslands() {		global $mysql;		$query = "SELECT insel.id FROM insel, archipel WHERE insel.spieler_id = 0 AND archipel.groessenklasse=1 AND insel.archipel_id = archipel.id;";		$result = $mysql->select($query);		return $result;	}
	/**	 * update ressource production on island	 */	public function update() {
		global $mysql;
		$query = "SELECT l.rohstoff_id, l.anzahl, rp.produktion_stunde, rp.insel_id, NOW() as now, l.lager_id
	                  FROM rohstoff r, lagerenthaelt l, rohstoffproduktion rp
	                  WHERE l.rohstoff_id = r.id AND rp.rohstoff_id=r.id AND rp.insel_id=".$this->id." AND l.lager_id=".$this->data['lager_id'].";";
		$lastupdate = $this->data['__changedon'];
		$array = $mysql->select($query);		foreach ($array as $item) {
			$rohstoff_id = $item[0];
			$anzahl = $item[1];
			$pps = $item[2];
			$insel_id = $item[3];
			$lager_id = $item[5];          			
			$diff_sec = strtotime($item[4]) - strtotime($lastupdate);
			$wachstum = ($pps / 3600) * $diff_sec;
			$neueAnzahl = $anzahl + $wachstum;
			$query = "UPDATE lagerenthaelt SET anzahl=$neueAnzahl WHERE rohstoff_id=$rohstoff_id AND lager_id=$lager_id;";
			$rows = $mysql->update($query);
		}
		$this->store();
	}

	/**	 * constructor, instantiates island wit updated ressources	 * @param	int	$id	id of instance	 */	function Insel($id = '') {		if(empty($id) or ($id==0)) return;
		parent::AbstractNavigationClass($id);
		$this->update();
		$this->rohstoffproduktion = new Rohstoffproduktion($this->id);
		$this->lager = new Lager($this->data['lager_id']);
	}

	/**	 * check if method is allowed	 * @param	String	$method	method to test	 * @return	boolean	true/false	 */	public function acl($method) {
		if ($method == 'show')
			return (Login :: isLoggedIn()) && ($this->data['spieler_id'] == Session::getCookie('spieler_id')); // better would be owner check
		return parent::acl($method);
	}

	/**	 * Show insel using template insel/page	 */	function show(& $vars) {
		$array['insel_name'] = $this->data['name'];
		foreach ($this->rohstoffproduktion->getData() as $res) {
			$array[$res['id']] = $this->lager->lagerenthaelt[$res['id']];
			$array[$res['id'].'_wachstum'] = intval(($res['ps']));
		}
		return $this->getLayout($array, "page", $vars);
	}
}
?>