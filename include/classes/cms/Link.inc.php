<?
	var $value;
	function show(& $vars) {
		return $this->link.$this->value;
	}

	function Link($value) {
		$this->value = $value;
	}
}
?>