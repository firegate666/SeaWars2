<?php
class Setting {
	
	/**
	* set setting in db
	* @param	String	$name	name of setting
	* @param	String	$value	value of setting
	* @param	boolean	$override	if true, overwrite if setting already exists
	* @return	false, if $override = false and setting existed, else true
	*/		
	function set($name, $value, $override = true) {
		if(!$override) {
			// check if setting existed
			// if existed return false;
		}
		// add setting to db
		return true;
	}
	
	/**
	* return setting value from db
	
	* @param	String	$name	name of setting
	* @param	String	$default	default if not set
	* @return	String	value of setting
	*/
	function get($name, $default) {
		$result = '';
		// fetch setting
		if(empyt($result))
			$result = $default;
		return $result;
	}
}
?>
