<?php
class ResearchNewArrivalController extends BasicController
{
  const _INDEX = 'research_new_arrival';
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
    $store      = null;
    $data       = null;
    $http_query = array();

    $user  = $this->_session->get('user');

    if ($user['market_screening'] !== 'enable') {

      $this->httpForbidden();
    }

    if (isset($params['store'])) {

      if ($params['store'] === 'kitamura' or
          $params['store'] === 'map_camera' or
          $params['store'] === 'champ_camera' or
          $params['store'] === 'fujiya_camera' or
          $params['store'] === 'camera_no_naniwa') {

        $store = $this->_connect_model
                      ->get('ResearchStores')
                      ->get(
                          $params['id'],
                          $this->_session
                               ->get('user')['id']
                        );
        $data = $this->_connect_model
                     ->get($this->_controller)
                     ->desc();
        $data = $this->fillValue($data);

        $data['name'] = $store['name'];
        $data['chatwork_to'] = 'grant';
        $data['action'] = 'chatwork';
        $data['stock'] = 'existence';
        $data['title_include_everything'] = $store['name'];
        $data['store_price'] = $store['price'];
        $data['max_price'] = $store['price'] * 1.05;
        $data['min_price'] = $store['price'] * 0.95;
        $data['rank_'.$params['store']] = array($store['rank']);
      }
    } else if (isset($params['id'])) {

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

    $authority_level = (int)$this->getAccount()[
      'account_authority_level_id'
    ];

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
                  '商品タイトル > すべてを含む',
                  '商品タイトル > いずれかを含む',
                  '商品タイトル > 含めない',
                  '商品ランク > カメラのキタムラ',
                  '商品ランク > カメラのナニワ',
                  '商品ランク > マップカメラ',
                  '商品ランク > チャンプカメラ',
                  '商品ランク > ハードオフ',
                  '商品状態（備考欄）> いずれかを含む',
                  '商品状態（備考欄）> いずれかを含む',
                  '商品価格の範囲指定 > 下限値',
                  '商品価格の範囲指定 > 上限値',
                );

      if ($authority_level > 1) {

        array_splice(
          $header,
          8,
          0,
          array(
            '商品ランク（フジヤカメラ）',
          )
        );
      }

      $to_csv[] = $header;

      foreach ($this->_request->getPosts()['id'] as $id => $value) {

        $data = $this->_connect_model
                     ->get($this->_controller)
                     ->get(
                         $id,
                         $this->_user['id']
                       );

        if ($authority_level === 1) {

          unset($data['rank_fujiya_camera']);
        }

        $body = array(
                  $data['name'],
                  $data['action'],
                  $data['chatwork_to'],
                  $data['stock'],
                  $data['title_include_everything'],
                  $data['title_include_either'],
                  $data['title_not_include'],
                  $data['rank_kitamura'],
                  $data['rank_camera_no_naniwa'],
                  $data['rank_map_camera'],
                  $data['rank_champ_camera'],
                  $data['rank_hardoff'],
                  $data['min_price'],
                  $data['max_price'],
                  $data['remarks_include_either'],
                  $data['remarks_not_include'],
               );

        if ($authority_level > 1) {

          array_splice(
            $body,
            8,
            0,
            array(
              $data['rank_fujiya_camera'],
            )
          );
        }

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
