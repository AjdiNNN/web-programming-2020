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
  Flight::json(Flight::userDAO()->get_all());
});

/**
* List invidiual user
*/
Flight::route('GET /user/@id', function($id){
  Flight::json(Flight::userDAO()->get_by_id($id));
});

/**
* add user
*/
Flight::route('POST /user', function(){
  Flight::json(Flight::userDAO()->add(Flight::request()->data->getData()));
});

/**
* update user
*/
Flight::route('PUT /user/@id', function($id){
  $data = Flight::request()->data->getData();
  $data['id'] = $id;
  Flight::json(Flight::userDAO()->update($data));
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