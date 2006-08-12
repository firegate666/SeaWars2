<?
/**
 * Templatehandling
 */

$__userrights[] = array('name'=>'templateadmin', 'desc'=>'can edit templates & images');

class Template extends AbstractClass {
	protected $layout;
	protected $tags = array ();

	function contenttypeoptionlist($class, $layout) {
		global $mysql;
		$list[] = "text/html";
		$list[] = "text/plain";
		$list[] = "text/css";
		$list[] = "text/javascript";
		$list[] = "text/xml";
		$result = $mysql->select("SELECT contenttype FROM template WHERE class='$class' AND layout='$layout'");
		$contenttype = $result[0][0];
		$return = "";
		foreach($list as $item) {
			$SELECTED = "";
			if ($item == $contenttype)
				$SELECTED = "selected='selectetd'";
			$return .= "<option $SELECTED value='$item'>$item</option>\n";
		}
		return $return;
	}

	function idbyname($name) {
	} 
	
	/**
	* checks whether it is allowed to call method from outside 	or	 who is
	* allowed to call.
	*
	* @param	String	$method	function to test
	* @return	boolean	true if allowed, else false
	*/
	function acl($method) {
		if ($method == 'clearcache')
			return true;
		return false;
	}

	/**
	* remove all templates from cache
	*/
	function clearcache() {
		if (get_config('cache_enabled', false))
			unset ($_SESSION['template']);
		die('cache geleert');
	}

	/**
	 * Remove all not substituted tags from $template
	 *
	 * @param	String	$template	template contents
	 */
	function removeLostTags(& $template) {
		$suchmuster = '/\$\{.*\}/i';
		$template = preg_replace($suchmuster, '', $template);
	}

	/**
	 * parse $template for known tags and store them
	 *
	 * @param	String	$template	template contents
	 */
	function parseTags($template) {
		$result = array ();
		$suchmuster = '/\$\{(\w*):(\w*\|?\w+)\}/i';
		$temp = array ();
		preg_match_all($suchmuster, $template, $temp, PREG_SET_ORDER);
		foreach ($temp as $item) {
			$result[$item[1].':'.$item[2]] = array ('type' => $item[1], 'value' => $item[2]);
		}
		$this->tags = $result;
	}

	/**
	 * Delete template
	 *
	 * @param	String	$class	category
	 * @param	String	$layout	name
	 */
	function deleteTemplate($class, $layout) {
		global $mysql;
		$class = $mysql->escape($class);
		$layout = $mysql->escape($layout);
		$query = "DELETE FROM template WHERE class='$class' AND layout='$layout';";
		$mysql->update($query);
	}

	/**
	 * Create template
	 *
	 * @param	String	$class	category
	 * @param	String	$layout	name
	 */
	function createTemplate($class, $layout) {
		global $mysql;
		$class = $mysql->escape($class);
		$layout = $mysql->escape($layout);
		$query = "INSERT INTO template(class, layout) VALUES('$class', '$layout');";
		$mysql->insert($query);
	}

//	/**
//	* public constructor
//	*/
//	function Template() {
//	}

	/**
	 * get all template classes
	 *
	 * @return	String[]	all categories sorted
	 */
	function getClasses() {
		global $template_classes;
		if (!isset ($template_classes) || empty ($template_classes))
			$template_classes = array ();
		sort($template_classes);
		return array_unique($template_classes);
	}

	/**
	 * get all layouts for $class
	 *
	 * @param	String	$class	category
	 * @return	String[]	all layouts
	 */
	function getLayouts($class) {
		global $mysql;
		$class = $mysql->escape($class);
		$result = $mysql->select("SELECT layout, id FROM template WHERE class='$class';");
		return $result;
	}

	public function getLayoutOptions($class, $default) {
		$list = Template::getLayouts($class);
		$return = "<option value='0'></option>";
		foreach($list as $layout) {
			$selected = "";
			if ($layout[1] == $default)
				$selected = "SELECTED='SELECTED'";
			$return .= "<option $selected value='{$layout[1]}'>{$layout[0]}</option>";
		}
		return $return;
	}


	/**
	 * Returns parsed template
	 *
	 * @param	String	$class	category
	 * @param	String	$layout	template name
	 * @param	String[]	$array	array of elements to replace tags in
	 * template
	 * @param	boolean	$noparse	if true, no replacement is made
	 * @param	String[]$vars	request parameters
	 * @param	boolean	$nocache	if false, take template from session cache
	 * @return	String	template as string
	 */
	function getLayout($class, $layout, $array = array (), $noparse = false, $vars = array (), $nocache = false) {
		global $mysql;
		$class = $mysql->escape($class);
		$layout = $mysql->escape($layout);
		$string = '';
		$array['__ref__'] = $vars['ref'];
		if (isset ($_SESSION['template'][$class][$layout]) && !$nocache && get_config('cache_enabled', false))
			$string = $_SESSION['template'][$class][$layout];
		else {
			$result = $mysql->select("SELECT content FROM template WHERE class='$class' AND layout='$layout'");
			$string = $result[0][0];
			if (get_config('cache_enabled', false))
				$_SESSION['template'][$class][$layout] = $string;
		}
		if ($noparse)
			return $string;
		$string = html_entity_decode($string);
		$string = HTML::convert_specialchars($string);
		$keys = array_keys($array);
		foreach ($keys as $key) {
			$string = stripcslashes(str_replace('${'.$key.'}', $array[$key], $string));
		}
		$this->parseTags($string);
		foreach ($this->tags as $key => $item) {
			$type = $mysql->escape($item['type']);			$value= $mysql->escape($item['value']);
			$obj = new $type ($value);
			$array[$key] = $obj->show($vars);
		}
		$keys = array_keys($array);
		foreach ($keys as $key) {
			$string = str_replace('${'.$key.'}', $array[$key], $string);
		}
		$this->removeLostTags($string);
		return $string;
	}
	
}
?>