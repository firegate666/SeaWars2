<?
  class MySQL extends SQL{

    /**
      Connects to MySQL Database using global parameters
      $dbserver
      $dbuser
      $dbpassword
      $dbdatabase
      
      return : databaselink
    */
    function connect() {
      global $dbserver;
      global $dbuser;
      global $dbpassword;
      global $dbdatabase;
      $dblink = MYSQL_CONNECT($dbserver, $dbuser, $dbpassword) or die ( "<H3>MySQL error: Databaseserver not responding.</H3>");
      MYSQL_SELECT_DB($dbdatabase) or die ( "<H3>MySQL error: Database not available.</H3>");
      return $dblink;
    }

    /**
      Disconnects database
      $dblink : databaselink
    */
    function disconnect($dblink) {
      MYSQL_CLOSE($dblink);
    }

    /**
      Executes SQL insert statement
      return : last insert id
    */
    function insert($query) {
      $dblink = $this->connect();
      $result = MYSQL_QUERY($query) or die("MySQL insert error: ".mysql_error());
      $id = MYSQL_INSERT_ID();
      $this->disconnect($dblink);
      return $id;
    }

    /**
      Executes SQL select statement
      return : result set as numeric array
    */
    function select($query) {
      $dblink = $this->connect();
      $result = MYSQL_QUERY($query) or die("MySQL select error: ".mysql_error());
      $return = "";
      $counter = 0;
      while($line=MYSQL_FETCH_ARRAY($result, MYSQL_NUM)) $return[$counter++]=$line;
      $this->disconnect($dblink);
      return $return;
    }
    
    /**
      Executes SQL statement
      return : result set
    */
    function executeSql($query) {
      $dblink = $this->connect();
      $result = MYSQL_QUERY($query) or die("MySQL select error: ".mysql_error());
      $result = MYSQL_FETCH_ARRAY($result, MYSQL_ASSOC);
      $this->disconnect($dblink);
      return $result;      
    }

    /**
      Executes SQL update statement
      return : number of affected rows
    */
    function update($query) {
      $dblink = $this->connect();
      $result = MYSQL_QUERY($query) or die("MySQL update error: ".mysql_error());
      $rows = MYSQL_AFFECTED_ROWS();
      $this->disconnect($dblink);
      return $rows;
    }
  }
?>
