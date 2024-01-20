<?php 
class MonitorInterfaceModel extends MonitorModel
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

  public function gets()
  {
    return parent::gets($this->resolve());
  }

  public function getsByDate($today)
  {
    $param = array();
    $where = array();

    $param['created_at'] = $today;
    $param['deleted']    = 0;

    $where[] = 'created_at = :created_at';
    $where[] = 'deleted    = :deleted';

    return parent::getsByParameters($this->resolve(), $param, $where);
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

}
