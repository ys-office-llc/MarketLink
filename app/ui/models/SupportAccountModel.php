<?php 
class SupportAccountModel extends SupportModel
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

  public function gets($user_id)
  {
    return parent::gets($this->resolve(), $user_id);
  }

  public function get($id, $user_id = null)
  {
    return parent::get($this->resolve(), $id, $user_id);
  }

  public function desc()
  {
    return parent::desc($this->resolve());
  }

  public function getTableValues()
  {
    return parent::getTableValues($this->resolve());
  }

  public function getCurrentPassword($user_name)
  {
    $param = array();

    $sql = sprintf("
      SELECT *
      FROM %s
      WHERE user_name = :user_name AND deleted = :deleted",
      $this->resolve()
    );

    $param['user_name'] = $user_name;
    $param['deleted']   = 0;

    return $this->getRecord($sql, $param)['password'];
  }

  public function updatedWithinThePeriod($id, $colmun, $period)
  {

    $sql = sprintf("
      SELECT id
      FROM %s
      WHERE DATE_ADD(%s, INTERVAL %s) > NOW() AND
            id = :id",
      $this->resolve(),
      $colmun,
      $period
    );

    $param['id'] = $id;

    if ($this->getRecord($sql, $param)) {

      return true;
    } else {

      return false;
    }
  }

}
