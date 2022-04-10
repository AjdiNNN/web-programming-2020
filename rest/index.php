<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'dao/UserDao.class.php';
require_once '../vendor/autoload.php';

Flight::register('userDao', 'UserDao');

// CRUD operations for users entity

/**
* List all users
*/
Flight::route('GET /users', function(){
  Flight::json(Flight::userDao()->get_all());
});

/**
* List invidiual user
*/
Flight::route('GET /user/@id', function($email){
  Flight::json(Flight::userDao()->get_by_id($email));
});
/**
* Check if email exists
*/
Flight::route('GET /user/email/@email', function($email){
  Flight::json(Flight::userDao()->check_email($email));
});
/**
* Check if username exists
*/
Flight::route('GET /user/@username', function($username){
  Flight::json(Flight::userDao()->check_user($username));
});
/**
* add user
*/
Flight::route('POST /user', function(){
  Flight::json(Flight::userDao()->add(Flight::request()->data->getData()));
});

/**
* update user
*/
Flight::route('PUT /user/@id', function($id){
  $data = Flight::request()->data->getData();
  $data['id'] = $id;
  Flight::json(Flight::userDao()->update($data));
});

/**
* delete user
*/
Flight::route('DELETE /user/@id', function($id){
  Flight::userDAO()->delete($id);
  Flight::json(["message" => "deleted"]);
});

Flight::start();
?>