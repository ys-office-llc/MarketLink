<?php
class SettingItemMyPatternController extends BasicController
{
  const _INDEX = 'my_pattern';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  public function listAction()
  {

    $user  = $this->_session->get('user');

    if ($user['merchandise_management'] !== 'enable') {

      $this->httpForbidden();
    }

    return $this->render(
      array(
        self::_INDEX . 's' => $this->_connect_model
                                   ->get($this->_controller)
                                   ->gets($this->_session->get('user')['id']),
        'view_path' => $this->_view_path,
        '_token' => $this->getToken($this->_view_path . '/' . self::_POST),
      )
    );
  }

  public function getAction($params)
  {
    $data = null;


    $user  = $this->_session->get('user');

    if ($user['merchandise_management'] !== 'enable') {

      $this->httpForbidden();
    }

    if (isset($params['id'])) {
      $data = $this->_connect_model
                       ->get($this->_controller)
                       ->get(
                           $params['id'],
                           $this->_session
                                ->get('user')['id']
                         );

      if (!$data) {
        $this->httpNotFound();
      }

    }

    $this->set();
    return $this->render(
      array(
        self::_INDEX   => $data,
        'table_values' => $this->_connect_model
                               ->get($this->_controller)
                               ->getTableValues(),
        'view_path'    => $this->_view_path,
        '_token'     => $this->getToken($this->_view_path . '/' . self::_POST)
      )
    );
  }

  private function verify($type, $data)
  {
    $errors = array();

    switch ($type) {
      case self::TYPE_CREATE:
      case self::TYPE_UPDATE:

      if (!strlen($data['name'])) {

        $errors[] = 'マイパターン名を入力してください';
      }
  
      if ($data['category_id'] == 0) {

        $errors[] = 'カテゴリーを選択してください';
      }
  
      if ($data['grade_id'] == 0) {

        $errors[] = 'グレードを選択してください';
      }
  
      if ($data['description_id'] == 0) {

        $errors[] = '説明文を選択してください';
      }
  
      if ($data['yahoo_auctions_template_id'] == 0) {

        $errors[] = 'ヤフオクページを選択してください';
      }
  
      if ($data['ebay_us_template_id'] == 0) {

        $errors[] = 'eBay USページを選択してください';
      }
  
      if ($data['ebay_us_condition_id'] == 0) {

        $errors[] = 'eBay US条件を選択してください';
      }
  
    } // switch($type)

    return $errors;
  }

  public function postAction()
  {
    $data    = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {
      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {
      return $this->redirect('/');
    }

    $type = $this->_request->getSubmitType();
    $data = $this->_connect_model
                 ->get($this->_controller)
                 ->desc();
    $data = $this->fillValue($data);

    $this->set();
    $successes = array();
    $render = array(
      'errors'       => $this->verify($type, $data),
      'successes'    => $successes,
      self::_INDEX   => $data,
      'table_values' => $this->_connect_model
                              ->get($this->_controller)
                              ->getTableValues(),
      'view_path'    => $this->_view_path,
      '_token'       => $this->getToken($this->_view_path . '/' . self::_POST),
    );

    return $this->commit(
      $type,
      $render,
      self::_INDEX,
      $this->_controller,
      self::_GET
    );
  }

}
