<?
/**
 * The main features everyone should know
 */
abstract class AbstractClass {
	
    /** main data array */
    protected $data;
    
    /** auto id of object */
    protected $id;
    
    
	protected $language;
	
	/**
	 * workaround for get_class to user with lowercase
	 * tablenames
	 * 
	 * @return	String	lowercase class name
	 */
	protected function class_name() {
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
	protected function getFields() {
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
	function getlist($classname='', $ascending=true, $orderby = 'id', $fields = array('id')) {
		global $mysql;
		if (empty($classname)) $classname = $this->class_name();
		$orderdir = "ORDER BY ".$orderby." ";
		$fields = implode(',', $fields);
		if ($ascending) $orderdir .= "ASC";
		else $orderdir .= "DESC";
		$result = $mysql->select("SELECT ".$fields." FROM ".mysql_escape_string($classname)." $orderdir;", true);
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
		return $this->data[$key];
	}

	/**
	 * not used yet
	 * not sure if used anywhen
	 */
	function load_language($language,$class){
	}
	
	/**
	 * is session registered?
	 */
// is this used?
//	function isRegisteredSession() {
//		return session_is_registered(session);
//	}
	
	/**
	 * does this object exists?
	 *
	 * @param	boolean	true, if $this->id exists
	 */
	function exists() {
	   return empty($this->id);
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
     * save me to database
     * fetch all from $this->data and build SQL Statement
     * Update if existed, insert if new
     *
     * @return	int	id of object
     */
    function store() {
		global $mysql;
		if(empty($this->data)) return;

		// set timestamps
		if($this->id=='')
			$this->set('__createdon', Date::now());
		$this->set('__changedon', Date::now());
		
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
	function show(& $vars) {
		return $this->getLayout(array(), "page", $vars);
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
	function getForm($content='', $class='', $method='',$name='MyForm', $vars=array()) {
		if(empty($class)) $class = $vars['class'];
		if(empty($method)) $method = $vars['method'];
		$o = '<!--getform start-->';
		$o .= '<form action="index.php" name="'.$name.'" METHOD="POST">';
		$o .= '<input type="hidden" name="class" value="'.$class.'">';
		$o .= '<input type="hidden" name="method" value="'.$method.'">';
		if(is_string($content))
			$o .= $content;
		else {
			$o .= '<table>';
			foreach($content as $input) {
				if($input['descr']=='') $o .= $input['input'];
				else $o .= HTML::tr('<td>'.$input['descr'].'</td>' .
							'<td>'.$input['input'].'</td>');
			}
			$o .= '</table>';
		}
		$o .= '</form><!--getform end-->';
		return $o;
	}
	
	protected function error($msg, $action) {
		error($msg, get_class($this), $action);
	}
}
?>
