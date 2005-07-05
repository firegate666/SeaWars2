<?
	$template_classes[] = 'techtree';
	
/**
 * Main Tech-Tree class
 * Tech know which TTEntries a user knows and
 * what he can learn next
 */
class TechTree extends AbstractClass {
	
	protected $categories;
	protected $techtree;
	
	public function update(){
	}
	
	public function research(&$vars) {
	}
	
	/**
	 * show techtree using template page
	 * 
	 * @param	String[]	$vars	request parameters
	 */
	function show(&$vars) {
		$catlayout = '';
		foreach($this->categories as $catid) {
			$cat = new TTCategory($catid['id']);
			$catlayout .= $this->getLayout(array('categoryname' => $cat->get('name')), "category_deactivated ", $vars);
		}

		// know techs
		if(isset($this->techtree['known']))
			foreach($this->techtree['known'] as $techid) {
				$tech = new TTEntry($techid);
				$array['name'] = $tech->get('name');
				if($tech->get('imageid') != 0) {
					$i = new Image($tech->get('imageid'));
					$array['image'] = $i->get('url');
				} else
					$array['image'] = '';
				$techlayout .= $this->getLayout($array, "tech_known", $vars);
			}
		
		// running techs
		if(isset($this->techtree['running']))
			foreach($this->techtree['running'] as $techid) {
				$tech = new TTEntry($techid);
				$array['name'] = $tech->get('name');
				if($tech->get('imageid') != 0) {
					$i = new Image($tech->get('imageid'));
					$array['image'] = $i->get('url');
				} else
					$array['image'] = '';
				$techlayout .= $this->getLayout($array, "tech_running", $vars);
			}

		// available techs
		if(isset($this->techtree['avail']))
			foreach($this->techtree['avail'] as $techid) {
				$tech = new TTEntry($techid);
				$array = array();
				$array['name'] = $tech->get('name');
				$array['dauer'] = $tech->get('aufwand');
				if($tech->get('imageid') != 0) {
					$i = new Image($tech->get('imageid'));
					$array['image'] = $i->get('url');
				} else
					$array['image'] = '';
				$techlayout .= $this->getLayout($array, "tech_available", $vars);
			}

		$array['categories'] = $catlayout;
		$array['techs'] = $techlayout;
		return $this->getLayout($array, "page", $vars);
	}

	/**
	 * check if method is allowed
	 * @param	String	$method	method to test
	 * @return	boolean	true/false
	 */
	public function acl($method) {
		if ($method == 'show')
			return Login::isLoggedIn();
		return parent::acl($method);
	}
	
	function TechTree($spieler_id) {
		$this->load();
		// get all information
	}
	
	/**
	 * place new learning for logged in player
	 * @param	int	$ttentryid	TTEntry ID
	 */	
	function learn($ttentryid) {
	}
	
	/**
	 * Fetches Tech-Tree as array including all known and available techs
	 * @return	int[]	ttentry ids
	 */	
	function getTechTree() {
		global $mysql;
		$known_techs = TTExplored::getExplored();
		$result = array();
		foreach($known_techs as $tech) {
			$result['known'][] = $tech['techtree_entry_id'];
		}
		$running_techs = TTExplored::getRunning();
		foreach($running_techs as $tech) {
			$result['running'][] = $tech['techtree_entry_id'];
		}
		$avail_techs = TTExplored::getAvailable($result['known']);
		foreach($avail_techs as $tech) {
			$result['avail'][] = $tech['entry_id'];
		}
		return $result;
	}
	
	/**
	 * as the tech-tree himself has no table
	 * there have to be work arounds for load and save.
	 * MySQL does not support views I'm afraid
	 */
	function load() {
		// get techtree, know/available/running
		$this->techtree = $this->getTechTree();
		
		// get categories
		$cat = new TTCategory();
		$this->categories = $cat->getlist();
		
	}
	
	/**
	 * as the tech-tree himself has no table
	 * there have to be work arounds for load and save.
	 * MySQL does not support views I'm afraid
	 */
	function save() {
	}
}

/**
 * Diese Klasse regelt die Zuordnung des konkreten Rohstoffes zur abstrakten
 * Kategorie aus TTEntry
 */
class TTEntryRohstoff extends AbstractClass {
  	/**
	 * all fields used in class
	 */
	protected function getFields() {
		$fields[] = array('name' => 'rohstoffid', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'ttentry_resid', 'type' => 'Integer', 'notnull' => true);
	}
}

/**
 * This class knows, which entry depends depends on whom
 */
class TTEntryDependson extends AbstractClass {
  	/**
	 * all fields used in class
	 */
	protected function getFields() {
		$fields[] = array('name' => 'entry_id', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'dependson_id', 'type' => 'Integer', 'notnull' => true);
	}
	
