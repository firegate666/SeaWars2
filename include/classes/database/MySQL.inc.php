<?
/**
 * MySQL Wrapper
 * in fact, this isn't yet a wrapper, much improved has
 * to be done
 */
class MySQL extends SQL {

	/**
	* Connects to MySQL Database using global parameters
	* $dbserver
	* $dbuser
	* $dbpassword
	* $dbdatabase
	* 
	* @return	Ressource	databaselink
	*/
	function connect() {
		global $dbserver;
		global $dbuser;
		global $dbpassword;
		global $dbdatabase;
		$this->querycount++;
		
		if(($this->dblink != null) && mysql_ping($this->dblink)) // connection still exists?
			return;
		else {
	  		$flags = MYSQL_CLIENT_COMPRESS + MYSQL_CLIENT_INTERACTIVE;
	  		$this->dblink = MYSQL_CONNECT($dbserver, $dbuser, $dbpassword, false, $flags) or die("<H3>MySQL error: Databaseserver not responding.</H3>");
	  		MYSQL_SELECT_DB($dbdatabase) or die("<H3>MySQL error: Database not available.</H3>");
		}
	}

	/**
	* Disconnects database
	* @param	Ressource $dblink	databaselink
	*/
	function disconnect() {
		if($this->dblink != null)
			MYSQL_CLOSE($this->dblink);
	}

	/**
	* Executes SQL insert statement
	* @param	String	$query	sql query
	* @return	int	last insert id
	*/
	function insert($query) {
		$this->connect();
		$this->queries[] = $query;			
		$result = MYSQL_QUERY($query) or $this->print_error("insert", $query);
		$id = MYSQL_INSERT_ID();
		return $id;
	}

	/**
	* Executes SQL select statement
	* @param	String	$query	sql query
	* @param	boolean	$assoc	if false, return array is numeric
	* @return	String[][]	result set as array
	*/
	function select($query, $assoc = false) {
		$this->connect();
		$this->queries[] = $query;			
		$result = MYSQL_QUERY($query) or $this->print_error("select", $query);
		$return = array ();
		$counter = 0;
		if (!$assoc)
			while ($line = MYSQL_FETCH_ARRAY($result, MYSQL_NUM))
				$return[$counter ++] = $line;
		else
			while ($line = MYSQL_FETCH_ARRAY($result, MYSQL_ASSOC))
				$return[$counter ++] = $line;
		return $return;
	}

	/**
	* Executes SQL statement
	* @param	String	$query	sql query
	* @return	String[]	result set with single row
	*/
	function executeSql($query) {
		$this->connect();
		$this->queries[] = $query;			
		$result = MYSQL_QUERY($query) or $this->print_error("executeSql", $query);
		$result = MYSQL_FETCH_ARRAY($result, MYSQL_ASSOC);
		return $result;
	}

	/**
	* Executes SQL update statement
	* @param	String	$query	update statement
	* @return	int	number of affected rows
	*/
	function update($query) {
		$this->connect();
		$this->queries[] = $query;			
		$result = MYSQL_QUERY($query) or $this->print_error("update", $query);
		$rows = MYSQL_AFFECTED_ROWS();
		return $rows;
	}

	public function print_error($method, $query) {
		$msg = mysql_error()."<br><b>Query:</b> $query";
		error($msg, "MySQL", $method);
	}	
	
	public function escape($string) {
		return mysql_escape_string($string);
	} 
}
?>
