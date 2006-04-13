<?php
$__userrights[] = array('name'=>'configadmin', 'desc'=>'can view config');
$__userrights[] = array('name'=>'settingsadmin', 'desc'=>'can edit settings');

class Setting extends AbstractClass {
	
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
		$result = Setting::get($name, '');
		if(($result==0) || !empty($result)) {
			if(!$override) return false;
			else {
				$mysql->update("UPDATE setting SET value='$value' WHERE name='$name';") or die(mysql_error());
				$_SESSION['setting'][$name] = $value;
				return true;
			}
		} else {
			//$mysql->insert("INSERT INTO setting(name, value, description) VALUES ('$name', '$value', '$description');");
			$s = new Setting();
			$s->set('name', $name);
			$s->set('value', $value);
			$s->set('description', $description);
			$s->store();
			print_a($s);
			$_SESSION['setting'][$name] = $value;
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
		if(isset($_SESSION['setting'][$name]))
			return $_SESSION['setting'][$name];
		else {
			$result = $mysql->executeSql("SELECT value, description FROM setting WHERE name='".mysql_escape_string($name)."';");
			$description = 'please re-login';
			if(isset($result['value'])) {
				$description = $result['description'];
				$result = $result['value'];
			} else
				$result = $default;
			$_SESSION['setting'][$name] = $result;
			$_SESSION['settingdesc'][$name] = $description;
		}
		return $result;
	}
}
?>