	/**
	 * returns all ttentry ids a ttentry depends on
	 * 
	 * @param	int	$ttenryid	Tech-Entry id
	 * @return int['dependson_id']	array of tech ids
	 */
	function get($ttentryid) {
		global $mysql;
		$query = "SELECT dependson_id WHERE entry_id=".$ttentryid.";";
		return $mysql->select($query, true);
	}
}

/**
 * This class knows, who knows what and where and when
 */
class TTExplored {

	/**
	 * returns all tech ids from techs a player knows
	 * 
	 * @param	int	$spieler_id	player id, if empty logged in player
	 * @return	int['techtree_entry_id']	array of ids
	 */
	function getExplored($spieler_id = '') {
		global $mysql;
		if(empty($spieler_id))
			$spieler_id = SeaWars::player();
		$spieler_id = mysql_real_escape_string($spieler_id);
		$query = "SELECT techtree_entry_id FROM ttexplored WHERE spieler_id=".$spieler_id." AND finished=1;";
		return $mysql->select($query, true);
	}

	/**
	 * returns all tech ids from techs a player ist exploring
	 * 
	 * @param	int	$spieler_id	player id, if empty logged in player
	 * @return	int['techtree_entry_id']	array of ids
	 */
	function getRunning($spieler_id = ''){
		global $mysql;
		if(empty($spieler_id))
			$spieler_id = SeaWars::player();
		$spieler_id = mysql_real_escape_string($spieler_id);
		$query = "SELECT techtree_entry_id FROM ttexplored WHERE spieler_id=".$spieler_id." AND finished=0;";
		return $mysql->select($query, true);
	}

	/**
	 * returns all tech ids from techs a player can research
	 * 
	 * @param	int[]	$techids	known tech ids
	 * @param	int	$spieler_id	player id, if empty logged in player
	 * @return	int['techtree_entry_id']	array of ids
	 */
	function getAvailable($techids, $spieler_id = '') {
		global $mysql;
		$techids = implode(',', $techids);
		if(empty($spieler_id))
			$spieler_id = SeaWars::player();
		$spieler_id = mysql_real_escape_string($spieler_id);
		$query = "SELECT *, COUNT(`techtree_entry_id`) AS erfuellt, COUNT(*) AS Abhängigkeiten 
					FROM `ttentrydependson` 
					LEFT JOIN `ttexplored` ON `dependson_id`=`techtree_entry_id`
    				GROUP BY `ttentrydependson`.`entry_id`
	  				HAVING Abhängigkeiten=erfuellt AND spieler_id=$spieler_id AND entry_id NOT IN($techids);";
	  	$result = $mysql->select($query, true);
		return $result;
	}

	/**
	 * return end of research
	 * 
	 * @return	Timestamp	end of research
	 */
	function calculateEnd() {
		return strftime("%Y-%m-%d %H:%M:%S", time());
	}

	/**
	 * is only invoked when research ist started
	 */
	function store() {
		if(!isset($this->data['start']))
			$this->set('start', strftime("%Y-%m-%d %H:%M:%S", time()));
		$this->set($this->calculateEnd);
		parent::store();
	}
}

/**
 * Categories for techs, no functionality, only gui use
 */
class TTCategory extends AbstractClass {
  	/**
	 * all fields used in class
	 */
	protected function getFields() {
		$fields[] = array('name' => 'name', 'type' => 'String', 'notnull' => true);
		return $fields;
	}
}

/**
 * the tech himself
 */
class TTEntry extends AbstractClass {
  	/**
	 * all fields used in class
	 */
	protected function getFields() {
		$fields[] = array('name' => 'name', 'type' => 'String', 'notnull' => true);
		$fields[] = array('name' => 'description', 'type' => 'String', 'notnull' => true);
		$fields[] = array('name' => 'image_id', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'tttypeid', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'ttcategoryid', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'aufwand', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res1_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res2_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res3_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res4_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res5_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res6_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res7_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res8_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res9_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'res10_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'morale_pc', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'money_abs', 'type' => 'Integer', 'notnull' => true);
		$fields[] = array('name' => 'maxpopulation_pc', 'type' => 'Integer', 'notnull' => true);
		return $fields;
	}
}

/**
 * tech type of tech
 */
class TTType extends AbstractClass {
  	/**
	 * all fields used in class
	 */
	protected function getFields() {
		$fields[] = array('name' => 'name', 'type' => 'String', 'notnull' => true);
		$fields[] = array('name' => 'beschreibung', 'type' => 'String', 'notnull' => true);
		return $fields;
	}
}
?>