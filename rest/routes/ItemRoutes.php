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
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
  clearstatcache();
  // Check if file already exists
  //var_dump($target_file);
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
  }
  
  // Check file size
  if ($file["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }
  
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
  }
  
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
    if (move_uploaded_file($file["tmp_name"], $target_dir.$target_file)) {
      echo "The file ". htmlspecialchars( basename($file["name"])). " has been uploaded.";
    } else {
      echo "Sorry, there was an error uploading your file.";
    }
  }
  //Flight::json(Flight::noteService()->add(Flight::get('user'), Flight::request()->data->getData()));
});

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
function wh_log($log_msg)
{
    $log_filename = "log";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
} 
?>