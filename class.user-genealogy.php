<?php
/**
* Class for user Genealogy
**/
class User_Genealogy  {
  //use to instantiate user class
  protected $user;
  //use to hold the data array from db
  protected $data_array;
  //we get the index array for each parent child siblings
  protected $index_array;

  public function __construct()
  {
    $this->user = new Get_Users();
  }

  /**
  * Set the users insert to arrays
  **/
  private function _setUserTree($arr_data, $sponsor_id = 0)
  {

    foreach($arr_data as $key => $val){
      $db_sponsor_id = $val['Sponsorid'];
      $db_user_id = $val['ID'];
      $parent_id = $val['ID'] === 0 ? 0 : $val['Sponsorid'];

      $this->data_array[$db_user_id] = $val;
      $this->index_array[$parent_id][] = $db_user_id;
    }

  }

  /**
  * display the datas
  **/
  public function display_child_nodes($parent_id)
  {
      $parent_id = $parent_id === NULL ? 0 : $parent_id;
      $root = 0;
      if (isset($this->index_array[$parent_id])) {
          foreach ($this->index_array[$parent_id] as $key => $id) {
              $name = $this->data_array[$id]["FNAME"].' '.$this->data_array[$id]["lname"];
              $user_name = $this->data_array[$id]["Username"];
              if($root == 0){
                echo '<ul>';
                  echo '<li>['.$id.']#'.$user_name.' - <a href="?method=show-user&id='.$id.'">'.$name.'</a></li>';
              }else{
                echo '<li>['.$id.']#'.$user_name.' - <a href="?method=show-user&id='.$id.'">'.$name.'</a></li>';
              }
              $this->display_child_nodes($id);
              $root++;
            }
            echo '</ul>';
      }
  }

  /**
  * Get the user by tree
  **/
  public function getUserTree()
  {
    $users = $this->user->getUsers();
    $this->_setUserTree($users);
    $this->display_child_nodes(NULL);
  }

}
