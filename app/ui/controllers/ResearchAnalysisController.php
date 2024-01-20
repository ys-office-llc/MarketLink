<?php
class ResearchAnalysisController extends BasicController
{
  const _INDEX = 'research_analysis';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  private function getExchangeUSDJPY()
  {

    $html = file_get_contents('http://info.finance.yahoo.co.jp/fx/');
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    return (int)$dom->getElementById('USDJPY_top_bid')->nodeValue;
  }

  private function httpBuildQuery($data)
  {

    $temporary = array();
    $exchange_rate = $this->getExchangeUSDJPY();

    $temporary[] = $data['ebay_us_query_include_everything'];

    if (strlen($data['ebay_us_query_include_either']) > 0) {

      $temporary[] = '('.
                     implode(' ,',
                       preg_split(
                         "/\s+/",
                         mb_convert_kana(
                           $data[
                             'ebay_us_query_include_either'
                           ], 'as', 'UTF-8'
                         )
                       )
                      )
                      .')';
    }

    if (strlen($data['ebay_us_query_not_include']) > 0) {

      $temporary[] = implode(' ',
                       array_map(
                         function($x) {

                           return '-'.$x;
                         },
                         preg_split(
                           "/\s+/",
                           mb_convert_kana(
                             $data[
                               'ebay_us_query_not_include'
                             ], 'as', 'UTF-8'
                           )
                         )
                       )
                     );
    }

    $param = array(
      'ebay' => array(
        '_nkw'   => implode(' ', $temporary),
        '_sacat' => $data['ebay_us_category_id'],
      ),
      'yahoo' => array(
        'auctions' => array(
          'va'  => $data['yahoo_auctions_query_include_everything'],
          'vo'  => $data['yahoo_auctions_query_include_either'],
          've'  => $data['yahoo_auctions_query_not_include'],
          'auccat' => $data['yahoo_auctions_category_id'],
        ),
      ),
    );

    if ((int)$data['yahoo_auctions_min_price'] > 0) {

      $param['yahoo']['auctions']['min'] = $data['yahoo_auctions_min_price'];
    }

    if ((int)$data['yahoo_auctions_max_price'] > 0) {

      $param['yahoo']['auctions']['max'] = $data['yahoo_auctions_max_price'];
    }

    if ((int)$data['ebay_us_min_price'] > 0) {

      $param['ebay']['_udlo'] = (int)$data['ebay_us_min_price'] *
                                $exchange_rate;
    }

    if ((int)$data['ebay_us_max_price'] > 0) {

      $param['ebay']['_udhi'] = (int)$data['ebay_us_max_price'] *
                                $exchange_rate;
    }

    return array(
      'ebay' => array(
        'active' => $this->_configure
                         ->current['configure']['mvc'][
                           'system'
                         ]['research']['ebay']['url']['active'].'?'.
                    http_build_query(
                      array_merge(
                        $param['ebay'],
                        $this->_configure->current['configure']['mvc'][
                          'system'
                        ]['research']['ebay']['query']['active']
                      )
                    ),
        'sold' => $this->_configure
                       ->current['configure']['mvc'][
                         'system'
                       ]['research']['ebay']['url']['sold'].'?'.
                  http_build_query(
                    array_merge(
                      $param['ebay'],
                      $this->_configure->current['configure']['mvc'][
                        'system'
                      ]['research']['ebay']['query']['sold']
                    )
                  )
      ),
      'yahoo' => array(
        'auctions' => array(
          'selling' => $this->_configure
                            ->current['configure']['mvc']['system'][
                              'research'
                            ]['yahoo']['auctions']['url']['selling'].'?'.
                       http_build_query(
                         array_merge(
                           $param['yahoo']['auctions'],
                           $this->_configure->current['configure']['mvc'][
                             'system'
                           ]['research']['yahoo']['auctions']['query']['selling']
                         )
                       ),
          'sold' => $this->_configure
                         ->current['configure']['mvc'][
                           'system'
                         ]['research']['yahoo']['auctions']['url']['sold'].'?'.
                    http_build_query(
                      array_merge(
                        $param['yahoo']['auctions'],
                        $this->_configure->current['configure']['mvc'][
                          'system'
                        ]['research']['yahoo']['auctions']['query']['sold']
                      )
                    )
         )
       ),
      'amazon' => array(
        'jp' => array(
          'offer' => $this->_configure
                            ->current['configure']['mvc']['controllers'][
                              'item'
                            ]['amazon']['jp']['url']['offer'].'?'.
                       http_build_query(
                         array_merge(
                           $this->_configure->current['configure']['mvc'][
                             'controllers'
                           ]['item']['amazon']['jp']['query']['offer']
                         )
                       ),
          'dp' => $this->_configure
                       ->current['configure']['mvc']['controllers'][
                         'item'
                       ]['amazon']['jp']['url']['dp'].$data['amazon_jp_asin']
                     )
                   ),
    );
  }

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
        'go_update' => null,
        '_token'    => $this->getToken(
                         $this->_view_path.'/'.self::_POST
                       ),
      )
    );
  }

  public function getAction($params)
  {

    $store = null;
    $data = null;
    $http_query = array();

    $user  = $this->_session->get('user');

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
        $data['yahoo_auctions_query_include_everything'] = $store['name'];
        $data['ebay_us_query_include_everything'] = $store['name'];
        $http_query = $this->httpBuildQuery($data);
      }
    } else if (isset($params['id'])) {

      $data = $this->_connect_model
                   ->get($this->_controller)
                   ->get(
                       $params['id'],
                       $this->_session
                            ->get('user')['id']
                     );

      $http_query = $this->httpBuildQuery($data);

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
        'go_update'    => null,
        'http_query'   => $http_query,
        'view_path'    => $this->_view_path,
        '_token'       => $this->getToken($this->_view_path . '/' . self::_POST)
      )
    );
  }

  private function verify($data)
  {
    $errors = array();

    if (!strlen($data['name'])) {
      $errors[] = '商品名を入力してください';
    }

    if (strlen($data['yahoo_auctions_min_price']) > 0 and
        !is_numeric($data['yahoo_auctions_min_price'])) {
      $errors[] = 'ヤフオク価格帯の下限値は数値のみ入力可能です';
    }

    if (strlen($data['yahoo_auctions_max_price']) > 0 and
        !is_numeric($data['yahoo_auctions_max_price'])) {
      $errors[] = 'ヤフオク価格帯の上限値は数値のみ入力可能です';
    }

    if (strlen($data['ebay_us_min_price']) > 0 and
        !is_numeric($data['ebay_us_min_price'])) {
      $errors[] = 'eBay価格帯の下限値は数値のみ入力可能です';
    }

    if (strlen($data['ebay_us_max_price']) > 0 and
        !is_numeric($data['ebay_us_max_price'])) {
      $errors[] = 'eBay価格帯の上限値は数値のみ入力可能です';
    }

    return $errors;
  }

  public function postAction()
  {

    $data        = array();
    $http_query  = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {

      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {
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

    if (self::TYPE_DELETE === $type) {

      $this->_connect_model
           ->get('UseResearchAnalysisArchive')
           ->deleteByParameters(
             array(
               'research_analysis_id' => $data['id'],
               'user_id' => $this->_user['id'],
             )
           );
    }

    $http_query = $this->httpBuildQuery($data);

    $this->set();
    $successes = array();
    $render = array(
      'errors'       => $this->verify($data),
      'successes'    => $successes,
      self::_INDEX   => $data,
      'table_values' => $this->_connect_model
                             ->get($this->_controller)
                             ->getTableValues(),
      'go_update'    => null,
      'http_query'   => $http_query,
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

    if ($export_csv) {

      $to_csv[] = array(
                    '商品名',
                    'アクション',
                    'ヤフオク検索キーワード > すべてを含む',
                    'ヤフオク検索キーワード > いずれかを含む',
                    'ヤフオク検索キーワード > 含めない',
                    'ヤフオクカテゴリー',
                    'ヤフオク価格帯範囲指定 > 下限値',
                    'ヤフオク価格帯範囲指定 > 上限値',
                    'eBay検索キーワード > すべてを含む',
                    'eBay検索キーワード > いずれかを含む',
                    'eBay検索キーワード > 含めない',
                    'eBayカテゴリー',
                    'eBay価格帯範囲指定 > 下限値',
                    'eBay価格帯範囲指定 > 上限値',
                    'Amazon Japan ASIN',
                  );

      foreach ($this->_request->getPosts()['id'] as $id => $value) {

        $data = $this->_connect_model
                     ->get($this->_controller)
                     ->get(
                         $id,
                         $this->_user['id']
                       );

        $to_csv[] = array(
                      $data['name'],
                      $data['action'],
                      $data['yahoo_auctions_query_include_everything'],
                      $data['yahoo_auctions_query_include_either'],
                      $data['yahoo_auctions_query_not_include'],
                      $data['yahoo_auctions_category_id'],
                      $data['yahoo_auctions_min_price'],
                      $data['yahoo_auctions_max_price'],
                      $data['ebay_us_query_include_everything'],
                      $data['ebay_us_query_include_either'],
                      $data['ebay_us_query_not_include'],
                      $data['ebay_us_category_id'],
                      $data['ebay_us_min_price'],
                      $data['ebay_us_max_price'],
                      $data['amazon_jp_asin'],
                    );
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

        $this->_connect_model
             ->get('UseResearchAnalysisArchive')
             ->deleteByParameters(
                 array(
                   'research_analysis_id' => $id,
                   'user_id' => $this->_user['id'],
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
