<?php

Setting::write('codex_defaultpagelimit', '', 'Codex Default Pagelimit', false);

class Codex extends W40K {

	/**
	 * all fields used in class
	 */
	public function getFields() {
		$fields[] = array('name' => 'name',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true,
                          'htmltype' => 'input',
                          'desc'=>'Name');
		$fields[] = array('name' => 'gamesystem',
                          'type' => 'integer',
                          'notnull' => true,
                          'htmltype' => 'select',
                          'desc'=>'Spielsystem',
                          'join' => 'gamesystem');
		$fields[] = array('name' => 'groupname',
                          'type' => 'string',
                          'size' => 10,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Codexgruppe');
		$fields[] = array('name' => 'commander',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Anführer');
		$fields[] = array('name' => 'followers',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'textarea',
                          'desc'=>'Nachfolger');
		$fields[] = array('name' => 'founding',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Gründung');
		$fields[] = array('name' => 'homeplanet',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Heimat');
		$fields[] = array('name' => 'colors',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'textarea',
                          'desc'=>'Farben');
		$fields[] = array('name' => 'specialized',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'textarea',
                          'desc'=>'Spezialisierung');
		$fields[] = array('name' => 'favunit',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Bevorzugte Einheit');
		$fields[] = array('name' => 'favweapon',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Bevorzugte Waffe');
		$fields[] = array('name' => 'reputation',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'textarea',
                          'desc'=>'Ruf');
		$fields[] = array('name' => 'actstrength',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'textarea',
                          'desc'=>'Aktuelle Stärke');
		$fields[] = array('name' => 'archenemy',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Erzfeind');
		$fields[] = array('name' => 'comment',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'textarea',
                          'desc'=>'Kommentar');
		return $fields;
	}

	public function acl($method) {
		if ($method == 'view')
			return true;
		if ($method == 'showlist')
			return true;
		return parent::acl($method);
	}

	function view(&$vars) {
		return parent::show($vars, 'codex_view', array());
	}
	
	function showlist(&$vars) {
		$orderby = "name";
		if (isset($vars['orderby']) && !empty($vars['orderby']))
			$orderby = $this->escape($vars['orderby']);
		
		$limit = Setting::read('codex_defaultpagelimit');
		$limitstart = '';
		if (isset($vars['limit']) && !empty($vars['limit'])) {
			$limit = $this->escape($vars['limit']);
			$limitstart = $this->escape($vars['limitstart']);
		} else if (isset($vars['limit']))
			$limit = '';

		$where = array();
		if (isset($vars['gamesystem']) && ($vars['gamesystem'] != ''))
			$where[] = array('key'=>'gamesystem', 'value'=>$vars['gamesystem']);

		$list = $this->getlist('', true, $orderby,
				array('*',
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

		$gs = new GameSystem($vars['gamesystem']);
		$array['gamesystemoptionlist'] = $gs->getOptionList($vars['gamesystem']); 
		$array['gamesystem'] = $vars['gamesystem'];
		
		foreach($list as $entry) {
			if (strlen($entry['comment']) > 50)
				$entry['comment'] = substr(strip_tags($entry['comment']), 0, 50)." [...]";
			$rows .= parent::show($vars, 'codex_list_row', $entry);
		}
		$array['rows'] = $rows;
		return parent::show($vars, 'codex_list', $array);
	}

	

}
?>
