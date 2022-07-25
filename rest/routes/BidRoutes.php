<?php
/**
 * @OA\Get(path="/bids/{id}", tags={"bids"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Id of item"),
 *     @OA\Response(response="200", description="Fetch bids for item of that id")
 * )
 */
Flight::route('GET /bids/@id', function($id){
  Flight::json(Flight::bidService()->get_item_bids($id));
});
/**
* @OA\Post(
*     path="/bid",
*     description="Register to the system",
*     tags={"bids"},
*     security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic user info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="amount", type="integer", example="123",	description="Amount of bid"),
*    				@OA\Property(property="item_id", type="integer", example="15",	description="Item id of the bid" ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="Success bid accepted"
*     ),
*     @OA\Response(
*         response=500,
*         description="Bidding on own item or invalid item"
*     )
* )
*/
Flight::route('POST /bid', function(){
  Flight::json(Flight::bidService()->add(Flight::get('user'),Flight::request()->data->getData()));
});

?>