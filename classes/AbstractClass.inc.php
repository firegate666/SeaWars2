<?
  class AbstractClass {
    // $data is an array containing all table fields
    // keep in mind, that is a table representation, keep track that non-numeric fields
    // have to be set in ' '
    protected $data;
	
	protected $language;
	
	function getNavigation(){
		return Navigation::show();
	}
	
	function set($key, $value) {
		$this->data[$key] = $value;
	}
	
	function AbstractClass($id='') {
		$this->load($id);
	}
	
	function get_template($layout){
		return getLayout(get_class($this), $name);
	}
	
	function load_language($language,$class){
		include("languages/$language/lang_$class");
		$this->language = $lang;
	}
	
	function isRegisteredSession() {
		return session_is_registered(session);
	}
	
	function load() {
    	$id 		= $this->id;
    	$tablename 	= get_class($this);
    	$sql = new MySQL();
    	$this->data = $sql->executeSql("SELECT * FROM ".get_class($this)."");
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
	      $autoid = $sql->insert($query);
      } else {
		  $query  = "UPDATE $tablename SET";
		  $query .= " ".$keys[0]."=".$values[0];
	      for($i=1;$i<count($values);$i++)
	      	$query .= ", ".$keys[$i]."=".$values[$i];
	      $query .= " WHERE id=".$this->id.";";
	      $sql->update($query);
      }
      echo("<p>SQL Statement: $query</p>");
    }
    
    function printout() {
      print_a($this);
    }


  }
?>
