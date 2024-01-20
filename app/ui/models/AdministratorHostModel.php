<?php 

class AdministratorHostModel extends SystemItemModel
{
  public function create($param)
  {

    return parent::create($this->resolve(), $param);
  }

  public function update($param)
  {

    $where = array();
    $where[] = 'id = :id';

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

  public function get($id)
  {

    return parent::get($this->resolve(), $id, $user_id = null);
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
