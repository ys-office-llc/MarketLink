<?php 
class ItemModel extends BasicModel
{
  private function resolve()
  {
    return implode('_', $this->convert());
  }

  public function create($param)
  {
    return  parent::create($this->resolve(), $param);
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

  public function getsByStateId($user_id, $state_id)
  {

    $sql = sprintf("
      SELECT *
      FROM %s
      WHERE deleted = :deleted",
      $this->resolve()
    );

    $param[':user_id']                 = $user_id;
    $param[':yahoo_auctions_state_id'] = $state_id;
    $param[':ebay_us_state_id']        = $state_id;
    $param[':amazon_jp_state_id']      = $state_id;
    $param[':deleted']                 = 0;
    $sql .= ' AND user_id = :user_id';
    $sql .= ' AND ( yahoo_auctions_state_id = :yahoo_auctions_state_id';
    $sql .= ' OR    ebay_us_state_id        = :ebay_us_state_id';
    $sql .= ' OR    amazon_jp_state_id      = :amazon_jp_state_id )';

    return $this->getAllRecord($sql, $param);
  }

  public function existsSku($param)
  {
    $where = array();
    $where[] = 'stock_keeping_unit = :stock_keeping_unit';
    return parent::exists($this->resolve(), $param, $where);
  }

}
