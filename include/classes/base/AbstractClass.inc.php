<?
/**
 * The main features everyone should know
 */
abstract class AbstractClass {
	
    /** main data array */
    protected $data = array();
    
    /** auto id of object */
    protected $id;
    
    
	protected $language;
	
	/**
	 * return input field for objectfield
	 * 
	 * @param	String	name of field
	 */
	public function getInputField($field) {
		$return = '';
		switch($field['htmltype']) {
			case 'input': $return = "<input type='text' name='{$field['name']}' value='"."{$this->get($field['name'])}' size='75'/>";
				break; 
			case 'textarea': $return = "<textarea name='{$field['name']}' cols='75' rows='5'>{$this->get($field['name'])}</textarea>";
				break;
			case 'select':
				$return = "<select name='{$field['name']}'>";
				$obj = new $field['join']();
				$return .= $obj->getOptionList($this->get($field['name']), true);
				$return .= "</select>";
				break;
		}
		return $return;
	}
	
	/**
	 * workaround for get_class to user with lowercase
	 * tablenames
	 * 
	 * @return	String	lowercase class name
	 */
	public function class_name() {
		return strtolower(get_class($this));
	}
	
	/**
	 * return xml document of all items
	 */
	function xmllist() {
		global $mysql;
		$result[$this->class_name()] = $mysql->select("SELECT * FROM ".$this->class_name, true);
		$output = XML::get($result);
		return xml($output);
	}
	
	/**
	 * test if logged in user has righ $right
	 * 
	 * @param String	$userright to test
	 */
	public function hasright($right) {
		return User::hasright($right);
	}  
	
	/**
	 * All database fields are made public at this place
	 * Each field is one array row
	 * Example
	 * $fields[] = array('name' => id,
	 * 					'type' => boolean,
	 * 					'notnull' = false,
	 * 					'default' = '') 
	 * type can be: integer, string, boolean, timestamp
	 * it has to be implmented in each class, else it throws
	 * an error
	 *
	 * @return	String[][]	all known fields or false if no fields are set
	 */
	public function getFields() {
		return true;
		// not yet used
		// if activated
		// return false;
	}
	
	/**
	 * returns all rows for class $classname
	 *
	 * @param	String	$classname	if not set, $classname = name of actual
	 * class
	 * @return	String[][]	complete result
	 */
	function getlist($classname='', $ascending=true, $orderby = 'id', $fields = array('id'), $limitstart='', $limit='') {
		global $mysql;
		if (empty($classname)) $classname = $this->class_name();
		$orderdir = "ORDER BY ".$orderby." ";
		$fields = implode(',', $fields);
		if ($ascending) $orderdir .= "ASC";
		else $orderdir .= "DESC";
		$limits = '';
		if ($limitstart != '') {
			$limits = 'LIMIT '.$limitstart;
			if ($limit != '')
				$limits .= ', '.$limit;
		}
		else if ($limit != '')
			$limits = 'LIMIT '.$limit;
		$result = $mysql->select("SELECT ".$fields." FROM ".mysql_escape_string($classname)." $orderdir $limits;", true);
		return $result;
	}
	
	/**
	 * Setter
	 *
	 * @param	String	$key	name of attribute
	 * @param	String	$value	value of attribute
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	/**
	 * Getter for instance attributes
	 *
	 * @param	String	$key	name of attribute
	 * @return	String	value of attribute
	 */
	public function get($key) {
		if($key == 'id')
			return $this->id;
		if (!isset($this->data[$key]))
			return null; 
		return $this->data[$key];
	}

	/**
	 * return data array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * not used yet
	 * not sure if used anywhen
	 */
	function load_language($language,$class){
	}
	
	/**
	 * does this object exists?
	 *
	 * @param	boolean	true, if $this->id exists
	 */
	function exists() {
	   return !empty($this->id);
	}

