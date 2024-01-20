<?php

class ResearchFreeMarketsSearchController extends BasicController
{
  const _INDEX = 'research_free_markets_search';
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

  public function getAction($params)
  {
    $store      = null;
    $data       = null;
    $http_query = array();

    $user  = $this->_session->get('user');

    if ($user['use_experimental_function'] !== 'enable' or
        $user['market_screening'] !== 'enable') {

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

    if (!strlen($data['name'])) {
      $errors[] = 'タイトルを入力してください';
    }

    if (strlen($data['min_price']) > 0 and
        !is_numeric($data['min_price'])) {
      $errors[] = '商品価格の下限値は数値のみ入力可能です';
    }

    if (strlen($data['max_price']) > 0 and
        !is_numeric($data['max_price'])) {
      $errors[] = '商品価格の上限値は数値のみ入力可能です';
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
    if (!$this->checkToken($this->_view_path.'/'.self::_POST, $token)) {

      return $this->redirect('/');
    }

    $type = $this->_request->getSubmitType();

    if (preg_match(
          "/(delete_by_checked|export_csv)/",
          $type,
          $matches
        )
    ) {

      return $this->batchProcessing($type);
    }

    $data = $this->_connect_model
                 ->get($this->_controller)
                 ->desc();
    $data = $this->fillValue($data);
    $data = $this->serializer($this->_controller, $data);


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

  public function batchProcessing($type)
  {
    $results           = array();
    $export_csv        = null;
    $delete_by_checked = null;
    $delete_by_id      = null;

    $to_csv = array();

    $this->_user = $this->_session->get('user');

    $export_csv        = $this->_request
                              ->getPost('export_csv');
    $delete_by_checked = $this->_request
                              ->getPost('delete_by_checked');

    $null_to_s = function($string)
                 {

                   if (is_null($string)) {

                     return (string)$string;
                   } else {

                     return $string;
                   }
                 };

    if ($export_csv) {

      $header = array(
                  'タイトル',
                  'アクション',
                  'ChatWork宛先',
                  '在庫',
                  '商品 > タイトル > すべてを含む',
                  '商品 > タイトル > いずれかを含む',
                  '商品 > タイトル > 含めない',
                  '商品 > 説明文 > いずれかを含む',
                  '商品 > 説明文 > 含めない',
                  '商品 > ランク > メルカリ',
                  '商品 > ランク > ラクマ',
                  '商品 > ランク > フリル',
                  '商品 > 価格（最小）',
                  '商品 > 価格（最大）',
                  '出品者 > 含める',
                  '出品者 > 含めない',
                );

      $to_csv[] = $header;

      foreach ($this->_request->getPosts()['id'] as $id => $value) {

        $data = $this->_connect_model
                     ->get($this->_controller)
                     ->get(
                         $id,
                         $this->_user['id']
                       );

        $body = array(
                  $data['name'],
                  $data['action'],
                  $data['chatwork_to'],
                  $data['stock'],
                  $data['title_include_everything'],
                  $data['title_include_either'],
                  $data['title_not_include'],
                  $data['description_include_either'],
                  $data['description_not_include'],
                  $data['rank_mercari'],
                  $data['rank_rakuma'],
                  $data['rank_fril'],
                  $data['min_price'],
                  $data['max_price'],
                  $data['seller_include'],
                  $data['seller_not_include'],
               );

        $to_csv[] = $body;
      }

      $results = $this->exportCSV(
                          self::_INDEX.$this->_datetime
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

}
