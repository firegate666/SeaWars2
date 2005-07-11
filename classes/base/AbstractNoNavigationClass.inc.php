<?
/**
 * The main features everyone should know
 */
abstract class AbstractNoNavigationClass {
	
    protected $data;
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
	 */
	protected function getFields() {
		return true;
		// not yet used
		// if activated
		// return false;
	}
	
	/**
	 * returns all rows for class $classname
	 * @param	String	$classname	if not set, $classname = name of actual
	 * class
	 */
	function getlist($classname='') {
		global $mysql;
		if(empty($classname)) $classname = $this->class_name();
		$result = $mysql->select("SELECT id FROM ".mysql_escape_string($classname), true);
		return $result;
	}
	
	function getMainLayout() {
		return 'main';
	}

	/**
	 * Setter
	 */
	public function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	/**
	 * Getter
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
	function isRegisteredSession() {
		return session_is_registered(session);
	}
	
	/**
	 * does this object exists?
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
    	$this->id	= $this->data[id];
    	unset($this->data[id]);
    }

    /**
     * delete me
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
     */
    function store() {
    	global $mysql;
      if(empty($this->data)) return;
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
	      //echo($query);
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
	
	public function AbstractNoNavigationClass($id='') {
		if(!$this->getFields()) error("No fields set",$this->class_name(),'Constructor');
		if(empty($id) || !is_numeric($id)) return;
		$this->id=$id;
		$this->load();
	}
	
	/**
	* checks whether it is allowed to call method from outside 	or	 who is
	* allowed to call.
	* @param	String	$method	function to test
	*/
	public function acl($method) {
		// more rights have to be checked at this place
		//if($method == 'xmllist') return true;
		return false;
	}
	
	/**
	 * get template
	 * @param	String[]	$array	Array with tags for replacement
	 * @param	String		$layout	Name of template
	 * @param	String[]	parameters from request
	 */
	function getLayout($array, $layout, &$vars) {
		$t = new Template();
		return $t->getLayout($this->class_name(),$layout,$array,false,$vars);
	}
	
	function getNavigation(&$vars) {
		return "&nbsp;";
	}

	/**
	 * generic show using template page
	 * 
	 * @param	String[]	$vars	request parameters
	 */
	function show(& $vars) {
		return $this->getLayout(array(), "page", $vars);
	}

	/**
	 * helps building forms
	 */
	function getForm($content='', $class='', $method='',$name='MyForm') {
		if(empty($class)) $class = $_REQUEST['class'];
		if(empty($method)) $method = $_REQUEST['method'];
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
}
?>
