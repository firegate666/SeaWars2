<?
  /** 
    This class is supposed to be seen as Interface for different database connections
    Abstract class, no functionality
  */
  class SQL {

    /**
      Connects to Database using global parameters
      return : databaselink
    */
    function connect() {
    }

    /**
      Disconnects database
      $dblink : databaselink
    */
    function disconnect($dblink) {
    }

    /**
      Executes SQL insert statement
      return : last insert id
    */
    function insert($query) {
    }

    /**
      Executes SQL select statement
      return : result set as numeric array
    */
    function select($query) {
    }

    /**
      Executes SQL update statement
      return : number of affected rows
    */
    function update($query) {
    }
  }
?>
