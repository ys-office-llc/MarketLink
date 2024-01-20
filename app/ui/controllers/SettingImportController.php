<?php
class SettingImportController extends BasicController
{
  const _INDEX = 'import';
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

    if ($user['market_screening'] !== 'enable') {

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

    if ($user['market_screening'] !== 'enable') {

      $this->httpForbidden();
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

    return $errors;
  }

  public function postAction()
  {
    $data = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {
      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {
      return $this->redirect('/');
    }

    $type = $this->_request->getSubmitType();

    if (self::TYPE_IMPORT === $type) {

      $results = $this->csvToArray(5);

      return $this->render(
        array(
        'errors'      => $results['errors'],
        self::_INDEX  => $results['successes'],
        'view_path'   => $this->_view_path,
        '_token' => $this->getToken($this->_view_path . '/' . self::_POST),
      ), self::_GET);

    } else if (self::TYPE_CREATE === $type) {

var_dump($this->_request->getPosts()['column_a']);
var_dump($this->_request->getPosts()['column_b']);
var_dump($this->_request->getPosts()['column_c']);
var_dump($this->_request->getPosts()['column_d']);
var_dump($this->_request->getPosts()['column_e']);

    }

/*
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
*/
  }

}
