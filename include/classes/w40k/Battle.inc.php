<?php

Setting::write('battle_defaultpagelimit', '', 'Battle Default Pagelimit', false);

class Battle extends W40K {

	protected $mbarmies1 = array();
	protected $mbarmies2 = array();

	public function getFields() {
		$fields[] = array('name' => 'points',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'gamesystem',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'name',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'impdate',
                          'type' => 'string',
                          'size' => 20,
                          'notnull' => true);
		$fields[] = array('name' => 'comment',
                          'type' => 'string',
                          'size' => 10000,
                          'notnull' => false);
		$fields[] = array('name' => 'player1',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'player2',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'mission',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'vp1',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'vp2',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'userid',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'winner',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'day',
                          'type' => 'integer',
                          'min' => 1,
                          'size' => 2,
                          'notnull' => true);
		$fields[] = array('name' => 'month',
                          'type' => 'integer',
                          'min' => 1,
                          'size' => 2,
                          'notnull' => true);
		$fields[] = array('name' => 'year',
                          'type' => 'integer',
                          'min' => 4,
                          'size' => 4,
                          'notnull' => true);
		$fields[] = array('name' => 'battletypeid',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'multibattle',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'realdate',
                          'type' => 'date',
                          'notnull' => true);
		return $fields;
	}

	public function acl($method) {
		if ($method == 'view')
			return true;
		if ($method == 'edit')
			if ($this->exists())
				return ($this->get('userid')==User::loggedIn())
					|| $this->hasright('admin')
					|| $this->hasright('w40kadmin');
			else
				return $this->hasright('w40kuser_intern')
					|| $this->hasright('w40kuser_extern')
					|| $this->hasright('w40kadmin');
		if ($method == 'delete')
			return $this->get('userid')==User::loggedIn()
				|| $this->hasright('w40kadmin');
		if ($method == 'showlist')
			return true;
		return parent::acl($method);
	}
	
	function delete($vars) {
		parent::delete();
		return $this->showlist($vars);
	}
	
	function getListByArmy($armyid){
		global $mysql;
		$query = "SELECT *, CONCAT(year,'-',month,'-',day) as date FROM battle WHERE player1=$armyid OR player2=$armyid";
		return $mysql->select($query, true);
	}

	function showlist(&$vars) {
		$orderby = "realdate";
		if (isset($vars['orderby']))
			$orderby = $this->escape($vars['orderby']);
		$limit = Setting::read('battle_defaultpagelimit');
		$limitstart = '';
		if (isset($vars['limit']) && !empty($vars['limit'])) {
			$limit = $this->escape($vars['limit']);
			$limitstart = $this->escape($vars['limitstart']);
		} else if (isset($vars['limit']))
			$limit = '';
			
		$where = array();
		if(isset($vars['battletype']) && ($vars['battletype'] != ''))
			$where[] = array('key'=>'battletypeid', 'value'=>$vars['battletype']);
		$list = $this->getlist('', false, $orderby,
				array('id',
					'name',
					'player1',
					'player2',
					'mission',
					'points',
					'battletypeid',
					'day',
					'month',
					'year',
					'impdate',
					'realdate',
					'comment',
				), $limitstart, $limit, $where);
		$array['orderby'] = $orderby;
		$array['prevlimit'] = '';
		$array['nextlimit'] = '';
		$array['limit'] = '';
		$array['limitstart'] = '';
		if ($limit != '') {
			$array['prevlimit'] = $limitstart - $limit;
			if ($array['prevlimit'] < 0)
				$array['prevlimit'] = 0;
			$array['nextlimit'] = '';
			if (count($list)==$limit)
				$array['nextlimit'] = $limitstart + $limit;
			$array['limit'] = $limit;
			$array['limitstart'] = $limitstart;
		}
		$rows = '';
		foreach($list as $entry) {
			$mission = new Mission($entry['mission']);
			$entry['missionname'] = $mission->get('name');
			$bt = new BattleType($entry['battletypeid']);
			$entry['battletypename'] = $bt->get('name');
			if (!empty($entry['comment']))
				$entry['hastext'] = "T";
			$entry['icount'] = $this->numImages($entry['id']);
			$rows .= parent::show($vars, 'battle_list_row', $entry);
		}
		$bt = new BattleType($vars['battletype']);
		$array['battletypeoptionlist'] = $bt->getOptionList($vars['battletype']); 
		$array['battletype'] = $vars['battletype'];
		$array['orderby'] = $orderby;
		$array['rows'] = $rows;
		$statrows = '';
		$stats = $this->getStats(null, $array['battletype']);
        $punkte = array();
        $score = array();
        $anzahl = array();
		foreach ($stats as $key => $row) {
	        $anzahl[$key] = $row['anzahl'];
	        $score[$key] = $row['score'];
	        $punkte[$key] = $row['punkte'];
		}
		if (($bt->get('sortfirst') != "") && ($bt->get('sortsecond') != "") && ($bt->get('sortthird') != "")) {
			$first = $bt->get('sortfirst');
			$second = $bt->get('sortsecond');
			$third = $bt->get('sortthird');
			array_multisort($$first, SORT_DESC, SORT_NUMERIC,
							$$second, SORT_DESC, SORT_NUMERIC,
							$$third, SORT_DESC, SORT_NUMERIC,
							$stats);
		}
		foreach($stats as $entry) {
			$statrows .= parent::show($vars, 'battle_stat_row', $entry);
		}
		$array['statrows'] = $statrows;
		return parent::show($vars, 'battle_list', $array);
	}