	/**
	 * loader routine... not ready yet
	 * still error if class has no id field, that is why every
	 * class has to have one
	 * all data is fetched from table and stored into $this->data
	 */
	function load() {
		global $mysql;
        //if(!$this->exists()) return;
    	$id 		= mysql_escape_string($this->id);
    	$tablename 	= $this->class_name();
    	$this->data = $mysql->executeSql("SELECT * FROM ".$tablename." WHERE id=$id;");
    	$this->id	= $this->data['id'];
    	unset($this->data['id']);
    }

    /**
     * delete me, remove record from database
     */
    function delete() {
    	global $mysql;
    	if(empty($this->id)) return;
    	$tablename = $this->class_name();
    	$id = $this->id;
    	$query = "DELETE FROM $tablename WHERE id=$id";
    	$mysql->update($query);
    }
    
	/**
	* returns id of logged in user, 0 if no one is logged in
	*
	* @return	integer	userid
	*/
   protected function loggedIn() {
    	return User::loggedIn();
    }
    
    /**
     * save me to database
     * fetch all from $this->data and build SQL Statement
     * Update if existed, insert if new
     *
     * @return	int	id of object
     */
    function store() {
		global $mysql;

		// set timestamps
		$datenow = Date::now();
		if($this->id=='')
			$this->set('__createdon', $datenow);
		$this->set('__changedon', $datenow);
		
		// Seperate keys from values
		$keys   = array_keys($this->data);
		$values = array_values($this->data);
		for($i=0;$i<count($values);$i++) {
			$values[$i] = "'".mysql_escape_string($values[$i])."'";
		}
		// CREATE SQL Statement
		$tablename = $this->class_name();
		if($this->id=='') {
			$query = "INSERT INTO $tablename (".implode(",",$keys).") VALUES (".implode(",",$values).");";
			$this->id = $mysql->insert($query);
		} else {
			$query  = "UPDATE $tablename SET";
			$query .= " ".$keys[0]."=".$values[0];
			for($i=1;$i<count($values);$i++)
				$query .= ", ".$keys[$i]."=".$values[$i];
			$query .= " WHERE id=".$this->id.";";
			$mysql->update($query);
		}
		return $this->id;
	}
    
    /**
     * print myself to console
     */
    function printout() {
      print_a($this);
    }
	
	/**
	* public constructor
	*
	* @param	int	$id	id of object
	*/
	public function AbstractClass($id='') {
		if(!$this->getFields()) error("No fields set",$this->class_name(),'Constructor');
		if(empty($id) || !is_numeric($id)) return;
		$this->id=$id;
		$this->load();
	}
	
	/**
	* checks whether it is allowed to call method from outside 	or	 who is
	* allowed to call.
	*
	* @param	String	$method	function to test
	* @return	boolean	true if allowed, else false
	*/
	public function acl($method) {
		// more rights have to be checked at this place
		//if($method == 'xmllist') return true;
		return false;
	}
	
	/**
	 * get template
	 *
	 * @param	String[]	$array	Array with tags for replacement
	 * @param	String		$layout	Name of template
	 * @param	String[]	parameters from request
	 * @return	String	layout
	 */
	function getLayout($array, $layout, &$vars) {
		$t = new Template();

		// add some basic tags to parse
		$array['_created_'] = $this->get('__createdon');
		$array['_changed_'] = $this->get('__changedon');
		$array['_datetime_'] = Date::now();
		
		if (isset($this->layoutclass))
			return $t->getLayout($this->layoutclass,$layout,$array,false,$vars);
		else			
			return $t->getLayout($this->class_name(),$layout,$array,false,$vars);
	}
	
	function getNavigation(&$vars) {
		return "&nbsp;";
	}

	/**
	 * generic show using template page
	 * 
	 * @param	String[]	$vars	request parameters
	 * @return	String	output
	 */
	function show(&$vars, $layout = 'page', $array = array()) {
		if (!empty($array)) {
			foreach($this->data as $key=>$value) {
				if (!isset($array[$key]))
					$array[$key] = $value;
			}
		}
		else
			$array = $this->data;
		if (!isset($array['id']))
			$array['id'] = $this->id;
		return $this->getLayout($array, $layout, $vars);
	}

