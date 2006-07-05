<?/** * real links */class Link {
	protected $value;	protected $link;	
	public function acl($method){		if ($method=='show')			return true;		return false;	}	function show(& $vars) {
		return $this->link.$this->value;
	}

	function Link($value) {		$this->link = 'index.php?';
		$this->value = $value;
	}
}
?>