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
			return $this->get('userid')==User::loggedIn();
		if ($method == 'showlist')
			return true;
		return false;
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
				));
		$rows = '';
		foreach($list as $entry) {
			if(isset($vars['battletype']) && ($vars['battletype'] != ''))
				if ($entry['battletypeid'] != $vars['battletype'])
					continue;
			$mission = new Mission($entry['mission']);
			$entry['missionname'] = $mission->get('name');
			$bt = new BattleType($entry['battletypeid']);
			$entry['battletypename'] = $bt->get('name');
			$rows .= parent::show($vars, 'battle_list_row', $entry);
		}
		$bt = new BattleType();
		$array['battletypeoptionlist'] = $bt->getOptionList($vars['battletype']); 
		$array['battletype'] = $vars['battletype'];
		$array['orderby'] = $orderby;
		$array['rows'] = $rows;
		return parent::show($vars, 'battle_list', $array);
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
		$ilist = $image->getlist('', true, 'name', array('*'));
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
		$ilist = $image->getlist('', true, 'name', array('*'));
		$array['imagelist'] = "";
		foreach($ilist as $iobj) {
			if (($iobj['parent'] == $this->class_name()) && ($iobj['parentid'] == $this->get('id')))
				$array['imagelist'] .= $this->show($vars, 'battle_view_image', $iobj); 			
		}
		return parent::show($vars, 'battle_view', $array);
	}

}
?>