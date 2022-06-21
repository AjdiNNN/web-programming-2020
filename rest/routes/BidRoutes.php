<?php
Flight::route('GET /bids/@id', function($id){
  Flight::json(Flight::bidService()->get_item_bids($id));
});
Flight::route('POST /bid', function(){
  Flight::json(Flight::bidService()->add(Flight::get('user'),Flight::request()->data->getData()));
});
?>