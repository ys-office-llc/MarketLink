<?php
class ResearchFreeMarketsWatchController extends BasicController
{
  const _INDEX = 'research_free_markets_watch';
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

    if ($user['use_experimental_function'] !== 'enable' or
        $user['market_screening'] !== 'enable') {

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
                    'マーケット',
                    '商品タイトル',
                    '商品ランク',
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
                      $this->resolveMarketName($data['store']),
                      $data['name'],
                      $data['rank'],
                      number_format($data['price']),
                      $data['stock'],
                      $data['link'],
                    );
      }

      $results = $this->exportCSV(
                          'free_markets_'.$this->_datetime
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

  private function resolveMarketName($store_en)
  {

    switch ($store_en) {

      case 'mercari':

        return 'メルカリ';
      case 'rakuma':

        return 'ラクマ';
      case 'fril':

        return 'フリル';
      default:

        return '不明';
    }
  }

}
