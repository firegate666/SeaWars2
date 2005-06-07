<?/** * real links */class Link {
	var $value;	var $link = 'index.php?class=';	
	function show(& $vars) {
		return $this->link.$this->value;
	}

	function Link($value) {
		$this->value = $value;
	}
}
?>