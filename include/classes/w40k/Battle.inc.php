<?php
class Battle extends W40K {

	public function getFields() {
		$fields[] = array('name' => 'points',
                          'type' => 'integer',
                          'notnull' => false);
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
		$orderby = "name";
		if (isset($vars['orderby']))
			$orderby = mysql_escape_string($vars['orderby']);
		$limit = '';
		$limitstart = '';
		if (isset($vars['limit']) && !empty($vars['limit'])) {
			$limit = mysql_escape_string($vars['limit']);
			$limitstart = mysql_escape_string($vars['limitstart']);
		}
		$list = $this->getlist('', true, $orderby,
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
				), $limitstart, $limit);
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
			if(isset($vars['battletype']) && ($vars['battletype'] != ''))
				if ($entry['battletypeid'] != $vars['battletype'])
					continue;
			$mission = new Mission($entry['mission']);
			$entry['missionname'] = $mission->get('name');
			$bt = new BattleType($entry['battletypeid']);
			$entry['battletypename'] = $bt->get('name');
			if (!empty($entry['comment']))
				$entry['hastext'] = "T";
			$entry['icount'] = $this->numImages($entry['id']);
			$rows .= parent::show($vars, 'battle_list_row', $entry);
		}
		$bt = new BattleType();
		$array['battletypeoptionlist'] = $bt->getOptionList($vars['battletype']); 
		$array['battletype'] = $vars['battletype'];
		$array['orderby'] = $orderby;
		$array['rows'] = $rows;
		$statrows = '';
		foreach($this->getStats(null, $array['battletype']) as $entry) {
			$statrows .= parent::show($vars, 'battle_stat_row', $entry);
		}
		$array['statrows'] = $statrows;
		return parent::show($vars, 'battle_list', $array);
	}

	function getStats($playerid=null, $battletype = null) {
		global $mysql;
		$PLAYER = '';
		if ($playerid != null) {
			$PLAYER = "AND a.id='".mysql_escape_string($playerid)."'";
		}

		$BATTLETYPE = '';
		if ($battletype != null) {
			$BATTLETYPE = "AND battletypeid='".mysql_escape_string($battletype)."'";
		}

		$query1 = "SELECT player1, winner, vp1 as plus, vp2 as minus,
					(vp1-vp2) as punkte, count(*) as anzahl, a.id as armyid, a.name as army,
					sum(winner+1) as score
				FROM battle, army a
				WHERE player1=a.id $BATTLETYPE $PLAYER
				GROUP BY player1
				ORDER BY punkte DESC;";

		$query2 = "SELECT player2, winner, vp2 as plus, vp1 as minus,
					(vp2-vp1) as punkte, count(*) as anzahl, a.id as armyid, a.name as army,
					IF(winner=0,sum(winner+1),sum(winner)) as score
				FROM battle, army a
				WHERE player2=a.id $BATTLETYPE $PLAYER
				GROUP BY player2
				ORDER BY punkte DESC;";

		$result1 = $mysql->select($query1, true);
		$result2 = $mysql->select($query2, true);
                
		$result = array();
		foreach($result1 as $row)
			$result[$row['armyid']] = $row;
		
		foreach($result2 as $row) {
			if (isset($result[$row['armyid']])) {
				$result[$row['armyid']]['punkte'] += $row['punkte'];
				$result[$row['armyid']]['anzahl'] += $row['anzahl'];
				$result[$row['armyid']]['score'] += $row['score'];
			} else
				$result[$row['armyid']] = $row;
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
		return parent::parsefields($vars);
	}

	function edit(&$vars) {
		$array = array();
		if (isset($vars['submit'])) {
			$err = $this->parsefields($vars);
			if (!empty($err))
				$array['error'] = implode (", ", $err);
			else {
				$this->store();
				$array['error'] = "Object saved";
			}
		}
		$mission = new Mission();
		$array['missionlist'] = $mission->getOptionList($this->data['mission'], false, 'name', true, 'name');
		$bt = new BattleType();
		if ($this->hasright('w40kuser_extern'))
			$array['battletypelist'] = "<option value='0'></option>";
		else 
			$array['battletypelist'] = $bt->getOptionList($this->data['battletypeid'], true, 'name', true, 'name');
		$army = new Army();
		$array['armylist1'] = $army->getOptionList($this->data['player1'], false, 'name', true, 'name');
		$array['armylist2'] = $army->getOptionList($this->data['player2'], false, 'name', true, 'name');
		switch($this->get('winner')) {
			case 0: $array['deuce']="CHECKED='CHECKED'"; break;
			case 1: $array['win1']="CHECKED='CHECKED'"; break;
			case 2: $array['win2']="CHECKED='CHECKED'"; break;
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
