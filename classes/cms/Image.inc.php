<?
class Image extends AbstractClass {

	function getImageList() {
		global $mysql;
		$query = "SELECT id, name, url FROM image;";
		$array = $mysql->select($query);
		return $array;
	}

	function load() {
	}

	function show(& $vars) {
		return $this->data['url'];
	}

	function Image($name = '') {
		global $mysql;
		if (empty ($name))
			return;
		$query = "SELECT id, name, url FROM image WHERE name='$name';";
		$array = $mysql->executeSql($query);
		$this->data = $array;
	}
}
?>