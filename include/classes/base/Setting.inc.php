<?php
$__userrights[] = array('name'=>'configadmin', 'desc'=>'can view config');
$__userrights[] = array('name'=>'settingsadmin', 'desc'=>'can edit settings');

class Setting extends AbstractClass {
	
	public function Setting($name = null) {
		if (($name == null) || ($name == '')) 
			return;
		global $mysql;
		$result = $mysql->executeSql("SELECT id FROM setting WHERE name='".mysql_escape_string($name)."';");
		$this->id = $result['id'];
		$this->load();
	}

	/**
	* set setting in db
	* @param	String	$name	name of setting
	* @param	String	$value	value of setting
	* @param	boolean	$override	if true, overwrite if setting already exists
	* @return	false, if $override = false and setting existed, else true
	*/		
	function set($name, $value, $description = '', $override = true) {
		global $mysql;
		$name = mysql_escape_string($name);
		$value = mysql_escape_string($value);
		$description = mysql_escape_string($description);
		$setting = new Setting($name);
		$setting->data['name'] = $name;
		$setting->data['description'] = $description;
		if ($override)
			$setting->data['value'] = $value;
		$setting->store();
		$_SESSION['setting'][$name] = $setting->data['value'];
		if (empty($_SESSION['settingdesc'][$name]))
			$_SESSION['settingdesc'][$name] = $setting->data['description'];
		return true;
	}
	
	/**
	* return setting value from db
	*
	* @param	String	$name	name of setting
	* @param	String	$default	default if not set
	* @return	String	value of setting
	*/
	function get($name, $default='', $description='') {
		if(isset($_SESSION['setting'][$name]))
			return $_SESSION['setting'][$name];
		global $mysql;
		$result = null;
		$setting = new Setting($name);
		if (!$setting->exists()) {
			$result = $default;
		} else {
			$result = $setting->data['value'];
			$description = $setting->data['description'];
		}
		$_SESSION['setting'][$name] = $result;
		$_SESSION['settingdesc'][$name] = $description;
	}
}
?>