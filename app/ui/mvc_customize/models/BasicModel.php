<?php 
class BasicModel extends ExecuteModel
{
  const DO_DELETE = 1;

  private $user_id = null;

  public function setUserId($user_id)
  {
    $this->user_id = $user_id;
  }

  protected function create($table, $param)
  {
    $user_id   = null;
    $assign_id = null;

    if (isset($param['user_id'])) {

      $user_id = $param['user_id'];
    }

    $assign_id = $this->assignId($user_id, $table);

    $param['id'] = $assign_id;
    $stmt = $this->execute(
      $this->buildInsert($table, $param),
      $this->buildParam($param)
    );

    return $param['id'];
  }

  protected function update($table, $param, $where)
  {
    $stmt = $this->execute(
      $this->buildUpdate($table, $param, $where),
      $this->buildParam($param)
    );
  }

  protected function delete($table, $param)
  {
    $set   = array();
    $where = array();

    $set['id']         = $param['id'];
    //$set['user_id']    = $param['user_id'];
    $set['deleted']    = self::DO_DELETE;
    $set['deleted_at'] = $param['deleted_at'];

    $where[] = 'id = :id';
    //$where[] = 'user_id = :user_id';

    $stmt = $this->execute(
      $this->buildUpdate($table, $set, $where),
      $this->buildParam($set)
    );
  }

  protected function deleteByParameters($table, $param)
  {
    $set      = array();
    $where    = array();
    $datetime = new DateTime();

    $set['deleted']    = self::DO_DELETE;
    $set['deleted_at'] = $datetime->format('Y-m-d H:i:s');

    foreach ($param as $key => $value) {

      $set[$key] = $value;
      $where[]   = sprintf("%s = :%s", $key, $key);
    }

    $stmt = $this->execute(
      $this->buildUpdate($table, $set, $where),
      $this->buildParam($set)
    );
  }

  protected function exists($table, $param, $where)
  {
    $where[] = 'deleted = :deleted';
    $param['deleted'] = 0;

    $sql = sprintf("
      SELECT COUNT(id) AS count
      FROM %s WHERE %s",
      $table,
      implode(' AND ', $where)
    );

    $records = $this->getRecord(
                 $sql,
                 $this->buildParam($param)
               );

    if ($records['count'] === '0') {

      return false;
    } else {

      return true;
    }
  }

  protected function gets($table, $user_id = null)
  {
    $sql = "
      SELECT *
      FROM $table
      WHERE deleted = :deleted";

    if (isset($user_id)) {
      $param[':user_id'] = $user_id;
      $sql .= " AND user_id = :user_id";
    }
    $param[':deleted'] = 0;

    return $this->getAllRecord($sql, $param);
  }

  protected function getsByParameters($table, $param, $where)
  {
    $sql = sprintf("
      SELECT *
      FROM %s WHERE %s",
      $table,
      implode(' AND ', $where)
    );

    $records = $this->getAllRecord(
                 $sql,
                 $this->buildParam($param)
               );

    return $records;
  }

  protected function get($table, $id, $user_id)
  {
    $param = array();

    $sql = "
      SELECT *
      FROM $table
      WHERE id = :id AND deleted = :deleted";

    if (isset($user_id)) {
      $sql .= " AND user_id = :user_id";
      $param['user_id'] = $user_id;
    }

    $param['id'] = $id;
    $param['deleted'] = 0;

    return $this->getRecord($sql, $param);
  }

  protected function getIdByName($table, $user_id, $name)
  {
    $param  = array();
    $record = null;
    $sql    = null;

    $sql = "
      SELECT *
      FROM   $table
      WHERE  name    = :name AND
             deleted = :deleted
    ";

    if (isset($user_id)) {
      $sql .= " AND user_id = :user_id";
      $param['user_id'] = $user_id;
    }

    $param['name']    = $name;
    $param['deleted'] = 0;

    $record = $this->getRecord($sql, $param);

    if ($record) {

      return (int)$record['id'];
    } else {

      return 0;
    }
  }

