<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('POST /login', function(){
    $login = Flight::request()->data->getData();
    $user = Flight::userDao()->get_user_by_email($login['email']);
    if (isset($user['id'])){
      if(password_verify($login['password'],$user['password'])){
        unset($user['password']);
        $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
        Flight::json(['token' => $jwt]);
      }else{
        Flight::json(["message" => "Wrong password"], 404);
      }
    }else{
      Flight::json(["message" => "User doesn't exist"], 404);
    }
});

Flight::route('POST /register', function(){
  $data = Flight::request()->data->getData();
  Flight::userDao()->addUser($data);
  Flight::json(["message" => "Success"]);
});

Flight::route('GET /user', function(){
  Flight::json(Flight::get('user')['id']);
});

Flight::route('GET /email/@email', function($email){
  Flight::json(Flight::userService()->get_user_by_email($email));
});

Flight::route('GET /username/@username', function($username){
  Flight::json(Flight::userService()->get_user_by_username($username));
});

?>