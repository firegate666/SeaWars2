<?
/**
 * MySQL Wrapper
 * in fact, this isn't yet a wrapper, much improved has
 * to be done
 */
class MySQL extends SQL {

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
		$dblink = MYSQL_CONNECT($dbserver, $dbuser, $dbpassword) or die("<H3>MySQL error: Databaseserver not responding.</H3>");
		MYSQL_SELECT_DB($dbdatabase) or die("<H3>MySQL error: Database not available.</H3>");
		return $dblink;
	}

	/**
	  Disconnects database
	  @dblink	databaselink
	*/
	function disconnect($dblink) {
		MYSQL_CLOSE($dblink);
	}

	/**
	  Executes SQL insert statement
	  @query	sql query
	  @return	last insert id
	*/
	function insert($query) {
		$dblink = $this->connect();
		$result = MYSQL_QUERY($query) or die("MySQL insert error: ".mysql_error()." / Query " + $query);
		$id = MYSQL_INSERT_ID();
		$this->disconnect($dblink);
		return $id;
	}

	/**
	  Executes SQL select statement
	  @query	sql query
	  @assoc	if false, return array is numeric
	  @return	result set as array
	*/
	function select($query, $assoc = false) {
		$dblink = $this->connect();
		$result = MYSQL_QUERY($query) or die("MySQL select error: ".mysql_error()." / Query " + $query);
		$return = array ();
		$counter = 0;
		if (!$assoc)
			while ($line = MYSQL_FETCH_ARRAY($result, MYSQL_NUM))
				$return[$counter ++] = $line;
		else
			while ($line = MYSQL_FETCH_ARRAY($result, MYSQL_ASSOC))
				$return[$counter ++] = $line;
		$this->disconnect($dblink);
		return $return;
	}

	/**
	  Executes SQL statement
	  @query	sql query
	  @return	result set with single row
	*/
	function executeSql($query) {
		$dblink = $this->connect();
		$result = MYSQL_QUERY($query) or die("MySQL executeSql error: ".mysql_error()." / Query " + $query);
		$result = MYSQL_FETCH_ARRAY($result, MYSQL_ASSOC);
		$this->disconnect($dblink);
		return $result;
	}

	/**
	  Executes SQL update statement
	  @query	update statement
	  @return	number of affected rows
	*/
	function update($query) {
		$dblink = $this->connect();
		$result = MYSQL_QUERY($query) or die("MySQL update error: ".mysql_error()." / Query " + $query);
		$rows = MYSQL_AFFECTED_ROWS();
		$this->disconnect($dblink);
		return $rows;
	}
}
?>