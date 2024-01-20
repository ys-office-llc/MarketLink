<?php
class PotalController extends BasicController {

  public $resources = array(
    'counter' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'research_watch_list' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'research_analysis' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'use_research_analysis_archive' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'research_stores' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'research_free_markets_watch' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'research_free_markets_search' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'research_new_arrival' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'research_yahoo_auctions_search' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
    'reserve_place_bids' => array(
       'current' => 0,
       'limit'   => 0,
       'ratio'   => 0,
       'color' => ''
    ),
  );

  protected $_authentication = array('index');

  const _POST  = 'potal/post';
  const _MODEL = 'Account';

  const _THRESHOLD_WARNING     = 70;
  const _THRESHOLD_DANGER      = 90;
  const _THRESHOLD_UPPER_LIMIT = 100;

  public function indexAction()
  {
    $errors  = array();
    $reached = array();

    $account = $this->_session->get('user');

    $this->_session->set(
      'resources_threads', 
      $this->_configure
           ->current['configure']['system'][
               'resources'
             ]['threads'][$account['account_contract_id']]
    );

    $this->_session->set(
      'resources_retention_period', 
      $this->_configure
           ->current['configure']['system'][
               'resources'
             ]['retention_period'][$account['account_contract_id']]
    );

    // 商品状態と入札状態のカウント数を取得する
    $this->_session->set(
      'counter', array_merge(
                   $this->getCounterItem($account['id']),
                   $this->getCounterBids($account['id']),
                   array(
                     'research_watch_list' =>
                     $this->_connect_model
                          ->get('ResearchWatchList')
                          ->counter($account['id'])
                   ),
                   array(
                     'research_analysis' =>
                     $this->_connect_model
                          ->get('ResearchAnalysis')
                          ->counter($account['id'])
                   ),
                   array(
                     'use_research_analysis_archive' =>
                     $this->_connect_model
                          ->get('UseResearchAnalysisArchive')
                          ->counterCurrentDate($account['id'])
                   ),
                   array(
                     'research_stores' =>
                     $this->_connect_model
                          ->get('ResearchStores')
                          ->counter($account['id'])
                   ),
                   array(
                     'research_new_arrival' =>
                     $this->_connect_model
                          ->get('ResearchNewArrival')
                          ->counter($account['id'])
                   ),
                   array(
                     'research_yahoo_auctions_search' =>
                     $this->_connect_model
                          ->get('ResearchYahooAuctionsSearch')
                          ->counter($account['id'])
                   ),
                   array(
                     'research_free_markets_watch' =>
                     $this->_connect_model
                          ->get('ResearchFreeMarketsWatch')
                          ->counter($account['id'])
                   ),
                   array(
                     'research_free_markets_search' =>
                     $this->_connect_model
                          ->get('ResearchFreeMarketsSearch')
                          ->counter($account['id'])
                   )
                 )
    );

    // データ使用量と登録数を取得する
    $this->getResources($account['account_contract_id']);
    $this->_session->set('resources', $this->resources);

//$errors = array('ヤフオク販売用認証が有効期限切れです。うんぬん');

    return $this->render(array(
      'errors'       => $errors,
      'account'      => $account,
      'status'       => $this->_connect_model->get('MonitorProcess')
                             ->getsByDate($this->_datetime->format('Y-m-d')),
      'interface'    => $this->_connect_model->get('MonitorInterface')
                             ->gets(),
      'table_values' => $this->_connect_model
                             ->get(self::_MODEL)
                             ->getTableValues(),
      'resources'    => $this->resources,
      '_token'       => $this->getToken(self::_POST),
    ));
  }

  private function setLimit($limit, $factor, $threads, $index)
  {

    $this->resources['counter']['limit'] = $limit[$index];
    $this->resources['research_watch_list']['limit'] = $limit[$index] * $factor['market_screening'][$index];
    $this->resources['use_research_analysis_archive']['limit'] = $limit[$index] * $factor['market_screening'][$index];
    $this->resources['research_stores']['limit'] = $limit[$index] * $factor['market_screening'][$index];
    $this->resources['research_free_markets_watch']['limit'] = $limit[$index] * $factor['market_screening'][$index];
    $this->resources['research_free_markets_search']['limit'] = $limit[$index] * $factor['market_screening'][$index];
    $this->resources['research_analysis']['limit'] = $limit[$index] * $factor['market_screening'][$index];
    $this->resources['research_new_arrival']['limit'] = $limit[$index] * $factor['market_screening'][$index];
    $this->resources['research_yahoo_auctions_search']['limit'] = $limit[$index] * $factor['market_screening'][$index];
    $this->resources['reserve_place_bids']['limit'] = $threads[$index];
  }

