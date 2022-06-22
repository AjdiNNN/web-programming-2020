<?php
/**
 * @OA\Get(path="/notes", tags={"notes"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all user notes from the API. ",
 *         @OA\Parameter(in="query", name="search", description="Search critieri"),
 *         @OA\Response( response=200, description="List of notes.")
 * )
 */
Flight::route('GET /items', function(){
  Flight::json(Flight::itemService()->get_all_sorted());
});

/**
 * @OA\Get(path="/notes/{id}", tags={"notes"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of note"),
 *     @OA\Response(response="200", description="Fetch individual note")
 * )
 */
Flight::route('GET /items/@id', function($id){
  Flight::json(Flight::itemService()->get_by_id(Flight::get('user'), $id));
});

Flight::route('GET /useritems', function(){
  Flight::json(Flight::itemService()->get_user_items(Flight::get('user')));
});
/**
* @OA\Post(
*     path="/notes", security={{"ApiKeyAuth": {}}},
*     description="Add user note",
*     tags={"notes"},
*     @OA\RequestBody(description="Basic note info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="name", type="string", example="test",	description="Title of the note"),
*    				@OA\Property(property="description", type="string", example="test",	description="Short note description" ),
*           @OA\Property(property="color", type="string", example="white",	description="white, red, blue, ..." ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Note that has been created"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route('POST /item', function(){
  $request = Flight::request();
  $file = $request->files['imageInput'];
  $target_dir = "../img/items/";
  $target_file = $file['name'];
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  // Check if image file is a actual image or fake image
  $check = getimagesize($file["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    $uploadOk = 0;
  }
  
  // Check file size
  if ($file["size"] > 500000) {
    Flight::json(["message" => "Your file is too large"]);
    $uploadOk = 0;
  }
  
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    Flight::json(["message" => "Format not allowed"]);
    $uploadOk = 0;
  }
  
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    return;
  // if everything is ok, try to upload file
  } else {
    $imagePath = generateRandomString(19).".".$imageFileType;
    if (move_uploaded_file($file["tmp_name"], $target_dir.$imagePath ) ) {

    } else {
      Flight::json(["message" => "Sorry, there was an error uploading your file."]);
      return;
    }
  }
  $entity = $request->data->getData();
  $entity['image'] = $imagePath;
  Flight::json(Flight::itemService()->add(Flight::get('user'),$entity));
});
function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
/**
* @OA\Put(
*     path="/notes/{id}", security={{"ApiKeyAuth": {}}},
*     description="Update user note",
*     tags={"notes"},
*     @OA\Parameter(in="path", name="id", example=1, description="Note ID"),
*     @OA\RequestBody(description="Basic note info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="name", type="string", example="test",	description="Title of the note"),
*    				@OA\Property(property="description", type="string", example="test",	description="Short note description" ),
*           @OA\Property(property="color", type="string", example="white",	description="white, red, blue, ..." ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Note that has been updated"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route('PUT /notes/@id', function($id){
  
  Flight::json(Flight::noteService()->update(Flight::get('user'), $id, $data));
});

/**
* @OA\Delete(
*     path="/notes/{id}", security={{"ApiKeyAuth": {}}},
*     description="Soft delete user note",
*     tags={"notes"},
*     @OA\Parameter(in="path", name="id", example=1, description="Note ID"),
*     @OA\Response(
*         response=200,
*         description="Note deleted"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
Flight::route('DELETE /notes/@id', function($id){
  Flight::noteService()->delete(Flight::get('user'), $id);
  Flight::json(["message" => "deleted"]);
});

/**
* @OA\Post(
*     path="/notes/{id}/share", security={{"ApiKeyAuth": {}}},
*     description="Share user note",
*     @OA\Parameter(in="path", name="id", example=1, description="Note ID"),
*     tags={"notes"},
*     @OA\RequestBody(description="Recipient info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="email", type="string", example="dino.keco@ibu.edu.ba",	description="Recipient of the note")
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Note that has been shared"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
?>