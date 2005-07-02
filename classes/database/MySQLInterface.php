<?php
/**
 * Encapsulates the use of the mysql database
 * Queries are build from given arrays
 * 
 * WORK IN PROGRESS !! 
 */
class MySQLInterface {
	
	private $mysql;
	
	public function MySQLInterface() {
		$this->mysql = new MySQL();
	}
	
	/**
	 * @param	String[]	$fields	array of databasefields, if empty or null
	 * all fields are selected
	 * @param	String[][]	$orderby	array(array('orderby' => $name,
	 * 'orderdir' => $direction))
	 * @param	String[][]	$where	array(array('field', 'comp', 'val',
	 * 'next')), where default for comparator is = and next is AND
	 */
	public function select($fields, $tables, $where, $orderby=array()) {
		$fields = $this->createFields($fields,"'",'*');
		$tables = $this->createFields($tables,"",null);
		$orderby = $this->createOrderby($orderby);
		$where = $this->createWhere($where);

		$statement = $this->buildStatement($fields, $tables, $where, $orderby);
		print_a($statement);		
	}
	
	private function createWhere($where){
		$result = array();
		for($i = 0; $i < count($where); $i++) {
			$next = $where[$i];
			if(!isset($next['field']) || empty($next['field']))
				error("field must be set for where-claus", 'MySQLInterface', 'createWhere');
			else
				$next['field'] = mysql_real_escape_string($next['field']);
			if(!isset($next['comp']) || empty($next['field']))
				$next['comp'] = '=';
			else
				$next['comp'] = mysql_real_escape_string($next['comp']);
			if(!isset($next['val']) || empty($next['val']))
				error("value must be set for where-claus", 'MySQLInterface', 'createWhere');
			else
				$next['val'] = mysql_real_escape_string($next['val']);
			if(!isset($next['next']) || empty($next['next']))
				if($i < (count($where)-1))
					$next['next'] = 'AND';
			else			
				if(!($i < (count($where)-1)))
					$next['next'] = '';
				else
					$next['next'] = mysql_real_escape_string($next['next']);
					
			$result[] = $next['field'].' '.$next['comp']."'".$next['val']."'".' '.$next['next'].' '; 
		}
		return $result;
	}

	private function createFields($fields, $surround = '', $default = null) {
		$result = array();
		if(is_array($fields) && !empty($fields) && ($fields != null)) {
			foreach($fields as $field)
				$result[] = "$surround".mysql_real_escape_string($field)."$surround";
		} else {
			if($default == null)
				error("no elements submitted and no default set",'MySQLInterface', 'create fields');
			$result = array($default);
		}
		return $result;
	}

	private function createOrderby($orderby) {
		$result = array();
		foreach($orderby as $item) {
			if(!isset($item['orderby']))
				error("missing orderby in statement", 'MySQLInterface', 'createOrderby');
			else
				$item['orderby'] = mysql_real_escape_string($item['orderby']);
			if(!isset($item['orderdir']) || empty($item['orderdir']))
				$item['orderdir'] = 'ASC';
			else
				$item['orderdir'] = mysql_real_escape_string($item['orderdir']);
			$result[] = $item['orderby'].' '.$item['orderdir'];
		}
		return $result;
	}	
	
}
?>