	function getStats($playerid=null, $battletype = null) {
		global $mysql;
		$PLAYER = '';
		if ($playerid != null) {
			$PLAYER = "AND a.id='".$this->escape($playerid)."'";
		}

		$BATTLETYPE = '';
		if ($battletype != null) {
			$BATTLETYPE = "AND battletypeid='".$this->escape($battletype)."'";
		}

		$query1 = "SELECT player1, sum(vp1) as plus, sum(vp2) as minus,
					count(*) as anzahl, a.id as armyid, a.name as army,
					IF(winner=0, sum(1), sum(0)) as deuce,
					IF(winner=1, sum(1), sum(0)) as wins,
					IF(winner=2, sum(1), sum(0)) as lost
				FROM battle, army a
				WHERE player1=a.id $BATTLETYPE $PLAYER AND multibattle = 0
				GROUP BY player1, winner;";

		$query2 = "SELECT player2, sum(vp2) as plus, sum(vp1) as minus,
					count(*) as anzahl, a.id as armyid, a.name as army,
					IF(winner=0, sum(1), sum(0)) as deuce,
					IF(winner=1, sum(1), sum(0)) as lost,
					IF(winner=2, sum(1), sum(0)) as wins
				FROM battle, army a
				WHERE player2=a.id $BATTLETYPE $PLAYER AND multibattle = 0
				GROUP BY player2, winner;";

		$result1 = $mysql->select($query1, true);
		$result2 = $mysql->select($query2, true);

		$result = array();

		foreach($result1 as $row)
			if (isset($result[$row["armyid"]])) {
				$result[$row["armyid"]]['plus'] += $row['plus'];
				$result[$row["armyid"]]['minus'] += $row['minus'];
				$result[$row["armyid"]]['anzahl'] += $row['anzahl'];
				$result[$row["armyid"]]['deuce'] += $row['deuce'];
				$result[$row["armyid"]]['wins'] += $row['wins'];
				$result[$row["armyid"]]['lost'] += $row['lost'];
				$result[$row["armyid"]]['punkte'] = $result[$row["armyid"]]['plus'] - $result[$row["armyid"]]['minus'];
				$result[$row["armyid"]]['score'] = 2*$result[$row["armyid"]]['wins'] + $result[$row["armyid"]]['deuce'];
			} else {
				$result[$row["armyid"]] = $row;
				$result[$row["armyid"]]['punkte'] = $row['plus'] - $row['minus'];
				$result[$row["armyid"]]['score'] = 2*$row['wins'] + $row['deuce'];
			}

		foreach($result2 as $row) {
			if (isset($result[$row["armyid"]])) {
				$result[$row["armyid"]]['plus'] += $row['plus'];
				$result[$row["armyid"]]['minus'] += $row['minus'];
				$result[$row["armyid"]]['anzahl'] += $row['anzahl'];
				$result[$row["armyid"]]['deuce'] += $row['deuce'];
				$result[$row["armyid"]]['wins'] += $row['wins'];
				$result[$row["armyid"]]['lost'] += $row['lost'];
				$result[$row["armyid"]]['punkte'] = $result[$row["armyid"]]['plus'] - $result[$row["armyid"]]['minus'];
				$result[$row["armyid"]]['score'] = 2*$result[$row["armyid"]]['wins'] + $result[$row["armyid"]]['deuce'];
			} else {
				$result[$row["armyid"]] = $row;
				$result[$row["armyid"]]['punkte'] = $row['plus'] - $row['minus'];
				$result[$row["armyid"]]['score'] = 2*$row['wins'] + $row['deuce'];
			}
		}

		return $result;
	}

