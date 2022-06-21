<?php
require_once __DIR__.'/BaseDao.class.php';

class ItemDao extends BaseDao{

  /**
  * constructor of dao class
  */
  public function __construct(){
    parent::__construct("items");
  }

  public function get_user_items($user_id){
    return $this->query("SELECT * FROM items WHERE owner_id = :owner_id ORDER BY `ending` < NOW(), `ending` ASC", ['owner_id' => $user_id]);
  }

  public function get_all_sorted(){
    return$this->query("SELECT * 
    FROM `items` 
    ORDER BY `ending` < NOW(), `ending` ASC",null);
  }
}

?>