<?php
/*
 * MySQL Database
 * @Author APYC
 * @Since 07-12-2015
 */
class Database_MySQL{

	private $db_host = "localhost";  // Change as required
	private $db_user = "root";  // Change as required
	private $db_pass = "potpot4182!";  // Change as required
	private $db_name = "naxum_db";	// Change as required

	private $con = false; // Check to see if the connection is active
  private $myconn = ""; // This will be our mysqli object
	private $result = array(); // Any results from a query will be stored here
  private $myQuery = "";// used for debugging process with SQL return
  private $numResults = "";// used for returning the number of rows

	/**
  * Connect to database
  **/
	public function connect()
  {
		if(!$this->con){
			$this->myconn = new mysqli($this->db_host,$this->db_user,$this->db_pass,$this->db_name);  // mysql_connect() with variables defined at the start of Database class
            if($this->myconn->connect_errno > 0){
                array_push($this->result,$this->myconn->connect_error);
                return false; // Problem selecting database return FALSE
            }else{
                $this->con = true;
                return true; // Connection has been made return TRUE
            }
        }else{
            return true; // Connection has already been made return TRUE
        }
	}

	/**
  * Disconnect to database
  **/
  public function disconnect()
  {
    	// If there is a connection to the database
    	if($this->con){
    		// We have found a connection, try to close it
    		if($this->myconn->close()){
    			// We have successfully closed the connection, set the connection variable to false
    			$this->con = false;
				// Return true tjat we have closed the connection
				return true;
			}else{
				// We could not close the connection, return false
				return false;
			}
		}
  }

  /**
  * use for generic sql query, full sql query
  **/
	public function sql($sql)
  {
		$query = $this->myconn->query($sql);
    $this->myQuery = $sql; // Pass back the SQL
		if($query){
			// If the query returns >= 1 assign the number of rows to numResults
			$this->numResults = $query->num_rows;
			// Loop through the query results by the number of rows returned
			for($i = 0; $i < $this->numResults; $i++){
				$r = $query->fetch_array();
               	$key = array_keys($r);
               	for($x = 0; $x < count($key); $x++){
               		// Sanitizes keys so only alphavalues are allowed
                   	if(!is_int($key[$x])){
                   		if($query->num_rows >= 1){
                   			$this->result[$i][$key[$x]] = $r[$key[$x]];
						}else{
							$this->result = null;
						}
					}
				}
			}
			return true; // Query was successful
		}else{
			array_push($this->result,$this->myconn->error);
			return false; // No rows where returned
		}
	}

  /**
  * check if table exists for use with queries
  **/
	private function tableExists($table)
  {
	   $tablesInDb = $this->myconn->query('SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
      if($tablesInDb){
      	if($tablesInDb->num_rows == 1){
              return true; // The table exists
          }else{
          	array_push($this->result,$table." does not exist in this database");
              return false; // The table does not exist
          }
      }
  }
	/**
  * select function
  **/
	public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null)
  {
		// Create query from the variables passed to the function
		$q = 'SELECT '.$rows.' FROM '.$table;
		if($join != null){
			$q .= ' JOIN '.$join;
		}
    if($where != null){
    	$q .= ' WHERE '.$where;
		}
    if($order != null){
        $q .= ' ORDER BY '.$order;
		}
    if($limit != null){
        $q .= ' LIMIT '.$limit;
    }
    // echo $table;
    $this->myQuery = $q; // Pass back the SQL
    // Check to see if the table exists
    if($this->tableExists($table)){
    	// The table exists, run the query
    	$query = $this->myconn->query($q);
			if($query){
				// If the query returns >= 1 assign the number of rows to numResults
				$this->numResults = $query->num_rows;
				// Loop through the query results by the number of rows returned
				for($i = 0; $i < $this->numResults; $i++){
					$r = $query->fetch_array();
          $key = array_keys($r);
        	for($x = 0; $x < count($key); $x++){
        		// Sanitizes keys so only alphavalues are allowed
          	if(!is_int($key[$x])){
            	if($query->num_rows >= 1){
            			$this->result[$i][$key[$x]] = $r[$key[$x]];
  						}else{
  							$this->result[$i][$key[$x]] = null;
  						}
						}
					}
				}
				return true; // Query was successful
			}else{
				array_push($this->result,$this->myconn->error);
				return false; // No rows where returned
			}
    }else{
    		return false; // Table does not exist
  	}
  }

  /**
  * return the data to the user
  **/
  public function getResult()
  {
      $val = $this->result;
      $this->result = array();
      return $val;
  }

  /**
  * just for debugging
  **/
  public function getSql()
  {
      $val = $this->myQuery;
      $this->myQuery = array();
      return $val;
  }

  /**
  * get number rows
  **/
  public function numRows()
  {
      $val = $this->numResults;
      $this->numResults = array();
      return $val;
  }

}
