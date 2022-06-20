<?php
Flight::route('GET /bids/@id', function($id){
  Flight::json(Flight::bidService()->get_item_bids($id));
});
?>