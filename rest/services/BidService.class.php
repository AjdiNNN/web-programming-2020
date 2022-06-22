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
    $entity['bidder_id'] = $user['id'];
    //$this->dao->check_if_owner($user['id']));
    $check = $this->dao->check_if_owner($user['id']);
    if (!empty($check))
    {
      throw new Exception("This is hack you will be traced, be prepared :)");
    }
    return parent::add($user, $entity);
  }
}
?>