<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function _pre($arr)
{
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
}

include('class.mysql-database.php');
include('class.get-users.php');
include('class.user-genealogy.php');


$user = new Get_Users;

$ret_user = new User_Genealogy;

if(isset($_GET)
  && isset($_GET['method'])
  && $_GET['method'] == 'show-user'
) {
  if(isset($_GET['id'])) {
    $id = $_GET['id'];
  }

  $ret = $user->showUser($id);
  _pre($ret);
}else{
  $ret_user->getUserTree();
}
