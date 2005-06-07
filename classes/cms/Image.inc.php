<?/** * Imagelinks */
class Image extends AbstractClass {

	/**	 * returns all know images	 */	function getImageList() {
		global $mysql;
		$query = "SELECT id, name, url FROM image;";
		$array = $mysql->select($query);
		return $array;
	}

	function load($name) {
		global $mysql;		$query = "SELECT id, name, url FROM image WHERE name='$name';";		$array = $mysql->executeSql($query);		$this->data = $array;	}

	function show(& $vars) {
		return $this->data['url'];
	}

	function Image($name = '') {		if (empty ($name))			return;		$this->load($name);
	}
}
?>