	function parsefields($vars){
		if ($this->get('userid')==null)
			$vars['userid'] = User::loggedIn();
		else
			$vars['userid'] = $this->get('userid');
		if ($this->hasright('w40kuser_extern'))
			$vars['battletypeid'] = 0;
		$vars['impdate'] = std2impDate("{$vars['year']}-{$vars['month']}-{$vars['day']}");
		$vars['realdate'] = "{$vars['year']}-{$vars['month']}-{$vars['day']}";
		
		
		$return = parent::parsefields($vars);

		// store multibattle
		if (($return === false) && ($vars['multibattle'] == 1)) {
			
			if (!is_array($vars['multibattle1']) || !is_array($vars['multibattle2']))
				$return[] = "No armies for multibattle selected";
			else {
				$mb = new MultiBattle();
				$mb->store();
				$this->set('multibattle', $mb->get('id'));
				
				$this->mbarmies1 = $vars['multibattle1'];
				foreach($vars['multibattle1'] as $army1) {
					$mba = new MultiBattleArmy();
					$mba->set('army_id', $army1);
					$mba->set('player', 1);
					$mba->set('multibattle', $mb->get('id'));
					$mba->store();
				}
	
				$this->mbarmies2 = $vars['multibattle2'];
				foreach($vars['multibattle2'] as $army2) {
					$mba = new MultiBattleArmy();
					$mba->set('army_id', $army2);
					$mba->set('player', 2);
					$mba->set('multibattle', $mb->get('id'));
					$mba->store();
				}
			}
		}
		
		return $return;
	}

	function edit(&$vars) {
		$array = array();
		if (isset($vars['submitted'])) {
			$err = $this->parsefields($vars);
			if (!empty($err))
				$array['error'] = implode (", ", $err);
			else {
				$this->store();
				$array['error'] = "Object saved";
			}
		}

		$this->preloaddata($vars);

		$bt = new BattleType();
		if ($this->hasright('w40kuser_extern'))
			$array['battletypelist'] = "<option value='0'></option>";
		else 
			$array['battletypelist'] = $bt->getOptionList($this->data['battletypeid'], true, 'name', true, 'name');
		$gamesystem = new GameSystem();
		$array['gamesystemlist'] = $gamesystem->getOptionList($this->data['gamesystem'], true, 'name', true, 'name');
		$where = array();
		$array['armylist1'] = "";
		$array['armylist2'] = "";
		$array['mbarmylist1'] = "";
		$array['mbarmylist2'] = "";
		$array['missionlist'] = "";
		if (!empty($this->data['gamesystem'])) {
			$where[] = array('key'=>'gamesystem', 'value'=>$this->data['gamesystem']);
			$army = new Army();
			$array['armylist1'] = $army->getOptionList($this->data['player1'], false, 'name', true, 'name', 'id', $where);
			$array['armylist2'] = $army->getOptionList($this->data['player2'], false, 'name', true, 'name', 'id', $where);
	
			$array['mbarmylist1'] = $army->getOptionList($this->mbarmies1, false, 'name', true, 'name', 'id', $where);
			$array['mbarmylist2'] = $army->getOptionList($this->mbarmies2, false, 'name', true, 'name', 'id', $where);

			$mission = new Mission();
			$array['missionlist'] = $mission->getOptionList($this->data['mission'], false, 'name', true, 'name', 'id', $where);
		}

		switch($this->get('multibattle')) {
			case 0: $array['multibattleno']="CHECKED='CHECKED'"; break;
			default: $array['multibattleyes']="CHECKED='CHECKED'"; break;
		}

		switch($this->get('winner')) {
			case 0: $array['deuce']="CHECKED='CHECKED'"; break;
			case 1: $array['win1']="CHECKED='CHECKED'"; break;
			case 2: $array['win2']="CHECKED='CHECKED'"; break;
			default: $array['deuce']="CHECKED='CHECKED'"; break;
		}
		$image = new Image();
		$ilist = $image->getlist('', true, 'prio', array('*'));
		$array['imagelist'] = "";
		foreach($ilist as $iobj) {
			if (($iobj['parent'] == $this->class_name()) && ($iobj['parentid'] == $this->get('id')))
				$array['imagelist'] .= $this->show($vars, 'battle_edit_image', $iobj); 			
		}
		return parent::show($vars, 'battle_edit', $array);
	}

