<?php
class ResearchStoresController extends BasicController
{
  const _INDEX = 'research_stores';
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

      $data = $this->unserializer($this->_controller, $data);

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
    $results           = array();
    $export_csv        = null;
    $delete_by_checked = null;
    $delete_by_id      = null;

    $to_csv = array();

    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {

      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {

      return $this->redirect('/');
    }

    $export_csv        = $this->_request
                              ->getPost('export_csv');
    $delete_by_checked = $this->_request
                              ->getPost('delete_by_checked');
    $delete_by_id      = $this->_request
                              ->getPost('delete_by_id');

    if ($export_csv) {

      $to_csv[] = array(
                    'ストア',
                    'メーカー',
                    '商品タイトル',
                    '商品ランク',
                    '付属品',
                    '備考',
                    '商品価格',
                    '在庫',
                    'リンク',
                  );
      foreach ($this->_request->getPosts()['id'] as $id => $value) {

        $data = $this->_connect_model
                     ->get($this->_controller)
                     ->get(
                         $id,
                         $this->_user['id']
                       );

        $to_csv[] = array(
                      $this->resolveStoreName($data['store']),
                      $data['maker'],
                      $data['name'],
                      $data['rank'],
                      $data['accessories'],
                      $data['remarks'],
                      number_format($data['price']),
                      $data['stock'],
                      $data['link'],
                    );
      }

      $results = $this->exportCSV(
                          'stores_'.$this->_datetime
                                         ->format('YmdHis'),
                          $to_csv
                        );
    } else if ($delete_by_checked) {

      foreach ($this->_request->getPosts()['id'] as $id => $value) {

        $this->_connect_model
             ->get($this->_controller)
             ->update(
                 array(
                   'id' => $id,
                   'user_id' => $this->_user['id'],
                   'deleted' => 1,
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
                 'deleted' => 1,
               )
             );
    }

    return $this->render(
      array(
        'errors' => $results['errors'],
        self::_INDEX . 's' => $this->_connect_model
                                   ->get($this->_controller)
                                   ->gets($this->_user['id']),
        'view_path' => $this->_view_path,
        '_token' => $this->getToken($this->_view_path . '/' . self::_POST),
      ), self::_LIST);
  }

  private function resolveStoreName($store_en)
  {

    switch ($store_en) {

      case 'kitamura':

        return 'カメラのキタムラ';
      case 'map_camera':

        return 'マップカメラ';
      case 'champ_camera':

        return 'チャンプカメラ';
      case 'fujiya_camera':

        return 'フジヤカメラ';
      case 'camera_no_naniwa':

        return 'カメラのナニワ';
      case 'hardoff':

        return 'ハードオフ';
      default:

        return '不明';
    }
  }

}
