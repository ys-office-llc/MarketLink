<?php
// 抽象クラス
abstract class ExecuteModel
{
  // PDOオブジェクトを保持するプロパティ
  protected $_pdo;

  // ***コンストラクター***
  public function __construct($pdo)
  {
    $this->setPdo($pdo);
  }

  // ***setPdo()メソッド***
  public function setPdo($pdo)
  {
    $this->_pdo = $pdo;
  }

  // ***execute()メソッド***
  public function execute($sql, $parameter = array())
  {
    // プリペアドステートメントを生成
    $stmt = $this->_pdo
                 ->prepare(
                     $sql,
                     array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL)
                   );
    // プリペアドステートメントを実行
    $stmt->execute($parameter);
    // 戻り値としてPDOStatementオブジェクトを返す
    return $stmt;
  }

  // ***getAllRecord()メソッド***
  public function getAllRecord($sql, $parameter = array())
  {
    $all_rec = $this->execute($sql, $parameter)
                    ->fetchAll(PDO::FETCH_ASSOC);
    return $all_rec;
  }

  // ***getRecord()メソッド***
  public function getRecord($sql, $parameter = array())
  {
    $rec = $this->execute($sql, $parameter)
                ->fetch(PDO::FETCH_ASSOC);
    return $rec;
  }

  // 佐藤追加分
  private function appendColon($param)
  {
    return ':' . $param;
  }

  protected function buildInsert($table, $param)
  {
    return sprintf(
      "INSERT INTO %s (%s) VALUES (%s)",
      $table,
      implode(',', array_keys($param)),
      implode(',', array_map(array($this, 'appendColon'), array_keys($param)))
    );
  }

  protected function buildUpdate($table, $param, $where)
  {
    $set   = array();

    foreach ($param as $key => $value) {
      $set[] = sprintf("%s = :%s", $key, $key);
    }

    return sprintf(
      "UPDATE %s SET %s WHERE %s",
      $table,
      implode(',', $set),
      implode(' AND ', $where)
    );
  }

  protected function buildParam($param)
  {
    return array_combine(
      array_map(array($this, 'appendColon'), array_keys($param)),
      array_values($param)
    );
  }

  protected function getDesc($table)
  {
    $values = array();

    $sql = "
      DESC $table
    ";

    $records = $this->getAllRecord($sql);
    foreach ($records as $key => $value) {
      $values[$value['Field']] = $value['Default'];
    }

    return $values;
  }

  protected function currentDb()
  {
    $sql = "
      SELECT database() AS db;
    ";

    return $this->getRecord($sql)['db'];

  }
  protected function showTables()
  {
    $database = $this->currentDb();
    $tables   = array();

    $sql = "
      SHOW TABLES
    ";

    $records = $this->getAllRecord($sql);

    foreach ($records as $key => $value) {
      $tables[] = $value["Tables_in_{$database}"];
    }

    return $tables;
  }

  protected function assignId($user_id, $table)
  {

    $records      = null;
    $pool_deleted = array();
    $assign_id    = null;

    if (isset($user_id)) {

      // 削除済み (deleted=1) のものがあれば, そちらを再利用する
      $sql = "
        SELECT id
        FROM   {$table}
        WHERE  user_id = :user_id AND
               deleted = :deleted
      ";

      $records = $this->getAllRecord(
                          $sql,
                          array(
                            ':user_id' => $user_id,
                            ':deleted' => 1,
                          )
                        );
    } else {

      $sql = "
        SELECT id
        FROM   {$table}
        WHERE  deleted = :deleted
      ";

      $records = $this->getAllRecord(
                          $sql,
                          array(
                            ':deleted' => 1,
                          )
                        );
    }

    foreach ($records as $key => $value) {

      $pool_deleted[] = (int)$value['id'];
    }

    if (count($pool_deleted) > 0) {

      $assign_id = array_shift($pool_deleted);

      // 再利用前に, 削除しておく
      $sql = "
        DELETE FROM $table WHERE id = :id
      ";
      $this->execute($sql, array(':id' => $assign_id));

      return $assign_id;
    } else {

      $sql = "
        SELECT MAX(id) AS global_maximum FROM $table
      ";
      $record = $this->getRecord($sql);
      if (is_null($record['global_maximum'])) {

        if (isset($user_id)) {

          return (int)sprintf("1%03d", $user_id);
        } else {

          return 1;
        }
      } else {

        if (isset($user_id)) {

          $ix = (int)(substr($record['global_maximum'], 0, -3));

          return (int)(($ix + 1) . sprintf("%03d", $user_id));
        } else {

          return (int)($record['global_maximum'] + 1);
        }
      }
    }
  }

}
