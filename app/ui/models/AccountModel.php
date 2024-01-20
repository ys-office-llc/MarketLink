<?php 
class AccountModel extends BasicModel
{
  private $table = 'account';

  public function create($param)
  {
    return parent::create($this->table, $param);
  }

  public function update($param)
  {
    $where = array();

    $where[] = 'id = :id';

    parent::update($this->table, $param, $where);
  }

  public function delete($param)
  {
    parent::delete($this->table, $param);
  }

  public function gets()
  {
    return parent::gets($this->table);
  }

  public function get($id, $user_id = null)
  {
    return parent::get($this->table, $id, $user_id);
  }

  public function desc()
  {
    return parent::desc($this->table);
  }

  public function getTableValues()
  {
    return parent::getTableValues($this->table);
  }

  public function exists($param)
  {
    $where = array();
    $where[] = 'user_name = :user_name';
    return parent::exists($this->table, $param, $where);
  }

  public function getUserRecord($user_name)
  {
      $sql =
        "SELECT *
         FROM   $this->table
         WHERE  user_name = :user_name AND
                deleted   = :deleted";

      return $this->getRecord(
                      $sql,
                      array(
                        ':user_name' => $user_name,
                        ':deleted' => 0,
                      )
                    );
  }

  public function verifyToken($user_name, $one_time_token)
  {

      $sql =
        "SELECT *
         FROM   $this->table
         WHERE  user_name = :user_name AND
                one_time_token = :one_time_token AND
                DATE_ADD(one_time_token_created_at, INTERVAL 1 HOUR) > NOW() AND
                deleted = :deleted";

      $record = $this->getRecord(
                         $sql,
                           array(
                             ':user_name' => $user_name,
                             ':one_time_token' => $one_time_token,
                             ':deleted' => 0,
                           )
                         );

      if ($record) {

        return true;
      } else {

        return false;
      }
  }

  public function getCurrentPassword($user_name)
  {
    $param = array();

    $sql = "
      SELECT *
      FROM $this->table
      WHERE user_name = :user_name AND deleted = :deleted";

    $param['user_name'] = $user_name;
    $param['deleted'] = 0;

    return $this->getRecord($sql, $param)['password'];
  }

  public function showTables()
  {

    return parent::showTables();
  }
}
