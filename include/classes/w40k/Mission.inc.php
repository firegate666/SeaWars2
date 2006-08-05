<?php

Setting::write('mission_defaultpagelimit', '', 'Mission Default Pagelimit', false);

class Mission extends W40K {

	public function acl($method) {
		if ($method == 'view')
			return true;
		if ($method == 'showlist')
			return true;
		return parent::acl($method);
	}

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
		$fields[] = array('name' => 'comment',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'textarea',
                          'desc'=>'Comment');
		$fields[] = array('name' => 'category',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Category');
		$fields[] = array('name' => 'ruleset',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Ruleset');
		$fields[] = array('name' => 'source',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Source');
		$fields[] = array('name' => 'rounds',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => false,
                          'htmltype' => 'input',
                          'desc'=>'Rounds');

		return $fields;
	}

	function view(&$vars) {
		return parent::show($vars, 'mission_view', array());
	}

	function showlist(&$vars) {
		$orderby = "name";
		if (isset($vars['orderby']))
			$orderby = $this->escape($vars['orderby']);
		$limit = Setting::read('mission_defaultpagelimit');
		if (isset($vars['limit']) && !empty($vars['limit'])) {
			$limit = $this->escape($vars['limit']);
			$limitstart = $this->escape($vars['limitstart']);
		} else if (isset($vars['limit']))
			$limit = '';

		$where = array();
		if (isset($vars['gamesystem']) && ($vars['gamesystem'] != ''))
			$where[] = array('key'=>'gamesystem', 'value'=>$vars['gamesystem']);

		$list = $this->getlist('', true, $orderby,
				array('id',
					'name',
					'comment',
					'rounds',
					'category',
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
			$rows .= parent::show($vars, 'mission_list_row', $entry);
		}
		$array['rows'] = $rows;
		return parent::show($vars, 'mission_list', $array);
	}

}
?>