	function parsefields($vars) {
		$err = false;
		if (!$this->getFields()) {
			$this->data = $var;
			return true;
		}
		foreach($this->getFields() as $field) {
			// set some defaults
			if (!isset($field['type'])) $field['type'] = "string";
			if (!isset($field['notnull'])) $field['notnull'] = false;
			if (isset($vars[$field['name']])) {
				$value = $vars[$field['name']];
				if ($field['notnull'] && empty($value))
					$err[] = "{$field['name']} is null";
				if ($field['type'] == 'date') {
					$darray = explode("-", $value);
					if (count($darray) != 3)
						$err[] = "Unknown date format: $value";
					else {
						if (checkdate($darray[1], $darray[2], $darray[0] ) === false)
							$err[] = "illegal date: $value";
					}
					
				} else
					if (!settype($value, $field['type']))
						$err[] = "{$field['name']} type error, must be ".$field['type'];
				if (isset($field['size']))
					if (strlen($value) > $field['size'])
						$err[] = "{$field['name']} too long. Max.: ".$field['size'];
				if (isset($field['min']))
					if (strlen($value) < $field['min'])
						$err[] = "{$field['name']} too short. Min.: ".$field['min'];
				$this->data[$field['name']] = $value;
			} else {
				// check not null
				if ($field['notnull'])
					$err[] = "{$field['name']} is null";
			}
		}
		return $err;
	}

	/**
	 * helps building forms
	 *
	 * @param	String	$content	content to show inside of form, if array, build table
	 * @param	String	$class
	 * @param	String	$method
	 * @param	String	$name	name of form
	 * @param	String[]	$vars	request parameters
	 * @return	String	form
	 */
	function getForm($content='', $class='', $method='show',$name='MyForm', $vars=array(), $enctype='') {
		if(empty($class)) $class = $this->class_name();
		$o = '<!--getform start-->';
		$o .= '<form action="index.php" enctype="'.$enctype.'" name="'.$name.'" METHOD="POST">';
		$o .= HTML::input('hidden', 'class', $class);
		$o .= HTML::input('hidden', 'method', $method);
		$o2 = '';
		if(is_string($content))
			$o .= $content;
		else {
			$o .= '<table>'."\n";
			foreach($content as $input) {
				if($input['descr']=='') $o2 .= $input['input'];
				else $o .= HTML::tr('<td>'.$input['descr'].'</td>'.
							'<td>'.$input['input'].'</td>');
			}
			$o .= '</table>'."\n";
		}
		$o .= '</form><!--getform end-->';
		return $o2.$o;
	}
	
	public function advsearch($where=array(), $fields=array('id'), $boolop = 'AND') {
		global $mysql;
		$query = "SELECT ".implode(",", $fields)." FROM ".($this->class_name()).
					" WHERE ".implode(" $boolop ", $where).";";
		return $mysql->select($query, true);			
	}
	
	
	public function search($where, $sfield='id', $fields='id') {
		global $mysql;
		if ($where == null)
			$this->error('Error in where clause', 'search');
		if (($sfield == null) || ($sfield == ''))
			$this->error('Error in where clause (field)', 'search');
		if (!is_array($fields))
			$fields = array($fields);
		if (count($fields) == 0)
			$this->error('Error in where clause (fields)', 'search');
		$fields = implode(",", $fields);
		$query = "SELECT ".$fields." FROM ".($this->class_name()).
					" WHERE ".$sfield." = '".$where."';";
		return $mysql->select($query, true);			
	}
	
	protected function error($msg, $action) {
		error($msg, get_class($this), $action);
	}

	public function getOptionList($default = 0, $cannull = false, $field = 'name', $asc= true, $orderby='id') {
		$list = $this->getlist('', $asc, $orderby);
		$options = "";
		if ($cannull)
			$options = "<option></option>";
		foreach($list as $item) {
			$obj = new $this($item['id']);
			$id = $obj->get('id');
			$name = $obj->get($field);
			$selected = "";
			if ($id == $default)
				$selected = "SELECTED='SELECTED'";
			$options .= "<option $selected value='$id'>$name</option>";
		}
		return $options;
	}


}
?>
