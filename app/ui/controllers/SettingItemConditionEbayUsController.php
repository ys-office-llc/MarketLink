<?php
class SettingItemConditionEbayUsController extends BasicController
{
  const _INDEX = 'condition';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  private function barrier()
  {

    if (!preg_match(
          "/^enable$/",
          $this->_session->get('user')['merchandise_management']
        )) {

      $this->httpForbidden();
    }
  }

  public function listAction()
  {

    $this->barrier();
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

    $this->barrier();
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
        '_token'       => $this->getToken($this->_view_path . '/' . self::_POST)
      )
    );
  }

  private function verify($data)
  {
    $errors = array();

    if (!strlen($data['name'])) {
      $errors[] = '条件名を入力してください';
    }

    if ($data['prefs_id'] < 1) {
      $errors[] = '英語表記住所を選択してください';
    }

    if ($data['condition_id'] < 1) {
      $errors[] = 'コンディションを選択してください';
    }

    if ($data['listing_type_id'] < 1) {
      $errors[] = '出品形式を選択してください';
    }

    if (!strlen($data['quantity'])) {
      $errors[] = '数量を入力してください';
    }

    if (!strlen($data['dispatch_time_max'])) {
      $errors[] = 'ハンドリングタイムを入力してください';
    }

    return $errors;
  }

  public function postAction()
  {
    $data        = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {

      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken(
           $this->_view_path.'/'.self::_POST,
           $token
         )
    ) {

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
      'errors'       => $this->verify($data),
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