  protected function counter($table, $param, $where)
  {
    $sql = sprintf("
      SELECT COUNT(id) AS count
      FROM %s WHERE %s",
      $table,
      implode(' AND ', $where)
    );

    $records = $this->getRecord(
                 $sql,
                 $this->buildParam($param)
               );

    return (int)$records['count'];
  }

  protected function counterCurrentDate($table, $param, $where)
  {
    $sql = sprintf("
      SELECT COUNT(id) AS count
      FROM %s WHERE created_at = CURRENT_DATE() AND %s",
      $table,
      implode(' AND ', $where)
    );

    $records = $this->getRecord(
                 $sql,
                 $this->buildParam($param)
               );

    return (int)$records['count'];
  }

  protected function getsByParameters1Y1M($table, $ym, $param, $where)
  {
    $sql = sprintf("
      SELECT *
      FROM %s WHERE DATE_FORMAT(created_at, '%%Y%%m') = %s AND %s",
      $table,
      $ym,
      implode(' AND ', $where)
    );

    $records = $this->getAllRecord(
                 $sql,
                 $this->buildParam($param)
               );

    return $records;
  }

  protected function getsByParametersYMtoYM(
    $table,
    $ym_c,
    $ym_p,
    $param,
    $where
  )
  {
    $sql = sprintf("
      SELECT *
      FROM %s
      WHERE DATE_FORMAT(created_at, '%%Y%%m') BETWEEN %s AND %s AND %s",
      $table,
      $ym_p,
      $ym_c,
      implode(' AND ', $where)
    );

    $records = $this->getAllRecord(
                 $sql,
                 $this->buildParam($param)
               );

    return $records;
  }

  protected function getsByParameters1Y($table, $y, $param, $where)
  {
    $sql = sprintf("
      SELECT *
      FROM %s WHERE DATE_FORMAT(created_at, '%%Y') = %s AND %s",
      $table,
      $y,
      implode(' AND ', $where)
    );

    $records = $this->getAllRecord(
                 $sql,
                 $this->buildParam($param)
               );

    return $records;
  }

  protected function desc($table)
  {
    return $this->getDesc($table);
  }

  protected function getColumns($table, $column)
  {
    $sql = "
      SHOW COLUMNS
      FROM {$table}
      LIKE '{$column}'";

    return $this->getRecord($sql);
  }

  private function getValues($table)
  {
    $records = array();
    $param   = array(':deleted' => 0);

    $sql = "
      SELECT id,name
      FROM   {$table}
      WHERE  deleted = :deleted";

    if ($this->getColumns($table, 'user_id')) {
      $param[':user_id'] = $this->user_id;
      $sql .= " AND user_id = :user_id";
    }

    foreach ($this->getAllRecord($sql, $param) as $key => $value) {
      $records[$value['id']] = array('name' => $value['name']);
    }

    return $records;
  }

  protected function getTableValues($table)
  {
    $table_values = array();

    foreach ($this->showTables() as $key => $value) {
      if (preg_match("/^{$table}_/", $value)) {
        $table_values[$value] = $this->getValues($value);
        $table_values[$value][0] = array('name' => '----');
        // http://php.net/manual/ja/array.sorting.php
        ksort($table_values[$value]);
        // asort($table_values[$value]);
      }
    }

    return $table_values;
  }

  protected function convert()
  {
    $class_paths = array();

    // Modle 文字列を削除するために 0, -5
    $class = substr(get_class($this), 0, -5);
    preg_match_all("/[A-Z]/", $class, $matches);
    $classes = preg_split("/[A-Z]/", $class);
    $classes = array_filter($classes, "strlen");
    $classes = array_values($classes);

    foreach ($classes as $index => $value) {
      $class_paths[] = strtolower($matches[0][$index]) . $value;
    }

    return $class_paths;
  }

}
