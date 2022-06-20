<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/BidDao.class.php';
require_once __DIR__.'/../dao/UserDao.class.php';

class BidService extends BaseService{

  private $user_dao;

  public function __construct(){
    parent::__construct(new BidDao());
    
    $this->user_dao = new UserDao();
  }

  public function get_item_bids($item_id){
    return $this->dao->get_item_bids($item_id);
  }


  public function add($user, $entity){
    $entity['user_id'] = $user['id'];
    return parent::add($user, $entity);
  }
}
?>