  private function setCurrent($id)
  {

    $this->resources['counter']['current'] =
    $this->_connect_model
         ->get('Item')
         ->counter($id);
    $this->resources['research_watch_list']['current'] =
     $this->_connect_model
          ->get('ResearchWatchList')
          ->counter($id);
    $this->resources['use_research_analysis_archive']['current'] =
     $this->_connect_model
          ->get('UseResearchAnalysisArchive')
          ->counterCurrentDate($id);
    $this->resources['research_stores']['current'] =
     $this->_connect_model
          ->get('ResearchStores')
          ->counter($id);
    $this->resources['research_free_markets_watch']['current'] =
     $this->_connect_model
          ->get('ResearchFreeMarketsWatch')
          ->counter($id);
    $this->resources['research_free_markets_search']['current'] =
     $this->_connect_model
          ->get('ResearchFreeMarketsSearch')
          ->counter($id);
    $this->resources['research_analysis']['current'] =
     $this->_connect_model
          ->get('ResearchAnalysis')
          ->counter($id);
    $this->resources['research_new_arrival']['current'] =
     $this->_connect_model
          ->get('ResearchNewArrival')
          ->counter($id);
    $this->resources['research_yahoo_auctions_search']['current'] =
     $this->_connect_model
          ->get('ResearchYahooAuctionsSearch')
          ->counter($id);
    $this->resources['reserve_place_bids']['current'] =
      $this->getCounterBids($id)['bids']['reserve_place_bids'];
  }

  private function setThreshold()
  {

    foreach ($this->resources as $key => $val) {

      if ($this->resources[$key]['current'] > 0) {
        $this->resources[$key]['ratio'] =
          sprintf("%d",
            $this->resources[$key]['current'] /
            $this->resources[$key]['limit'] * 100
          );
      }

      if ($this->resources[$key]['ratio'] > 100) {
        $this->resources[$key]['ratio'] = 100;
      }

      $reached[$key] = false;
      if ($this->resources[$key]['ratio'] >= self::_THRESHOLD_UPPER_LIMIT) {

        $this->resources[$key]['color'] = 'progress-bar-danger';
        $reached[$key] = true;
      } else if ($this->resources[$key]['ratio'] >= self::_THRESHOLD_DANGER) {

        $this->resources[$key]['color'] = 'progress-bar-danger';
      } else if ($this->resources[$key]['ratio'] >= self::_THRESHOLD_WARNING) {

        $this->resources[$key]['color'] = 'progress-bar-warning';
      } else {

        $this->resources[$key]['color'] = 'progress-bar-primary';
      }
    }

    if (count($reached) > 0) {

      $this->_session->set('reached', $reached);
    }
  }

  private function getResources($contract_id)
  {
    $account  = $this->_session->get('user');
    $limit    = $this->_configure->current['configure'][
                  'mvc'
                ]['potal']['resources']['limit'];
    $factor   = $this->_configure->current['configure'][
                  'mvc'
                ]['potal']['resources']['factor'];
    $contract = $this->_configure->current['configure'][
                  'mvc'
                ]['potal']['contract'];
    $threads  = $this->_configure->current['configure'][
                  'system'
                ]['resources']['threads'];

    switch ($contract_id) {
      case $contract['monitor']:

        $this->setLimit($limit, $factor, $threads, $contract['monitor']);
        break;
      case $contract['light']:

        $this->setLimit($limit, $factor, $threads, $contract['light']);
        break;
      case $contract['standard']:

        $this->setLimit($limit, $factor, $threads, $contract['standard']);
        break;
      case $contract['plemium']:

        $this->setLimit($limit, $factor, $threads, $contract['plemium']);
        break;
    }

    $this->setCurrent($account['id']);
    $this->setThreshold();
  }

}
