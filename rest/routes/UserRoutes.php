<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
/**
* Check user login
* @OA\Post(
*     path="/login",
*     description="Login to the system",
*     tags={"todo"},
*     @OA\RequestBody(description="Basic user info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="email", type="string", example="dino.keco@gmail.com",	description="Email"),
*    				@OA\Property(property="password", type="string", example="1234",	description="Password" )
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="JWT Token on successful response"
*     ),
*     @OA\Response(
*         response=404,
*         description="Wrong Password | User doesn't exist"
*     )
* )
*/
Flight::route('POST /login', function(){
    $login = Flight::request()->data->getData();
    $user = Flight::userService()->get_user_by_email($login['email']);
    if (isset($user['id'])){
      if(password_verify($login['password'],$user['password'])){
        print_r("radi");
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

Flight::route('GET /email/@email', function($email){
  Flight::json(Flight::userService()->get_user_by_email($email));
});

Flight::route('GET /username/@username', function($username){
  Flight::json(Flight::userService()->get_user_by_username($username));
});

?>