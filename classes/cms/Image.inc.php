<?
/**
 * Imagelinks
 */
class Image extends AbstractClass {

    /**
    * delete Image from database and filesystem
    */
    function delete() {
        if(!isset($this->data['url']) || empty($this->data['url']))
                return;
        if(@unlink($this->data['url']))
                parent::delete();
    }

	/**
	 * returns all know images
	 */
	function getImageList() {
		global $mysql;
		$query = "SELECT id, name, url FROM image;";
		$array = $mysql->select($query);
		return $array;
	}

	/**
	* load image by known name
	* @param     String   $name             name of image
	*/
        function loadbyname($name) {
		global $mysql;
		$name = mysql_real_escape_string($name);
		$query = "SELECT id, name, url FROM image WHERE name='$name';";
		$array = $mysql->executeSql($query);
		$this->data = $array;
	}

	function show(& $vars) {
		return $this->data['url'];
	}

	function Image($nameorid = '') {
		if (empty ($nameorid))
			return;
		if(is_numeric($nameorid)) {
			AbstractClass::AbstractClass($nameorid);
		} else
			$this->loadbyname($nameorid);
	}
}
?>
