<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'dao/class AuctionsDAO.class.php';
require_once '../vendor/autoload.php';

Flight::register('auctionsDAO', 'AuctionsDAO');

// CRUD operations for users entity

/**
* List all users
*/
Flight::route('GET /users', function(){
  Flight::json(Flight::auctionsDAO()->get_all());
});

/**
* List invidiual user
*/
Flight::route('GET /user/@id', function($id){
  Flight::json(Flight::auctionsDAO()->get_by_id($id));
});

/**
* add user
*/
Flight::route('POST /user', function(){
  Flight::json(Flight::auctionsDAO()->add(Flight::request()->data->getData()));
});

/**
* update user
*/
Flight::route('PUT /user/@id', function($id){
  $data = Flight::request()->data->getData();
  $data['id'] = $id;
  Flight::json(Flight::auctionsDAO()->update($data));
});

/**
* delete user
*/
Flight::route('DELETE /user/@id', function($id){
  Flight::auctionsDAO()->delete($id);
  Flight::json(["message" => "deleted"]);
});

Flight::start();
?>