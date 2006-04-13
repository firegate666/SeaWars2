<?/** * real links */class Link {
	protected $value;	protected $link;	
	function show(& $vars) {
		return $this->link.$this->value;
	}

	function Link($value) {		$this->link = 'index.php?class=';
		$this->value = $value;
	}
}
?>