	protected function loadMultibattles() {
		if (!$this->get('multibattle'))
			return;
		$mb = new Multibattle($this->get('multibattle'));
		$mba = new MultiBattleArmy();
		$where[] = array('key'=>'multibattle', 'value'=>$mb->get('id'));
		$list = $mba->getlist('', true, 'id', array('*'), '', '', $where);
		foreach($list as $entry) {
			if ($entry['player'] == 1)
				$this->mbarmies1[] = $entry['army_id'];
			if ($entry['player'] == 2)
				$this->mbarmies2[] = $entry['army_id'];
		}
	}
	
	public function Battle($id='') {
		parent::W40K($id);
		$this->loadMultibattles();
	}

	function view(&$vars) {
		$array = array();
		$m = new Mission($this->get('mission'));
		$array['missionname'] = $m->get('name');
		$army = new Army($this->get('player1'));
		$codex = new Codex($army->get('codex'));
		$array['army1name'] = $army->get('name');
		$array['army1commander'] = $army->get('commander');
		$array['codex1name'] = $codex->get('name');
		$array['codex1id'] = $codex->get('id');
		$army = new Army($this->get('player2'));
		$codex = new Codex($army->get('codex'));
		$array['army2name'] = $army->get('name');
		$array['army2commander'] = $army->get('commander');
		$array['codex2name'] = $codex->get('name');
		$array['codex2id'] = $codex->get('id');
		$u = new User($this->get('userid'));
		$array['username'] = $u->get('login');
		switch($this->get('winner')) {
			case 0 : $array['winnername'] = "-";break;
			case 1 : $array['winnername'] = $array['army1name'];break;
			case 2 : $array['winnername'] = $array['army2name'];break;
		}
		$this->mbarmies1_names = array();
		$this->mbarmies2_names = array();
		foreach($this->mbarmies1 as $army_id) {
			$a = new Army($army_id);
			$this->mbarmies1_names[] = $a->get('name');
		}
		foreach($this->mbarmies2 as $army_id) {
			$a = new Army($army_id);
			$this->mbarmies2_names[] = $a->get('name');
		}
		$array['mbarmies1'] = implode(', ', $this->mbarmies1_names);
		$array['mbarmies2'] = implode(', ', $this->mbarmies2_names);
		$bt = new BattleType($this->get('battletypeid'));
		$array['battletypename'] = $bt->get('name');
		$image = new Image();
		$ilist = $image->getlist('', true, 'prio', array('*'));
		$array['imagelist'] = "";
		foreach($ilist as $iobj) {
			if (($iobj['parent'] == $this->class_name()) && ($iobj['parentid'] == $this->get('id')))
				$array['imagelist'] .= $this->show($vars, 'battle_view_image', $iobj); 			
		}
		return parent::show($vars, 'battle_view', $array);
	}

}


?>
