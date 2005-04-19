<?
class AbstractNoNavigationClass {
	
    var $data;
    var $id;
	var $language;

	function getMainLayout() {
		return 'main';
	}

	function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	function get_template($layout){
		return $this->getLayout(get_class($this), $name);
	}
	
	function load_language($language,$class){
	}
	
	function isRegisteredSession() {
		return session_is_registered(session);
	}
	
	function exists() {
	   return empty($this->id);
	}

	function load() {
        //if(!$this->exists()) return;
    	$id 		= $this->id;
    	$tablename 	= get_class($this);
    	$sql = new MySQL();
    	$this->data = $sql->executeSql("SELECT * FROM ".get_class($this)." WHERE id=$id;");
    	$this->id	= $this->data[id];
    	unset($this->data[id]);
    }

	function show() {
	}
    
    function store() {
      // Seperate keys from values
      $keys   = array_keys($this->data);
      $values = array_values($this->data);
      for($i=0;$i<count($values);$i++) {
      	$values[$i] = "'".$values[$i]."'";
      }
      // CREATE SQL Statement
      $sql = new MySQL();
      $tablename = get_class($this);
      if($this->id=='') {
	      $query = "INSERT INTO $tablename (".implode(",",$keys).") VALUES (".implode(",",$values).");";
	      echo($query);
	      $autoid = $sql->insert($query);
      } else {
		  $query  = "UPDATE $tablename SET";
		  $query .= " ".$keys[0]."=".$values[0];
	      for($i=1;$i<count($values);$i++)
	      	$query .= ", ".$keys[$i]."=".$values[$i];
	      $query .= " WHERE id=".$this->id.";";
	      $sql->update($query);
      }
      //echo("<p>SQL Statement: $query</p>");
    }
    
    function printout() {
      print_a($this);
    }
	
	function AbstractNoNavigationClass($id='') {
		$this->id=$id;
		$this->load();
	}
	
	/**
		checks whether it is allowed to call method from outside
		or who is allowed to call.
	*/
	function acl($method) {
		return false;
	}
	
	function getLayout($array, $layout) {
		$string = Template::getLayout(get_class($this),$layout);
		if(empty($array)) return $string;
		$keys = array_keys($array);
		foreach($keys as $key) {
			$string = str_replace('${'.$key.'}',$array[$key],$string);
		}
		return $string;
	}
	
	function getNavigation() {
		return "&nbsp;";
	}

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
