<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("rest/dao/AuctionsDAO.class.php");

$dao = new AuctionsDAO();

$op = $_REQUEST['op'];

switch ($op) {
  case 'insert':
    $username = $_REQUEST['username'];
    $fistname = $_REQUEST['firstname'];
    $secondname = $_REQUEST['secondname'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $dao->add($username, $fistname, $secondname, $email, $password);
    break;

  case 'delete':
    $id = $_REQUEST['id'];
    $dao->delete($id);
    echo "DELETED $id";
    break;

  case 'update':
    $id = $_REQUEST['id'];
    $username = $_REQUEST['username'];
    $fistname = $_REQUEST['firstname'];
    $secondname = $_REQUEST['secondname'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $dao->update($id, $username, $fistname, $secondname, $email, $password);
    echo "UPDATED $id";
    break;

  case 'get':
  default:
    $results = $dao->get_all();
    print_r($results);
    break;
}
?>