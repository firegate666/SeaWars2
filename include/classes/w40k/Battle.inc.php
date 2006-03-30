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
                          'notnull' => true);
		$fields[] = array('name' => 'vp2',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'userid',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'winner',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'day',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'month',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'year',
                          'type' => 'integer',
                          'notnull' => true);
		return $fields;
	}

	public function acl($method) {
		if ($method == 'edit')
			return User::loggedIn();
		if ($method == 'showlist')
			return true;
		return false;
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
					'day',
					'month',
					'year',
					"CONCAT(year,'-',month,'-',day) as date",
				));
		$rows = '';
		foreach($list as $entry) {
			$mission = new Mission($entry['mission']);
			$entry['missionname'] = $mission->get('name');
			$rows .= parent::show($vars, 'battle_list_row', $entry);
		}
		$array['rows'] = $rows;
		return parent::show($vars, 'battle_list', $array);
	}

	function parsefields($vars){
		if ($this->get('userid')==null)
			$vars['userid'] = User::loggedIn();
		else
			$vars['userid'] = $this->get('userid');
		return parent::parsefields($vars);
	}

	function edit(&$vars) {
		$array = array();
		if (isset($vars['submit'])) {
			$err = $this->parsefields($vars);
			if (!empty($err))
				$array['error'] = implode (", ", $err);
			else
				$this->store();
		}
		$mission = new Mission();
		$array['missionlist'] = $mission->getOptionList($this->data['mission']);
		$army = new Army();
		$array['armylist1'] = $army->getOptionList($this->data['player1']);
		$array['armylist2'] = $army->getOptionList($this->data['player2']);
		switch($this->get('winner')) {
			case 0: $array['deuce']="CHECKED='CHECKED'"; break;
			case 1: $array['win1']="CHECKED='CHECKED'"; break;
			case 2: $array['win2']="CHECKED='CHECKED'"; break;
		}
		return parent::show($vars, 'battle_edit', $array);
	}

}
?>