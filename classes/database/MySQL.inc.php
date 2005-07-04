<?
/**
 * MySQL Wrapper
 * in fact, this isn't yet a wrapper, much improved has
 * to be done
 */
class MySQL extends SQL {

	
	/**
	* DB Ressource connection
	*/
	protected $dblink;

	public function MySQL() {
		$this->connect();
	}

	/**
	  Connects to MySQL Database using global parameters
	  $dbserver
	  $dbuser
	  $dbpassword
	  $dbdatabase
	  
	  @return	databaselink
	*/
	function connect() {
		global $dbserver;
		global $dbuser;
		global $dbpassword;
		global $dbdatabase;
		$flags = MYSQL_CLIENT_COMPRESS + MYSQL_CLIENT_INTERACTIVE;
		$this->dblink = MYSQL_CONNECT($dbserver, $dbuser, $dbpassword, false, $flags) or die("<H3>MySQL error: Databaseserver not responding.</H3>");
		MYSQL_SELECT_DB($dbdatabase) or die("<H3>MySQL error: Database not available.</H3>");
	}

	/**
	  Disconnects database
	  @dblink	databaselink
	*/
	function disconnect() {
		MYSQL_CLOSE($this->dblink);
	}

	/**
	  Executes SQL insert statement
	  @query	sql query
	  @return	last insert id
	*/
	function insert($query) {
		$result = MYSQL_QUERY($query) or $this->print_error("insert", $query);
		$id = MYSQL_INSERT_ID();
		return $id;
	}

	function print_error($method, $query) {
		$msg = mysql_error()."<br><b>Query:</b> $query"; 
		error($msg, "MySQL", $method);
	}

	/**
	  Executes SQL select statement
	  @query	sql query
	  @assoc	if false, return array is numeric
	  @return	result set as array
	*/
	function select($query, $assoc = false) {
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
	  Executes SQL statement
	  @query	sql query
	  @return	result set with single row
	*/
	function executeSql($query) {
		$result = MYSQL_QUERY($query) or $this->print_error("executeSql", $query);
		$result = MYSQL_FETCH_ARRAY($result, MYSQL_ASSOC);
		return $result;
	}

	/**
	  Executes SQL update statement
	  @query	update statement
	  @return	number of affected rows
	*/
	function update($query) {
		$result = MYSQL_QUERY($query) or $this->print_error("update", $query);
		$rows = MYSQL_AFFECTED_ROWS();
		return $rows;
	}
}
?>