<?
	$template_classes[] = 'techtree';
	
/**
 * Main Tech-Tree class
 * Tech know which TTEntries a user knows and
 * what he can learn next
 * 
 * Design Info: Every Tech has to depend on something.
 * There is a root tech with id=0, everyone has to know
 * but it is never displayed
 * 
 * TODO: Forschungswarteschlange
 */
class TechTree extends AbstractNavigationClass {
	
	protected $categories;
	protected $techtree;
	
	/**
	 * Update techtree, calculate all running researches for logged in player
	 */
	public function update(){
		global $mysql;
		// get all running
		$spieler_id = SeaWars::player();
		$query = "SELECT * FROM ttexplored WHERE finished=0 AND spieler_id=".$spieler_id.";";
		$result = $mysql->select($query, true);
		foreach($result as $item) {
			if(strtotime($item['end']) <= strtotime(Date::now())) {
				$ttex = new TTExplored($item['id']);
				$ttex->set('finished', 1);
				$ttex->store();
			}
		}
	}
	
	/**
	* learn another tech for logged in player
	* use $vars['ttentryid']
	*
	* @param	String[]	$vars	request parameters
	*/
	public function research(&$vars) {
		if(isset($vars['ttentryid']) && !empty($vars['ttentryid'])) {
			if(is_array($this->techtree['running']))
				$error = 'Forschung am Laufen, erst alte Forschung beenden';
			else if(in_array($vars['ttentryid'], $this->techtree['avail'])) {
				$ttentry = new TTEntry($vars['ttentryid']);
				$ttentry->learn();
			} else {
				$error = "Diese Forschung kannst Du nicht lernen.";
			}
		}
		$techtree = new TechTree();
		return redirect('?class=techtree&error='.$error);
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
				$array['beschreibung'] = $tech->get('description');
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
				$array['beschreibung'] = $tech->get('description');
				$now = strtotime(Date::now());
				$end = strtotime($tech->getend());
				$array['dauer'] = ($end-$now);
				if($tech->get('imageid') != 0) {
					$i = new Image($tech->get('imageid'));
					$array['image'] = $i->get('url');
				} else
					$array['image'] = '';
				$techlayout .= $this->getLayout($array, "tech_running", $vars);
			}

		// available techs
		// getPopulation
		$population = 100;
		if(isset($this->techtree['avail']))
			foreach($this->techtree['avail'] as $techid) {
				$tech = new TTEntry($techid);
				$array = array();
				$array['id'] = $tech->get('id');
				$array['name'] = $tech->get('name');
				$array['dauer'] = ($tech->get('aufwand') / $population);
				$array['beschreibung'] = $tech->get('description');
				if($tech->get('imageid') != 0) {
					$i = new Image($tech->get('imageid'));
					$array['image'] = $i->get('url');
				} else
					$array['image'] = '';
				$techlayout .= $this->getLayout($array, "tech_available", $vars);
			}

		$array['error'] = $vars['error'];
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
		if ($method == 'research')
			return Login::isLoggedIn();
		if ($method == 'dropall')
			return Login::isLoggedIn();
		return parent::acl($method);
	}
	
	/**
	* remove alle explored tech for logged in player
	*/
	function dropall() {
		global $mysql;
		$spieler_id= SeaWars::player();
		
		$query = "DELETE FROM ttexplored WHERE `spieler_id` = $spieler_id AND `techtree_entry_id` <> 0;";
		$mysql->update($query);
		$error = "Alle Forschungen gelöscht";
		return redirect('?class=techtree&error='.$error);
	}

	/**
	* public constructore, initialize Tech-Tree for logged in player
	*/
	function TechTree() {
		$this->update();
		$this->load();
		// get all information
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
		
		$avail_techs = TTExplored::getAvailable($result['known'], $result['running']);
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
	public function getFields() {
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
class TTExplored extends AbstractClass {

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
		$spieler_id = $mysql->escape($spieler_id);
		$query = "SELECT techtree_entry_id FROM ttexplored WHERE spieler_id=".$spieler_id." AND finished=1 AND techtree_entry_id <> 0;";
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
		$spieler_id = $mysql->escape($spieler_id);
		$query = "SELECT techtree_entry_id FROM ttexplored WHERE spieler_id=".$spieler_id." AND finished=0;";
		return $mysql->select($query, true);
	}

	/**
	 * returns all tech ids from techs a player can research
	 * 
	 * @param	int[]	$techids	known tech ids
	 * @param	int[]	$runningtech	running tech ids
	 * @param	int	$spieler_id	player id, if empty logged in player
	 * @return	int['techtree_entry_id']	array of ids
	 */
	function getAvailable($techids, $runningtechs,$spieler_id = '') {
		global $mysql;
		
		// ignore known techs
		if(!empty($techids))
			$techids = 'AND entry_id NOT IN ('.implode(',', $techids).')';
		// ignore dependencies of running techs
		if(!empty($runningtechs))
			$techids .= ' AND dependson_id NOT IN ('.implode(',', $runningtechs).')';
		if(empty($spieler_id))
			$spieler_id = SeaWars::player();
		$spieler_id = $mysql->escape($spieler_id);
		$query = "SELECT *, COUNT(`techtree_entry_id`) AS erfuellt, COUNT(*) AS Abhängigkeiten 
					FROM `ttentrydependson` 
					LEFT JOIN `ttexplored` ON `dependson_id`=`techtree_entry_id`
    				WHERE spieler_id=$spieler_id GROUP BY `ttentrydependson`.`entry_id`
	  				HAVING Abhängigkeiten=erfuellt $techids;";
	  	$temp = $mysql->select($query, true);
	  	$result = array();
	  	if(is_array($runningtechs))
		  	foreach($temp as $item) {
		  		if(!in_array($item['entry_id'], $runningtechs))
		  			$result[] = $item;
		  	}
		else
			$result = $temp;
		return $result;
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
	* learn this TTEntry
	*/
	public function learn() {
		// getPopulation
		$population = 100;
		$start = Date::now();
		$duration = $this->get('aufwand') / $population;
		$end = strftime(Setting::read('timestampformat', ''), strtotime($start)+$duration);
		$ttex = new TTExplored();
		$ttex->set('spieler_id', SeaWars::player());
		$ttex->set('techtree_entry_id', $this->id); 
		$ttex->set('end', $end);
		$ttex->store();
	}
	
	public function getend() {
		global $mysql;
		$query = "SELECT end FROM ttexplored, ttentry WHERE ttexplored.techtree_entry_id =".$this->id." AND spieler_id=".SeaWars::player().";";
		$result = $mysql->executeSql($query);
		return $result['end'];
	}
	
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