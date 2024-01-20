<?php
class ResearchWatchListController extends BasicController
{
  const _INDEX = 'research_watch_list';
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

    $user  = $this->_session->get('user');

    if ($user['market_screening'] !== 'enable') {

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

  private function verify($data)
  {
    $errors = array();

    return $errors;
  }

  public function postAction()
  {

    $delete_by_checked = null;
    $delete_by_id      = null;

    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {

      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {

      return $this->redirect('/');
    }

    $delete_by_checked = $this->_request
                              ->getPost('delete_by_checked');
    $delete_by_id      = $this->_request
                             ->getPost('delete_by_id');

    if ($delete_by_checked) {

      foreach ($this->_request->getPosts()['id'] as $id => $value) {

        $this->_connect_model
             ->get($this->_controller)
             ->update(
                 array(
                   'id' => $id,
                   'user_id' => $this->_user['id'],
                   'delete_request' => 1,
                 )
               );
      }
    } elseif ($delete_by_id) {

      $this->_connect_model
           ->get($this->_controller)
           ->update(
               array(
                 'id' => key($delete_by_id),
                 'user_id' => $this->_user['id'],
                 'delete_request' => 1,
               )
             );
    }

    return $this->render(
      array(
        self::_INDEX . 's' => $this->_connect_model
                                   ->get($this->_controller)
                                   ->gets($this->_user['id']),
        'view_path' => $this->_view_path,
        '_token' => $this->getToken($this->_view_path . '/' . self::_POST),
      ), self::_LIST);
  }

}
