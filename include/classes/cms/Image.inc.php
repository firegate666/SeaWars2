<?
/**
 * Imagelinks
 */
class Image extends AbstractClass {

		public function acl($method){
			return false;
		}

    	public function getFields() {
		$fields[] = array('name' => 'parent',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => null);
		$fields[] = array('name' => 'parentid',
                          'type' => 'integer',
                          'notnull' => null);
		$fields[] = array('name' => 'name',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'url',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);
		$fields[] = array('name' => 'size',
                          'type' => 'integer',
                          'notnull' => true);
		$fields[] = array('name' => 'prio',
                          'type' => 'integer',
                          'notnull' => false);
		$fields[] = array('name' => 'type',
                          'type' => 'string',
                          'size' => 100,
                          'notnull' => true);

		return $fields;
	}
    
    public function parsefields($vars, $parent = '', $parentid = ''){
    	$vars['parent'] = $parent;
    	$vars['parentid'] = $parentid;
    	$err = false;
    	if (!in_array($vars['type'], array('image/gif', 'image/pjpeg', 'image/jpeg')))
    		$err[] = "Image must be: image/gif, image/pjpeg or image/jpeg; found: ".$vars['type'];
    	if (!is_uploaded_file($vars['tmp_name']))
    		$err[] = "Upload failed";
    	$vars['name'] = str_replace (" ", "_", $vars['name']);
    	$vars['name'] = str_replace ("ä", "ae", $vars['name']);
    	$vars['name'] = str_replace ("ö", "oe", $vars['name']);
    	$vars['name'] = str_replace ("ü", "ue", $vars['name']);
    	$vars['name'] = str_replace ("Ä", "Ae", $vars['name']);
    	$vars['name'] = str_replace ("Ö", "Oe", $vars['name']);
    	$vars['name'] = str_replace ("Ü", "Ue", $vars['name']);
    	$vars['name'] = str_replace ("ß", "ss", $vars['name']);
    	$url = get_config("uploadpath").randomstring(25).$parent.$parentid.$vars['name'];
    	if ($err === false) {
    		$res = copy($vars['tmp_name'], $url);
	    	if (!$res)
	    		$err[] = "Copy faild";
	    	else
	    		$vars['url'] = $url;
    	}
    	if ($err !== false)
    		return $err;
    	return parent::parsefields($vars);
    }
    
    /**
    * delete Image from database and filesystem
    */
    function delete() {
        if(!isset($this->data['url']) || empty($this->data['url']))
                return;
        @unlink($this->data['url']);
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
		$name = mysql_escape_string($name);
		$query = "SELECT id, name, url FROM image WHERE name='$name';";
		$array = $mysql->executeSql($query);
		$this->data = $array;
	}

	function show(& $vars, $layout = '', $array = array()) {
		if ($layout != '')
			return parent::show($vars, $layout, $array);
		return $this->data['url'];
	}

	function Image($nameorid = '') {
		if (empty ($nameorid))
			return;
		if(is_numeric($nameorid)) {
			parent::AbstractClass($nameorid);
		} else
			$this->loadbyname($nameorid);
	}
}
?>
