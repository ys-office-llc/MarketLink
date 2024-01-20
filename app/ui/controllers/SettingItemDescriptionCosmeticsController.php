<?php
class SettingItemDescriptionCosmeticsController extends BasicController
{
  const _INDEX = 'description_cosmetics';
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

    $user = $this->_session->get('user');

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

    $user = $this->_session->get('user');

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

    return $this->render(
      array(
        self::_INDEX => $data,
        'view_path'  => $this->_view_path,
        '_token'     => $this->getToken($this->_view_path . '/' . self::_POST)
      )
    );
  }

  private function verify($data)
  {
    $errors = array();

     if (!strlen($data['name'])) {

      $errors[] = '外観名を入力してください';
    }

    if (!strlen($data['description_cosmetics_ja'])) {

      $errors[] = '外観の日本語を入力してください';
    }

    if (!strlen($data['description_cosmetics_en'])) {

      $errors[] = '外観の英語を入力してください';
    }

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

    $successes = array();
    $render = array(
      'errors'      => $this->verify($data),
      'successes'   => $successes,
      self::_INDEX  => $data,
      'view_path'   => $this->_view_path,
      '_token'      => $this->getToken($this->_view_path . '/' . self::_POST),
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
