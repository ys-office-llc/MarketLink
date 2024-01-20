<?php 
class ResearchNewArrivalModel extends ResearchModel
{
  public function create($param)
  {
    return parent::create($this->resolve(), $param);
  }

  public function update($param)
  {
    $where = array();

    $where[] = 'id = :id';
    $where[] = 'user_id = :user_id';

    parent::update($this->resolve(), $param, $where);
  }

  public function delete($param)
  {
    parent::delete($this->resolve(), $param);
  }

  public function gets($user_id)
  {
    return parent::gets($this->resolve(), $user_id);
  }

  public function get($user_id, $id)
  {
    return parent::get($this->resolve(), $user_id, $id);
  }

  public function desc()
  {
    return parent::desc($this->resolve());
  }

  public function getTableValues()
  {
    return parent::getTableValues($this->resolve());
  }

  public function counter($user_id)
  {
    $param = array();
    $where = array();
    $param['user_id'] = $user_id;
    $param['deleted'] = 0;
    $where[] = 'user_id = :user_id';
    $where[] = 'deleted = :deleted';

    return parent::counter($this->resolve(), $param, $where);
  }

  public function getIdByName($user_id, $name)
  {

    return parent::getIdByName($this->resolve(), $user_id, $name);
  }

}
