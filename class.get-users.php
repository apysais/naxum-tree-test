<?php
/**
* class to get users in database
**/
class Get_Users {
  //instantiate the class database
  protected $db;

  public function __construct()
  {
    $this->db = new Database_MySQL();
    $this->db->connect();
  }

  /**
  * Get users using sql query
  **/
  public function getUsers()
  {
    $sql_query_str = "select * from users order by Sponsorid";
    $this->db->sql($sql_query_str);
    $res = $this->db->getResult();
    return $res;
  }

  /**
  * Get user by id using sql query
  **/
  public function showUser($id)
  {
    $sql_query_str = "select * from users where ID = {$id} order by Sponsorid";
    $this->db->sql($sql_query_str);
    $res = $this->db->getResult();
    return $res;
  }

}
