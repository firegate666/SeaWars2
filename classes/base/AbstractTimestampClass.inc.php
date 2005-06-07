<?
class AbstractTimestampClass extends AbstractClass {
	function store() {
		$this->set('timestamp', strftime("%Y-%m-%d %H:%M:%S", time()));
		AbstractClass :: store();
	}
}
?>