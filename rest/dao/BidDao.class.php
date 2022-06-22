<?php
require_once __DIR__.'/BaseDao.class.php';

class BidDao extends BaseDao{

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("bids");
  }

  public function get_item_bids($item_id){
    return $this->query("SELECT * FROM bids WHERE item_id = :item_id ORDER BY `amount` DESC", ['item_id' => $item_id]);
  }
  public function check_if_owner($id)
  {
    return $this->query("SELECT id FROM items WHERE owner_id =:owner_id;",['owner_id' => $id]);
  }
}
?>