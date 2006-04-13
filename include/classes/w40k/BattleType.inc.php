<?php
class BattleType extends W40K {

	public function acl($method) {
		return false;
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
		$fields[] = array('name' => 'parent',
                          'type' => 'integer',
                          'notnull' => false,
                          'htmltype' => 'select',
                          'desc'=>'Parent',
                          'join' => 'battletype');
		return $fields;
	}	
}
?>