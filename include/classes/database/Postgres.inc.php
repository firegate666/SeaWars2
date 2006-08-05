<?
/**
 * MySQL Wrapper
 * in fact, this isn't yet a wrapper, much improved has
 * to be done
 */
class Postgres extends SQL {

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
		
		if(($this->dblink != null) && pg_ping($this->dblink)) // connection still exists?
			return;
		else {
			$connectionstring = "host=$dbserver dbname=$dbdatabase user=$dbuser password=$dbpassword";
	  		$this->dblink = pg_connect($connectionstring) or $this->print_error("connect", "");
		}
	}

	/**
	* Disconnects database
	* @param	Ressource $dblink	databaselink
	*/
	function disconnect() {
		if($this->dblink != null)
			pg_close($this->dblink);
	}

	/**
	* Executes SQL insert statement
	* @param	String	$query	sql query
	* @return	int	last insert id
	*/
	function insert($query, $seq=null) {
		$this->connect();
		$this->queries[] = $query;			
		$result = pg_query($this->dblink, $query) or $this->print_error("insert", $query);
		$id = 0;
		if ($seq != null) {
			$query = "SELECT currval('$seq') as id";
			$result = $this->executeSql($query);
			$id = $result['id'];
		}
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
		$result = pg_query($this->dblink, $query) or $this->print_error("select", $query);
		$return = array ();
		$counter = 0;
		if ($assoc)
			while ($line = pg_fetch_array($result, '', PGSQL_ASSOC))
				$return[$counter ++] = $line;
		else
			while ($line = pg_fetch_array($result, '', PGSQL_NUM))
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
		$result = pg_query($this->dblink, $query) or $this->print_error("executeSql", $query);
		$result = pg_fetch_array($result, '', PGSQL_ASSOC);
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
		$result = pg_query($this->dblink, $query) or $this->print_error("update", $query);
		$rows = pg_affected_rows();
		return $rows;
	}

	public function print_error($method, $query) {
		$msg = pg_last_error()."<br><b>Query:</b> $query";
		error($msg, "MySQL", $method);
	}	
	
	public function escape($string) {
		return pg_escape_string($string);
	} 
	
}
?>