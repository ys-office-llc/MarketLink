<?php
class ResearchYahooAuctionsSearchController extends BasicController
{
  const _INDEX = 'research_yahoo_auctions_search';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  private function setUserIdToModel($model)
  {

    $this->_connect_model
         ->get($model)
         ->setUserId(
             $this->_session->get('user')['id']
           );
  }

  private function getSearchCount($data)
  {

    $result = null;

    $html = file_get_contents($this->httpBuildQuery($data));
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $xpath = new DOMXPath($dom);

    $result = $xpath->query('//*[@id="AS-m19"]/p[1]/em')->item(0);

    if (is_null($result)) {

      return 0;
    } else {

      return $result->nodeValue;
    }
  }

  private function httpBuildQuery($data)
  {
    $param = array();

    if (strlen($data['query_include_everything']) > 0) {

      $param['va'] = $data['query_include_everything'];
    }

    if (strlen($data['query_include_either']) > 0) {

      $param['vo'] = $data['query_include_either'];
    }

    if (strlen($data['query_not_include']) > 0) {

      $param['ve'] = $data['query_not_include'];
    }

    if ((int)$data['aucminprice'] > 0) {

      $param['aucminprice'] = $data['aucminprice'];
    }

    if ((int)$data['aucmaxprice'] > 0) {

      $param['aucmaxprice'] = $data['aucmaxprice'];
    }

    if ((int)$data['aucmin_bidorbuy_price'] > 0) {

      $param['aucmin_bidorbuy_price'] = $data['aucmin_bidorbuy_price'];
    }

    if ((int)$data['aucmax_bidorbuy_price'] > 0) {

      $param['aucmax_bidorbuy_price'] = $data['aucmax_bidorbuy_price'];
    }

    if ((int)$data['category_id'] > 0) {

      $param['auccat'] = $data['category_id'];
    }

    $param['l0']        = 0;
    $param['abatch']    = 0;
    $param['istatus']   = 2;
    $param['fixed']     = 0;
    $param['gift_icon'] = 0;
    $param['charity']   = '';
    $param['ei']        = 'UTF-8';
    $param['tab_ex']    = 'commerce';
    $param['slider']    = 0;
    $param['f_adv']     = 1;
    $param['fr']        = 'auc_adv';
    $param['f']         = '0x2';

    return $this->_configure
                ->current['configure']['mvc']['controllers'][
                  'item'
                ]['yahoo']['auctions']['url']['selling'].'?'.
                http_build_query($param);
  }

  public function listAction()
  {

    $user = $this->_session->get('user');

    if ($user['market_screening'] !== 'enable') {

      $this->httpForbidden();
    }

    $this->set();
    return $this->render(
      array(
        self::_INDEX . 's' => $this->_connect_model
                                   ->get($this->_controller)
                                   ->gets($this->_session->get('user')['id']),
        'view_path' => $this->_view_path,
        'go_update' => null,
        '_token' => $this->getToken($this->_view_path . '/' . self::_POST),
      )
    );
  }

