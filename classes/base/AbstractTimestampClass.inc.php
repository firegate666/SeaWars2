<?
abstract class AbstractTimestampClass extends AbstractClass {
	function store() {
		$this->set('timestamp', Date::now());
		parent::store();
	}
}
?>