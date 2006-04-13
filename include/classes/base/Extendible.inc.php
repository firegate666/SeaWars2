<?php
class Extendible extends AbstractClass {

	public function acl($method) {
		return false;
	}

	function store() {
		$class = $this->get('parent');
		$id = $this->get('parentid');
		$obj = new $class($id);
		print_a($obj);
		if ($obj->exists())
			return parent::store();
		return '';
	}

	/**
	 * all fields used in class
	 */
	public function getFields() {
		$fields[] = array('name' => 'parent',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'parentid',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'name',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'value',
                          'type' => 'string',
                          'size' => 1000000,
                          'notnull' => false);

		return $fields;
	}

}
?>