  public function getAction($params)
  {
    $store      = null;
    $data       = null;
    $http_query = array();

    $user = $this->_session->get('user');

    if ($user['market_screening'] !== 'enable') {

      $this->httpForbidden();
    }

    if (isset($params['store'])) {

      if ($params['store'] === 'kitamura' or
          $params['store'] === 'map_camera' or
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
        $data['action'] = 'all';
        $data['chatwork_to'] = 'grant';
        $data['query_include_everything'] = $store['name'];
        $data['aucminprice'] = $store['price'] * 0.5;
        $data['aucmaxprice'] = $store['price'];
        $data['aucmin_bidorbuy_price'] = $store['price'] * 0.5;
        $data['aucmax_bidorbuy_price'] = $store['price'];
      }
    } else if (isset($params['id'])) {

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

    $this->setUserIdToModel('Item');
    $this->set();
    return $this->render(
      array(
        self::_INDEX   => $data,
        'table_values' => $this->_connect_model
                               ->get($this->_controller)
                               ->getTableValues(),
        'item_table_values' => $this->_connect_model
                                    ->get('Item')
                                    ->getTableValues(),
        'view_path'    => $this->_view_path,
        'go_update'    => null,
        'search_url'   => $this->httpBuildQuery($data),
        'search_count' => $this->getSearchCount($data),
        '_token'       => $this->getToken(
                            $this->_view_path.'/'.self::_POST
                          )
      )
    );
  }

  private function verify($data)
  {

    $errors = array();

    if (!strlen($data['name'])) {

      $errors[] = 'タイトルを入力してください';
    }

    if (strlen($data['query_include_everything']) === 0 and
        strlen($data['query_include_either']) === 0) {

      $errors[] = '検索キーワード（すべてを含む・いずれかを含む）にはなんらかの文字を入力してください';
    }

    if (strlen($data['aucminbids']) > 0 and
        !is_numeric($data['aucminbids'])) {

      $errors[] = '入札数の下限値は数値のみ入力可能です';
    }

    if (strlen($data['aucmaxbids']) > 0 and
        !is_numeric($data['aucmaxbids'])) {

      $errors[] = '入札数の上限値は数値のみ入力可能です';
    }

    if (strlen($data['aucminprice']) > 0 and
        !is_numeric($data['aucminprice'])) {

      $errors[] = '現在価格の下限値は数値のみ入力可能です';
    }

    if (strlen($data['aucmaxprice']) > 0 and
        !is_numeric($data['aucmaxprice'])) {

      $errors[] = '現在価格の上限値は数値のみ入力可能です';
    }

    if (strlen($data['aucmin_bidorbuy_price']) > 0 and
        !is_numeric($data['aucmin_bidorbuy_price'])) {
      $errors[] = '即決価格の下限値は数値のみ入力可能です';
    }

    if (strlen($data['aucmax_bidorbuy_price']) > 0 and
        !is_numeric($data['aucmax_bidorbuy_price'])) {
      $errors[] = '即決価格の上限値は数値のみ入力可能です';
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

    $this->setUserIdToModel('Item');
    $this->set();
    $successes = array();
    $render = array(
      'errors'       => $this->verify($data),
      'successes'    => $successes,
      self::_INDEX   => $data,
      'table_values' => $this->_connect_model
                              ->get($this->_controller)
                              ->getTableValues(),
      'item_table_values' => $this->_connect_model
                                  ->get('Item')
                                  ->getTableValues(),
      'search_url'   => $this->httpBuildQuery($data),
      'search_count' => $this->getSearchCount($data),
      'view_path'    => $this->_view_path,
      'go_update'    => null,
      '_token'       => $this->getToken(
                          $this->_view_path.'/'.self::_POST
                        ),
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
                  '検索キーワード > すべてを含む',
                  '検索キーワード > いずれかを含む',
                  '検索キーワード > 含めない',
                  'カテゴリー',
                  '絞り込み条件 > 検索対象',
                  '絞り込み条件 > 入札数（下限）',
                  '絞り込み条件 > 入札数（上限）',
                  '絞り込み条件 > 商品価格（下限）',
                  '絞り込み条件 > 商品価格（上限）',
                  '絞り込み条件 > 即決価格（下限）',
                  '絞り込み条件 > 即決価格（上限）',
                  '絞り込み条件 > 商品状態',
                  '絞り込み条件 > 商品区分',
                  '絞り込み条件 > 自動延長',
                  '絞り込み条件 > 最低落札価格',
                  '絞り込み条件 > 絞り込む出品者',
                  '絞り込み条件 > 除外する出品者',
                );

      if ($authority_level > 1) {

        array_splice(
          $header,
          3,
          0,
          array(
            '無在庫出品 > スイッチ',
            '無在庫出品 > 除外する画像のURL',
            '無在庫出品 > マイパターン',
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

          unset($data['stockless']);
          unset($data['except_img_urls']);
          unset($data['my_pattern_id']);
        }

        $body = array(
                  $data['name'],
                  $data['action'],
                  $data['chatwork_to'],
                  $data['query_include_everything'],
                  $data['query_include_either'],
                  $data['query_not_include'],
                  $data['category_id'],
                  $data['search_target'],
                  $data['aucminbids'],
                  $data['aucmaxbids'],
                  $data['aucminprice'],
                  $data['aucmaxprice'],
                  $data['aucmin_bidorbuy_price'],
                  $data['aucmax_bidorbuy_price'],
                  $data['item_status'],
                  $data['listing_category'],
                  $data['is_automatic_extension'],
                  $data['reserved'],
                  $data['seller'],
                  $data['seller_except'],
               );

        if ($authority_level > 1) {

          array_splice(
            $body,
            3,
            0,
            array(
              $data['stockless'],
              $data['except_img_urls'],
              $data['my_pattern_id'],
            )
          );
        }

        $to_csv[] = array_map($null_to_s, $body);
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
