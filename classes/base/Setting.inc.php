<?php
class Setting {
	
	/**
	* set setting in db
	* @param	String	$name	name of setting
	* @param	String	$value	value of setting
	* @param	boolean	$override	if true, overwrite if setting already exists
	* @return	false, if $override = false and setting existed, else true
	*/		
	function set($name, $value, $description = '', $override = true) {
		global $mysql;
		$name = mysql_real_escape_string($name);
		$value = mysql_real_escape_string($value);
		$description = mysql_real_escape_string($description);
		$result = Setting::get($name, '');
		if(!empty($result))
			if(!$override) return false;
			else {
				$mysql->update("UPDATE setting SET value='$value' WHERE name='$name';");
				return true;
			}
		else {
			$mysql->insert("INSERT INTO setting(name, value, description) VALUES ('$name', '$value', '$description');");
			return true;
		}
	}
	
	/**
	* return setting value from db
	*
	* @param	String	$name	name of setting
	* @param	String	$default	default if not set
	* @return	String	value of setting
	*/
	function get($name, $default='') {
		global $mysql;
		$result = $mysql->executeSql("SELECT value FROM setting WHERE name='".mysql_real_escape_string($name)."';");
		if(isset($result['value']))
			return $result['value'];
		else
			return $default;
		return $result;
	}
}
?>
