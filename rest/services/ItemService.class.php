<?php
require_once __DIR__.'/BaseService.class.php';
require_once __DIR__.'/../dao/ItemDao.class.php';
require_once __DIR__.'/../dao/UserDao.class.php';

class ItemService extends BaseService{

  private $user_dao;

  public function __construct(){
    parent::__construct(new ItemDao());
    
    $this->user_dao = new UserDao();
  }

  public function get_user_items($user, $search = NULL){
    return $this->dao->get_user_items($user['id'], $search);
  }

  public function add($user, $entity){
    $entity['user_id'] = $user['id'];
    return parent::add($user, $entity);
  }

  public function update($user, $id, $entity){
    $note = $this->dao->get_by_id($id);
    if ($note['user_id'] != $user['id']){
      throw new Exception("This is hack you will be traced, be prepared :)");
    }
    unset($entity['user_id']);
    unset($entity['status']);
    return parent::update($user, $id, $entity);
  }

  public function delete($user, $id){
    $note = $this->dao->get_by_id($id);
    if ($note['user_id'] != $user['id']){
      throw new Exception("This is hack you will be traced, be prepared :)");
    }
    parent::update($user, $id, ['status' => 'ARCHIVED']);
  }
}
?>