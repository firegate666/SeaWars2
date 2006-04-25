<?php
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
			$orderby = mysql_escape_string($vars['orderby']);
		if (isset($vars['limit']) && !empty($vars['limit'])) {
			$limit = mysql_escape_string($vars['limit']);
			$limitstart = mysql_escape_string($vars['limitstart']);
		}
		$list = $this->getlist('', true, $orderby,
				array('id',
					'name',
					'comment',
					'rounds',
					'category',
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
			if (strlen($entry['comment']) > 50)
				$entry['comment'] = substr(strip_tags($entry['comment']), 0, 50)." [...]";
			$rows .= parent::show($vars, 'mission_list_row', $entry);
		}
		$array['rows'] = $rows;
		return parent::show($vars, 'mission_list', $array);
	}

}
?>