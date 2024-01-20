<?php
class SystemResearchAnalysisController extends BasicController
{
  const _INDEX = 'system_research_analysis';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  private function httpBuildQuery($data)
  {
    $param = array(
      'ebay' => array(
        '_nkw'  => $data['ebay_us_query'],
        '_udlo' => $data['ebay_us_min_price']*100,
        '_udhi' => $data['ebay_us_max_price']*100,
      ),
      'yahoo' => array(
        'auctions' => array(
          'p'   => $data['yahoo_auctions_query'],
          'min' => $data['yahoo_auctions_min_price'],
          'max' => $data['yahoo_auctions_max_price'],
        ),
      ),
    );

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
       )
    );
  }

  public function listAction()
  {

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
    $http_query = array();

    if (isset($params['id'])) {
      $data = $this->_connect_model
                   ->get($this->_controller)
                   ->get($params['id']);

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
      $errors[] = 'eBay US価格帯の下限値は数値のみ入力可能です';
    }

    if (strlen($data['ebay_us_max_price']) > 0 and
        !is_numeric($data['ebay_us_max_price'])) {
      $errors[] = 'eBay US価格帯の上限値は数値のみ入力可能です';
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
    unset($data['user_id']);

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
