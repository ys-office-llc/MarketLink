<?php
class BasicController extends Controller
{
  const TYPE_CREATE      = 'create';
  const TYPE_UPDATE      = 'update';
  const TYPE_DELETE      = 'delete';
  const TYPE_DUPLICATE   = 'duplicate';
  const TYPE_RESERVATION = 'reservation';

  const TYPE_IMPORT_RESEARCH_YAHOO_AUCTIONS_SEARCH =
        'import_research_yahoo_auctions_search';

  const TYPE_IMPORT_RESEARCH_ANALYSIS =
        'import_research_analysis';

  const TYPE_IMPORT_RESEARCH_NEW_ARRIVAL =
        'import_research_new_arrival';

  const TYPE_YAHOO_AUCTIONS_WAITING_ITEM =
        'yahoo_auctions_waiting_item';
  const TYPE_YAHOO_AUCTIONS_RESERVE_ADD_ITEM =
        'yahoo_auctions_reserve_add_item';
  const TYPE_YAHOO_AUCTIONS_RESERVE_END_ITEM =
        'yahoo_auctions_reserve_end_item';
  const TYPE_YAHOO_AUCTIONS_RESERVE_RESUBMIT_ITEM =
        'yahoo_auctions_reserve_resubmit_item';
  const TYPE_YAHOO_AUCTIONS_RESERVE_REVISE_ITEM =
        'yahoo_auctions_reserve_revise_item';
  const TYPE_YAHOO_AUCTIONS_SELLER_REQUEST_CAPTCHA =
        'yahoo_auctions_seller_request_captcha';
  const TYPE_YAHOO_AUCTIONS_BUYER_REQUEST_CAPTCHA =
        'yahoo_auctions_buyer_request_captcha';

  const TYPE_EBAY_US_WAITING_ITEM =
        'ebay_us_waiting_item';
  const TYPE_EBAY_US_RESERVE_ADD_ITEM =
        'ebay_us_reserve_add_item';
  const TYPE_EBAY_US_RESERVE_END_ITEM =
        'ebay_us_reserve_end_item';
  const TYPE_EBAY_US_RESERVE_REVISE_ITEM =
        'ebay_us_reserve_revise_item';
  const TYPE_EBAY_US_RESERVE_RESUBMIT_ITEM =
        'ebay_us_reserve_resubmit_item';
  const TYPE_EBAY_US_SHIPMENT_ITEM =
        'ebay_us_shipment_item';
  const TYPE_REQUEST_EBAY_US_AUTH_TOKEN =
        'request_ebay_us_auth_token';

  const TYPE_AMAZON_JP_WAITING_ITEM =
        'amazon_jp_waiting_item';
  const TYPE_AMAZON_JP_RESERVE_ADD_ITEM =
        'amazon_jp_reserve_add_item';
  const TYPE_AMAZON_JP_RESERVE_END_ITEM =
        'amazon_jp_reserve_end_item';
  const TYPE_AMAZON_JP_RESERVE_RESUBMIT_ITEM =
        'amazon_jp_reserve_resubmit_item';
  const TYPE_AMAZON_JP_RESERVE_REVISE_ITEM =
        'amazon_jp_reserve_revise_item';
  const TYPE_AMAZON_JP_EXCLUDE_ITEM =
        'amazon_jp_exclude_item';
  const TYPE_AMAZON_JP_RETURN_ITEM =
        'amazon_jp_return_item';

  const TYPE_ALL_RESERVE_END_ITEM =
        'all_reserve_end_item';
  const TYPE_ALL_RESERVE_ADD_ITEM =
        'all_reserve_add_item';

  const TYPE_CHATWORK_CREATE_ROOMS =
        'chatwork_create_rooms';
  const TYPE_CHATWORK_DELETE_ROOMS =
        'chatwork_delete_rooms';

  /*
   * 子のクラスから呼び出されるため、$this->_controller変数には、
   * 例えば「Item」という文字列が入っている。(2016-10-20)
   */
  protected function set()
  {
    $this->_connect_model
         ->get($this->_controller)
         ->setUserId(
             $this->_session
                  ->get('user')['id']
           );
    $this->_session->set(
      'counter', array_merge(
                   $this->getCounterItem(
                     $this->_session
                          ->get('user')['id']
                   ),
                   $this->getCounterBids(
                     $this->_session
                          ->get('user')['id']
                   ),
                   array(
                     'research_watch_list' =>
                     $this->_connect_model
                          ->get('ResearchWatchList')
                          ->counter($this->_session->get('user')['id'])
                   ),
                   array(
                     'research_analysis' =>
                     $this->_connect_model
                          ->get('ResearchAnalysis')
                          ->counter($this->_session->get('user')['id'])
                   ),
                   array(
                     'use_research_analysis_archive' =>
                     $this->_connect_model
                          ->get('UseResearchAnalysisArchive')
                          ->counterCurrentDate(
                              $this->_session->get('user')['id']
                            )
                   ),
                   array(
                     'research_stores' =>
                     $this->_connect_model
                          ->get('ResearchStores')
                          ->counter($this->_session->get('user')['id'])
                   ),
                   array(
                     'research_new_arrival' =>
                     $this->_connect_model
                          ->get('ResearchNewArrival')
                          ->counter($this->_session->get('user')['id'])
                   ),
                   array(
                     'research_free_markets_watch' =>
                     $this->_connect_model
                          ->get('ResearchFreeMarketsWatch')
                          ->counter($this->_session->get('user')['id'])
                   ),
                   array(
                     'research_free_markets_search' =>
                     $this->_connect_model
                          ->get('ResearchFreeMarketsSearch')
                          ->counter($this->_session->get('user')['id'])
                   ),
                   array(
                     'research_yahoo_auctions_search' =>
                     $this->_connect_model
                          ->get('ResearchYahooAuctionsSearch')
                          ->counter($this->_session->get('user')['id'])
                   )
                 )
    );
  }

  protected function serializer($model, $data)
  {

    if ($model === 'Item') {

      $data['do_repeat'] = serialize($data['do_repeat']);
      if (!$data['do_repeat']) {
        $data['do_repeat'] = null;
      }

      $data['do_snipe'] = serialize($data['do_snipe']);
      if (!$data['do_snipe']) {
        $data['do_snipe'] = null;
      }

    }

    if ($model === 'SettingItemConditionYahooAuctions') {

      $data['yahuneko'] = serialize($data['yahuneko']);
      if (!$data['yahuneko']) {
        $data['yahuneko'] = null;
      }

      $data['hacoboon'] = serialize($data['hacoboon']);
      if (!$data['hacoboon']) {
        $data['hacoboon'] = null;
      }

      $data['hacoboonmini'] = serialize($data['hacoboonmini']);
      if (!$data['hacoboonmini']) {
        $data['hacoboonmini'] = null;
      }

    }

    if ($model === 'ResearchNewArrival') {

      $data['rank_kitamura'] = serialize($data['rank_kitamura']);
      if (!$data['rank_kitamura']) {
        $data['rank_kitamura'] = null;
      }
  
      $data['rank_fujiya_camera'] = serialize($data['rank_fujiya_camera']);
      if (!$data['rank_fujiya_camera']) {
        $data['rank_fujiya_camera'] = null;
      }
  
      $data['rank_camera_no_naniwa'] = serialize($data['rank_camera_no_naniwa']);
      if (!$data['rank_camera_no_naniwa']) {
        $data['rank_camera_no_naniwa'] = null;
      }
  
      $data['rank_map_camera'] = serialize($data['rank_map_camera']);
      if (!$data['rank_map_camera']) {
        $data['rank_map_camera'] = null;
      }

      $data['rank_champ_camera'] = serialize($data['rank_champ_camera']);
      if (!$data['rank_champ_camera']) {
        $data['rank_champ_camera'] = null;
      }

      $data['rank_hardoff'] = serialize($data['rank_hardoff']);
      if (!$data['rank_hardoff']) {
        $data['rank_hardoff'] = null;
      }
    }

    if ($model === 'ResearchFreeMarketsSearch') {

      $data['rank_mercari'] = serialize($data['rank_mercari']);
      if (!$data['rank_mercari']) {

        $data['rank_mercari'] = null;
      }

      $data['rank_rakuma'] = serialize($data['rank_rakuma']);
      if (!$data['rank_rakuma']) {

        $data['rank_rakuma'] = null;
      }

      $data['rank_fril'] = serialize($data['rank_fril']);
      if (!$data['rank_fril']) {

        $data['rank_fril'] = null;
      }
    }

    return $data;
  }

  protected function unserializer($model, $data)
  {

    if ($model === 'Item') {

      $data['do_repeat'] = unserialize($data['do_repeat']);
      if (!$data['do_repeat']) {
        $data['do_repeat'] = null;
      }

      $data['do_snipe'] = unserialize($data['do_snipe']);
      if (!$data['do_snipe']) {
        $data['do_snipe'] = null;
      }

    }

    if ($model === 'SettingItemConditionYahooAuctions') {

      $data['yahuneko'] = unserialize($data['yahuneko']);
      if (!$data['yahuneko']) {
        $data['yahuneko'] = null;
      }

      $data['hacoboon'] = unserialize($data['hacoboon']);
      if (!$data['hacoboon']) {
        $data['hacoboon'] = null;
      }

      $data['hacoboonmini'] = unserialize($data['hacoboonmini']);
      if (!$data['hacoboonmini']) {
        $data['hacoboonmini'] = null;
      }

    }

    if ($model === 'ResearchNewArrival') {

      $data['rank_kitamura'] = unserialize($data['rank_kitamura']);
      if (!$data['rank_kitamura']) {
        $data['rank_kitamura'] = null;
      }
  
      $data['rank_fujiya_camera'] = unserialize($data['rank_fujiya_camera']);
      if (!$data['rank_fujiya_camera']) {
        $data['rank_fujiya_camera'] = null;
      }
  
      $data['rank_camera_no_naniwa'] = unserialize($data['rank_camera_no_naniwa']);
      if (!$data['rank_camera_no_naniwa']) {
        $data['rank_camera_no_naniwa'] = null;
      }
  
      $data['rank_map_camera'] = unserialize($data['rank_map_camera']);
      if (!$data['rank_map_camera']) {
        $data['rank_map_camera'] = null;
      }

      $data['rank_champ_camera'] = unserialize($data['rank_champ_camera']);
      if (!$data['rank_champ_camera']) {
        $data['rank_champ_camera'] = null;
      }

      $data['rank_hardoff'] = unserialize($data['rank_hardoff']);
      if (!$data['rank_hardoff']) {
        $data['rank_hardoff'] = null;
      }
    }

    if ($model === 'ResearchFreeMarketsSearch') {

      $data['rank_mercari'] = unserialize($data['rank_mercari']);
      if (!$data['rank_mercari']) {

        $data['rank_mercari'] = null;
      }

      $data['rank_rakuma'] = unserialize($data['rank_rakuma']);
      if (!$data['rank_rakuma']) {

        $data['rank_rakuma'] = null;
      }

      $data['rank_fril'] = unserialize($data['rank_fril']);
      if (!$data['rank_fril']) {

        $data['rank_fril'] = null;
      }
    }

    return $data;
  }

  protected function getCounterBids($user_id)
  {
    $counter = array(
      'bids' => array(
         'reserve_place_bids'  => 0,
         'bidding'             => 0,
         'win'                 => 0,
         'end'                 => 0,
      ),
    );

    $state = $this->getState('bids');
    $items = $this->_connect_model
                  ->get('Bids')
                  ->gets($user_id);

    foreach ($items as $key => $item) {

      foreach ($item as $key_child => $value) {

        if (preg_match("/state_id$/", $key_child)) {
          $state_id = (int)$value;

          switch ($state_id) {
            case $state['reserve_place_bids']:

              $counter['bids']['reserve_place_bids'] =
              $counter['bids']['reserve_place_bids'] + 1;
            break;
            case $state['bidding']:

              $counter['bids']['bidding'] =
              $counter['bids']['bidding'] + 1;
            break;
            case $state['win']:

              $counter['bids']['win'] =
              $counter['bids']['win'] + 1;
            break;
            case $state['end']:

              $counter['bids']['end'] =
              $counter['bids']['end'] + 1;
            break;
          }
        }
      }
    }

    return $counter;
  }

  protected function getCounterItem($user_id)
  {
    $counter = array(
      'item' => array(
         'waiting'  => 0,
         'exhibit'  => 0,
         'selling'  => 0,
         'payment'  => 0,
         'shipment' => 0
      ),
    );

    $state = $this->getState('item');
    $items = $this->_connect_model
                  ->get('Item')
                  ->gets($user_id);
    
    foreach ($items as $key => $item) {

      $temporarily = array(
         'waiting'  => 0,
         'exhibit'  => 0,
         'selling'  => 0,
         'payment'  => 0,
         'shipment' => 0
      );
      foreach ($item as $key_child => $value) {
    
        if (preg_match("/_state_id$/", $key_child)) {
          $state_id = (int)$value;
    
          switch ($state_id) {
            case $state['waiting']:
              $temporarily['waiting'] = $temporarily['waiting'] + 1;
            break;
            case $state['exhibit']:
              $temporarily['exhibit'] = $temporarily['exhibit'] + 1;
            break;
            case $state['selling']:
              $temporarily['selling'] = $temporarily['selling'] + 1;
            break;
            case $state['payment']:
              $temporarily['payment'] = $temporarily['payment'] + 1;
            break;
            case $state['shipment']:
              $temporarily['shipment'] = $temporarily['shipment'] + 1;
            break;
          }
        }

      }

      if ($temporarily['waiting'] > 0) {
        $counter['item']['waiting'] = $counter['item']['waiting'] + 1;
      }

      if ($temporarily['exhibit'] > 0) {
        $counter['item']['exhibit'] = $counter['item']['exhibit'] + 1;
      }

      if ($temporarily['selling'] > 0) {
        $counter['item']['selling'] = $counter['item']['selling'] + 1;
      }

      if ($temporarily['payment'] > 0) {
        $counter['item']['payment'] = $counter['item']['payment'] + 1;
      }

      if ($temporarily['shipment'] > 0) {
        $counter['item']['shipment'] = $counter['item']['shipment'] + 1;
      }
    }

    return $counter;
  }

  protected function fillNull($target)
  {
    foreach ($target as $key => $value) {
      $target[$key] = null;
    }

    return $target;
  }

  protected function getState($type)
  {
    return $this->_configure
                ->current['configure']['builder']['mysql'][$type]['state'];
  }

  protected function fillValue($target)
  {
    foreach ($target as $key => $value) {

      if (is_null($this->_request->getPost($key))) {

        $target[$key] = $value;
      } else {

        $target[$key] = $this->_request->getPost($key);
      }
    }
    $target['user_id'] = $this->_user['id'];

    if (self::TYPE_CREATE === $this->_request->getSubmitType() or
        self::TYPE_RESERVATION === $this->_request->getSubmitType()) {

      $target['created_at']  = $this->_datetime->format('Y-m-d H:i:s');
    }

    $target['modified_at'] = $this->_datetime->format('Y-m-d H:i:s');

    if (self::TYPE_DELETE ===  $this->_request->getSubmitType()) {

      $target['deleted_at']  = $this->_datetime->format('Y-m-d H:i:s');
    }

    return $target;
  }

  private function setWaiting($data)
  {
    $state = $this->getState('item');
    $data['yahoo_auctions_state_id'] = $state['waiting'];
    $data['ebay_us_state_id']        = $state['waiting'];
    $data['amazon_jp_state_id']      = $state['waiting'];

    return $data;
  }

  private function setShipment($data)
  {
    $state   = $this->getState('item');
    $waiting = array();

    foreach ($data as $key => $value) {

      if (preg_match("/_state_id$/", $key)) {

        $data[$key] = $state['shipment'];
      }
    }

    return $data;
  }

  private function resetItem($data)
  {

    foreach ($data as $key => $value) {

      if (preg_match("/_item_id$/", $key) or
          preg_match("/_url$/", $key)) {

        $data[$key] = null;
      }

    }

    return $data;
  }

  private function setDoRepeat()
  {

    return serialize(
             array(
               'yahoo_auctions',
               'ebay_us',
             )
           );
  }

  protected function commit($type, $render, $index, $model, $page)
  {
    $state = $this->getState('item');
    if (count($render['errors']) === 0) {
      switch ($type) {
        case self::TYPE_CREATE:
          if ($model === 'Item') {
            $render[$index] = $this->setWaiting($render[$index]);
            $render[$index]['do_repeat'] = $this->setDoRepeat();
          }

          if ($model === 'Bids') {
            $render[$index]['state_id'] = 1;
          }

          $id = $this->_connect_model
                     ->get($model)
                     ->create($render[$index]);
          if (!empty($this->_request->getFiles())) {
            $render[$index]['id'] = $id;
            $render[$index] = $this->uploadFiles($render[$index]);
            $this->_connect_model->get($model)->update($render[$index]);
          }
          $render['go_update'] = sprintf(
            "%s/%s/%s",
            $render['view_path'],
            $page,
            $id
          );
          $render[$index] = $this->fillNull($render[$index]);
          $render['successes'][] = '作成しました';
          return $this->render($render, $page);
          break;
        case self::TYPE_UPDATE:
          if (!empty($this->_request->getFiles())) {
            $render[$index] = $this->uploadFiles($render[$index]);
          }
          $this->_connect_model->get($model)->update($render[$index]);
          $render['successes'][] = '変更しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          $render['in_progress'] = $this->inProgress($render[$index]);
          return $this->render($render, $page);
          break;

        case self::TYPE_DUPLICATE:
          if ($model === 'Item') {
            $render[$index] = $this->setWaiting($render[$index]);
            $render[$index] = $this->resetItem($render[$index]);
            $render['waiting']  = true;
            $render['shipment'] = false;
          }
          $id = $this->_connect_model
                     ->get($model)
                     ->create($render[$index]);
          if ($model === 'Account') {

            $this->duplicate($render[$index]['id'], $id);
          }
          $render[$index]['id'] = $id;
          $render['successes'][] = '複製しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_DELETE:
          if ($model === 'Item') {

            /*
             * prepareをクリアしておかないと、削除後に「全販路一括新規出品」
             * ボタンが表示されてしまう。
             */
            $render['prepare'] = array();
          }
          if ($model === 'Account') {

            $this->delete($render[$index]['id']);
          }
          $this->_connect_model->get($model)->delete($render[$index]);
          $render[$index] = $this->fillNull($render[$index]);
          if ($model === 'Bids') {

            $render['to_close'] = true;
          }
          $render[$index] = $this->fillNull($render[$index]);
          $render['successes'][] = '削除しました';
          return $this->render($render, $page);
          break;
        case self::TYPE_RESERVATION:

          if ($model === 'Bids') {
            $render[$index]['state_id'] = 1;
          }

          $id = $this->_connect_model
                     ->get($model)
                     ->create($render[$index]);
          $render['to_close'] = true;
          //$render[$index] = $this->fillNull($render[$index]);
          $render['successes'][] = '予約しました。';
          return $this->render($render, $page);
          break;
        case self::TYPE_YAHOO_AUCTIONS_WAITING_ITEM:
          $render[$index][
            'yahoo_auctions_state_id'
          ] = $state['waiting'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'yahoo_auctions_state_id' => $state['waiting']
               ));
          $render['successes'][] = 'ヤフオク入庫にしました。';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
        case self::TYPE_YAHOO_AUCTIONS_RESERVE_ADD_ITEM:
          $render[$index][
            'yahoo_auctions_state_id'
          ] = $state['reserve_add_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'yahoo_auctions_state_id' => $state['reserve_add_item']
               ));
          $render['successes'][] = 'ヤフオク出品開始予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_YAHOO_AUCTIONS_RESERVE_RESUBMIT_ITEM:
          $render[$index][
            'yahoo_auctions_state_id'
          ] = $state['reserve_relist_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'yahoo_auctions_state_id' => $state['reserve_relist_item']
               ));
          $render['successes'][] = 'ヤフオク再出品予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_YAHOO_AUCTIONS_RESERVE_REVISE_ITEM:
          $render[$index][
            'yahoo_auctions_state_id'
          ] = $state['reserve_revise_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'yahoo_auctions_state_id' => $state['reserve_revise_item']
               ));
          $render['successes'][] = 'ヤフオク修正予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        case self::TYPE_YAHOO_AUCTIONS_RESERVE_END_ITEM:
          $render[$index][
            'yahoo_auctions_state_id'
          ] = $state['reserve_end_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'yahoo_auctions_state_id' => $state['reserve_end_item']
               ));
          $render['successes'][] = 'ヤフオク出品終了予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_YAHOO_AUCTIONS_SELLER_REQUEST_CAPTCHA:
          $render[$index][
            'yahoo_auctions_seller_request_captcha'
          ] = 1;
          $render[$index][
            'yahoo_auctions_seller_cookies_is_set'
          ] = 0;
          $render[$index][
            'yahooapis_seller_appid'
          ] = null;
          $render[$index][
            'yahooapis_seller_secret'
          ] = null;
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'yahoo_auctions_seller_request_captcha' => 1,
                 'yahoo_auctions_seller_cookies_is_set' => 0,
                 'yahooapis_seller_appid' => null,
                 'yahooapis_seller_secret' => null,
               ));
          $render['in_progress'] = $this->inProgress($render[$index]);
          $render['successes'][] = 'ヤフオク販売用アカウント認証要求を受け付けました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        case self::TYPE_YAHOO_AUCTIONS_BUYER_REQUEST_CAPTCHA:
          $render[$index][
            'yahoo_auctions_buyer_request_captcha'
          ] = 1;
          $render[$index][
            'yahoo_auctions_buyer_cookies_is_set'
          ] = 0;
          $render[$index][
            'yahooapis_buyer_appid'
          ] = null;
          $render[$index][
            'yahooapis_buyer_secret'
          ] = null;
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'yahoo_auctions_buyer_request_captcha' => 1,
                 'yahoo_auctions_buyer_cookies_is_set' => 0,
                 'yahooapis_buyer_appid' => null,
                 'yahooapis_buyer_secret' => null,
               ));
          $render['in_progress'] = $this->inProgress($render[$index]);
          $render['successes'][] = 'ヤフオク仕入用アカウント認証要求を受け付けました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        case self::TYPE_EBAY_US_WAITING_ITEM:
          $render[$index][
            'ebay_us_state_id'
          ] = $state['waiting'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'ebay_us_state_id' => $state['waiting']
               ));
          $render['successes'][] = 'eBay入庫にしました。';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
        case self::TYPE_EBAY_US_RESERVE_ADD_ITEM:
          $render[$index][
            'ebay_us_state_id'
          ] = $state['reserve_add_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'ebay_us_state_id' => $state['reserve_add_item']
               ));
          $render['successes'][] = 'eBay出品開始予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_EBAY_US_RESERVE_REVISE_ITEM:
          $render[$index][
            'ebay_us_state_id'
          ] = $state['reserve_revise_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'ebay_us_state_id' => $state['reserve_revise_item']
               ));
          $render['successes'][] = 'eBay情報修正予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_EBAY_US_RESERVE_RESUBMIT_ITEM:
          $render[$index][
            'ebay_us_state_id'
          ] = $state['reserve_relist_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'ebay_us_state_id' => $state['reserve_relist_item']
               ));
          $render['successes'][] = 'eBay再出品予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_EBAY_US_RESERVE_END_ITEM:
          $render[$index][
            'ebay_us_state_id'
          ] = $state['reserve_end_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'ebay_us_state_id' => $state['reserve_end_item']
               ));
          $render['successes'][] = 'eBay出品終了予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_EBAY_US_SHIPMENT_ITEM:
          $render[$index] = $this->setShipment($render[$index]);
          $this->_connect_model->get($model)->update($render[$index]);
          $render['successes'][] = 'eBay出庫しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_REQUEST_EBAY_US_AUTH_TOKEN:
          $render[$index]['request_ebay_us_auth_token'] = 1;
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'request_ebay_us_auth_token' => 1
               ));
          $render['in_progress'] = $this->inProgress($render[$index]);
          $render['successes'][] = 'eBay認証トークン取得を要求しました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        case self::TYPE_AMAZON_JP_WAITING_ITEM:
          $render[$index][
            'amazon_jp_state_id'
          ] = $state['waiting'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'amazon_jp_state_id' => $state['waiting']
               ));
          $render['successes'][] = 'Amazon.co.jp入庫にしました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
        case self::TYPE_AMAZON_JP_RESERVE_ADD_ITEM:
          $render[$index][
            'amazon_jp_state_id'
          ] = $state['reserve_add_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'amazon_jp_state_id' => $state['reserve_add_item']
               ));
          $render['successes'][] = 'Amazon.co.jp出品開始予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_AMAZON_JP_RESERVE_RESUBMIT_ITEM:
          $render[$index][
            'amazon_jp_state_id'
          ] = $state['reserve_relist_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'amazon_jp_state_id' => $state['reserve_relist_item']
               ));
          $render['successes'][] = 'Amazon.co.jp再出品予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_AMAZON_JP_RESERVE_REVISE_ITEM:
          $render[$index][
            'amazon_jp_state_id'
          ] = $state['reserve_revise_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'amazon_jp_state_id' => $state['reserve_revise_item']
               ));
          $render['successes'][] = 'Amazon.co.jp修正予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        case self::TYPE_AMAZON_JP_RESERVE_END_ITEM:
          $render[$index][
            'amazon_jp_state_id'
          ] = $state['reserve_end_item'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'amazon_jp_state_id' => $state['reserve_end_item']
               ));
          $render['successes'][] = 'Amazon.co.jp出品終了予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_AMAZON_JP_EXCLUDE_ITEM:
          $render[$index][
            'amazon_jp_state_id'
          ] = $state['exclude'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'amazon_jp_state_id' => $state['exclude']
               ));
          $render['successes'][] = 'Amazon.co.jp除外しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_AMAZON_JP_RETURN_ITEM:
          $render[$index][
            'amazon_jp_state_id'
          ] = $state['waiting'];
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'amazon_jp_state_id' => $state['waiting']
               ));
          $render['successes'][] = 'Amazon.co.jp復帰しました';
          $render[$index] = $this->unserializer($model, $render[$index]); 
          return $this->render($render, $page);
          break;
        case self::TYPE_CHATWORK_CREATE_ROOMS:
          $render[$index][
            'chatwork_create_rooms'
          ] = 1;
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'chatwork_create_rooms' => 1,
               ));
          $render['successes'][] = 'ChatWorkルーム作成指示をしました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        case self::TYPE_CHATWORK_DELETE_ROOMS:
          $render[$index][
            'chatwork_delete_rooms'
          ] = 1;
          $this->_connect_model
               ->get($model)
               ->update(array(
                 'id' => $render[$index]['id'],
                 'chatwork_delete_rooms' => 1,
               ));
          $render['successes'][] = 'ChatWorkルーム削除指示をしました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        case self::TYPE_ALL_RESERVE_ADD_ITEM:

          foreach ($render['prepare'] as $key => $value) {

            if ($value) {

              $key = sprintf("%s_state_id", $key);
              switch ((int)$render[$index][$key]) {
                case $state['waiting']:

                  $render[$index][$key] = $state['reserve_add_item'];
                  $this->_connect_model
                       ->get($model)
                       ->update(
                           array(
                             'id' => $render[$index]['id'],
                             $key => $state['reserve_add_item'],
                           )
                         );
                  break;
                default:
              }
            }
          }
          $render['successes'][] = '全販路一括新規出品予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        case self::TYPE_ALL_RESERVE_END_ITEM:
          foreach ($render[$index] as $key => $value) {
            if (preg_match("/_state_id$/", $key)) {
              switch ((int)$value) {
                case $state['exhibit']:
                  $render[$index][$key] = $state['reserve_end_item'];
                  $this->_connect_model
                       ->get($model)
                       ->update(
                           array(
                             'id' => $render[$index]['id'],
                             $key => $state['reserve_end_item'],
                           )
                         );
                  break;
                default:
              }
            }
          }
          $render['successes'][] = '全販路一括出品終了予約しました';
          $render[$index] = $this->unserializer($model, $render[$index]);
          return $this->render($render, $page);
          break;
        default:
          $this->httpNotFound();
      }
    } else {
      // 登録時にnullにしないと作成ボタンが消えて、変更・削除ボタンが現れる
      // 変更と削除時は消してはだめですよ。
      if (self::TYPE_CREATE === $type) {
        $render[$index]['id'] = null;
        if ($index === 'item') {
          $render[$index]['yahoo_auctions_state_id'] = 0;
          $render[$index]['ebay_us_state_id']        = 0;
          $render[$index]['amazon_jp_state_id']      = 0;
        }
      }

      // 入力した値が不正な場合の再描画
      $render[$index] = $this->unserializer($model, $render[$index]);

      return $this->render($render, $page);
    }
  }

  // int
  protected function getDiskUsage($dir)
  {
    $usage = null;

    $files = scandir($dir);
    $files = array_filter($files, function ($file) {
      return !in_array($file, array('.', '..'));
    });

    foreach ($files as $file) {
      $fullpath = rtrim($dir, '/') . '/' . $file;
      if (is_file($fullpath)) {
        $usage += filesize($fullpath);
      }
    }

    return $usage;
  }

  private function uploadFiles($data)
  {
    $suffix = null;
    $error  = null;
    $files  = $this->_request->getFiles()['files'];

    if ($files['size'] > 0) {
      foreach ($files['error'] as $index => $error) {
        if ($error === UPLOAD_ERR_OK) {
          $tmp_name = $files['tmp_name'][$index];
          list($width, $height, $type) = getimagesize($tmp_name);

          switch ($type) {
            case IMAGETYPE_JPEG:
              $suffix = 'jpg';
              break;
            case IMAGETYPE_GIF:
              $suffix = 'gif';
              break;
            case IMAGETYPE_PNG:
              $suffix = 'png';
              break;
            default:
              throw new InternalServerErrorException(
                'The extension is unknown'
              );
          }

          $file      = sprintf("%02d.%s", $index + 1, $suffix);
          $thumbnail = sprintf("thumbnail_%02d.%s", $index + 1, $suffix);
          $directory = sprintf("%s/%s/%s",
            $this->_application->getImagesDirectory(),
            $data['user_id'],
            $data['id']
          );

          if (!file_exists($directory)) {
            if (!@mkdir($directory, 0777, true)) {
              throw new InternalServerErrorException(
                error_get_last()['message']
              );
            }
          }

          $path           = sprintf("%s/%s", $directory, $file);
          $path_thumbnail = sprintf("%s/%s", $directory, $thumbnail);
          $data[sprintf("image_%02d", $index + 1)] = sprintf(
            "%s/%s/%s",
            $data['user_id'],
            $data['id'],
            $file
          );
          $data[sprintf("thumbnail_image_%02d", $index + 1)] = sprintf(
            "%s/%s/%s",
            $data['user_id'],
            $data['id'],
            $thumbnail
          );

          move_uploaded_file($tmp_name, $path);
          copy($path, $path_thumbnail);
          $this->createThumbnail($path_thumbnail, $directory);
        }
      }
      return $data;
    }
  }

  private function createThumbnail($image, $directory)
  {
    $this->resizeImage($image, 100, $directory);
  }

  private function resizeImage($image, $new_width, $directory = '.')
  {
    $date = date('YmdHis');

    list($width, $height, $type) = getimagesize($image);

    $new_height = round($height * $new_width / $width);
    $emp_img    = imagecreatetruecolor($new_width, $new_height);

    switch($type){
      case IMAGETYPE_JPEG:
        $new_image = imagecreatefromjpeg($image);
        break;
      case IMAGETYPE_GIF:
        $new_image = imagecreatefromgif($image);
        break;
      case IMAGETYPE_PNG:
        imagealphablending($emp_img, false);
        imagesavealpha($emp_img, true);
        $new_image = imagecreatefrompng($image);
        break;
    }
    imagecopyresampled(
      $emp_img,
      $new_image,
      0,0,0,0,
      $new_width,
      $new_height,
      $width,
      $height
    );

    switch($type) {
      case IMAGETYPE_JPEG:
        imagejpeg($emp_img, $directory . '/' . $date . '.jpg');
        rename($directory."/".$date.".jpg", $image);
        break;
      case IMAGETYPE_GIF:
        $bgcolor = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
        imagefill($emp_img, 0, 0, $bgcolor);
        imagecolortransparent($emp_img, $bgcolor);
        imagegif($emp_img, $directory . '/' . $date . '.gif');
        rename($directory . '/' . $date . '.gif', $image);
        break;
      case IMAGETYPE_PNG:
        imagepng($emp_img, $directory . '/' . $date . '.png');
        rename($directory . '/' . $date . '.png', $image);
        break;
    }
    imagedestroy($emp_img);
    imagedestroy($new_image);
  }

  public function csvToArray($numof_column)
  {
    $results = array(
      'successes' => array(),
      'errors'    => array(),
    );
    $records = array();
    $file    = null;

    $file = $this->_request->getFiles()['file'];

    if (isset($file['error']) and is_int($file['error'])) {

      try {

        switch ($file['error']) {
          case UPLOAD_ERR_OK:
            // エラー無し
            break;
          case UPLOAD_ERR_NO_FILE:
            // ファイル未選択
            throw new RuntimeException('ファイルを選択してください。');
          case UPLOAD_ERR_INI_SIZE:
          case UPLOAD_ERR_FORM_SIZE:
            // 許可サイズを超過
            throw new RuntimeException('ファイルサイズが大きすぎます。');
          default:
            throw new RuntimeException('原因不明のエラーです。');
        }

        $tmp_name = $file['tmp_name'];
        $detect_order = 'ASCII,JIS,UTF-8,CP51932,SJIS-win';
        setlocale(LC_ALL, 'ja_JP.UTF-8');

        // 文字コードを変換してファイルを置換
        $buffer = file_get_contents($tmp_name);

        if (!$encoding = mb_detect_encoding($buffer, $detect_order, true)) {

          // 文字コードの自動判定に失敗
          unset($buffer);
          throw new RuntimeException('文字コードの自動判定に失敗しました。');
        }

        file_put_contents(
          $tmp_name,
          mb_convert_encoding($buffer, 'UTF-8', $encoding)
        );
        unset($buffer);

        try {
          $handle = fopen($tmp_name, 'rb');
          //fgetcsv($handle); // 1行目空読みによるスキップ処理
          while ($record = fgetcsv($handle)) {

            if ($record === array(null)) {

              // 空行はスキップ
              continue;
            }

            if (count($record) !== $numof_column) {

              throw new RuntimeException(
                'カラム数が異なる無効なフォーマットです。'
              );
            }

            $records[] = $record;
          }

          if (!feof($handle)) {
            // ファイルポインタが終端に達していなければエラー
            throw new RuntimeException('CSVファイルの解析に失敗しました。');
          }

          fclose($handle);
        } catch (Exception $e) {

          fclose($handle);
          throw $e;
        }

        $results['successes'] = $records;

        return $results;
      } catch (Exception $e) {

        $results['errors'][] = $e->getMessage();
        return $results;
      }
    }
  }

  protected function exportCSV($filename, $data)
  {

    $results = array(
      'successes' => array(),
      'errors'    => array(),
    );

    try {

      $tmp_path = sprintf(
                    "%s/tmp/%s.csv",
                    $this->_application
                         ->getVarDirectory(),
                    time().rand()
                  );

      $handle = fopen($tmp_path, 'w');

      if ($handle === false) {

        throw new RuntimeException(
                    sprintf(
                      "[%s]ファイルの書き込みに失敗しました。",
                      $tmp_path
                    )
                  );
      }

      foreach ($data as $key => $value) {

        mb_convert_variables(
          'SJIS',
          'UTF-8',
          $value
        );

        fputcsv($handle, $value);
      }

      fclose($handle);
      $buff = str_replace("\n", "\r\n", file_get_contents($tmp_path));
      $handle = fopen($tmp_path, 'w');
      fwrite($handle, $buff);
      fclose($handle);

      header('Content-Type: application/octet-stream');
      header("Content-Disposition: attachment; filename={$filename}.csv");
      header('Content-Transfer-Encoding: binary');
      header('Content-Length: '.filesize($tmp_path));
      readfile($tmp_path);
      unlink($tmp_path);
    } catch(Exception $e) {

      $results['errors'][] = $e->getMessage();

      return $results;
    }
  }

  private function dup($model, $from, $to)
  {
    foreach ($this->_connect_model
                  ->get($model)
                  ->gets($from) as $index => $data) {

      unset($data['id']);
      $data['user_id'] = $to;

      $this->_connect_model
           ->get($model)
           ->create($data);
    }

  }

  private function duplicate($user_id_from, $user_id_to)
  {

    $this->dup('ResearchAnalysis', $user_id_from, $user_id_to);
    $this->dup('ResearchNewArrival', $user_id_from, $user_id_to);
    $this->dup('ResearchYahooAuctionsSearch', $user_id_from, $user_id_to);
    $this->dup('SettingItemAccessories', $user_id_from, $user_id_to);
    $this->dup('SettingItemCategory', $user_id_from, $user_id_to);
    $this->dup('SettingItemConditionEbayUs', $user_id_from, $user_id_to);
    $this->dup('SettingItemConditionYahooAuctions', $user_id_from, $user_id_to);
    $this->dup('SettingItemDescription', $user_id_from, $user_id_to);
    $this->dup('SettingItemGrade', $user_id_from, $user_id_to);
    $this->dup('SettingItemMaker', $user_id_from, $user_id_to);
    $this->dup('SettingItemTemplateAmazonJp', $user_id_from, $user_id_to);
    $this->dup('SettingItemTemplateEbayUs', $user_id_from, $user_id_to);
    $this->dup('SettingItemTemplateYahooAuctions', $user_id_from, $user_id_to);
  }

  private function del($model, $user_id)
  {
    foreach ($this->_connect_model
                  ->get($model)
                  ->gets($user_id) as $index => $data) {

      $data['deleted_at'] = $this->_datetime
                                 ->format('Y-m-d H:i:s');

      $this->_connect_model
             ->get($model)
             ->delete($data);
    }

  }

  private function delete($user_id)
  {

    $this->del('Item', $user_id);
    $this->del('Bids', $user_id);
    $this->del('ResearchStores', $user_id);
    $this->del('ResearchAnalysis', $user_id);
    $this->del('ResearchNewArrival', $user_id);
    $this->del('ResearchWatchList', $user_id);
    $this->del('ResearchYahooAuctionsSearch', $user_id);
    $this->del('SettingItemAccessories', $user_id);
    $this->del('SettingItemCategory', $user_id);
    $this->del('SettingItemConditionEbayUs', $user_id);
    $this->del('SettingItemConditionYahooAuctions', $user_id);
    $this->del('SettingItemDescription', $user_id);
    $this->del('SettingItemGrade', $user_id);
    $this->del('SettingItemMaker', $user_id);
    $this->del('SettingItemMyPattern', $user_id);
    $this->del('SettingItemTemplateAmazonJp', $user_id);
    $this->del('SettingItemTemplateEbayUs', $user_id);
    $this->del('SettingItemTemplateYahooAuctions', $user_id);
    $this->del('UseResearchAnalysisArchive', $user_id);
  }

  protected function inProgress($data)
  {

    return array(
      'migration' =>$this->inProgressMigration($data),
      'chatwork' => array(
        'contact_with_admin' => $this->inProgressChatWorkContactWithAdmin($data),
      ),
      'yahoo_auctions' => array(
        'seller' => $this->inProgressYahooAuctionsSeller($data),
        'buyer' => $this->inProgressYahooAuctionsBuyer($data),
      ),
      'ebay' => $this->inProgressEbay($data),
      'migration' =>$this->inProgressMigration($data),
    );
  }

  private function inProgressChatWorkContactWithAdmin($data)
  {

    $account = $this->getAccount();

    if (intval($account['chatwork_contact_with_admin']) === 0 and
        strlen($account['chatwork_id']) > 0 and
        strlen($account['chatwork_account_id']) === 0) {

      return true;
    } else {

      return false;
    }
  }

  private function inProgressYahooAuctionsSeller($data)
  {

    $user = null;
    $user = $this->getAccount();

    if ((int)$user['yahoo_auctions_seller_request_captcha'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function inProgressYahooAuctionsBuyer($data)
  {

    $user = null;
    $user = $this->getAccount();

    if ((int)$user['yahoo_auctions_buyer_request_captcha'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function inProgressEbay($data)
  {

    $user = null;
    $user = $this->getAccount();

    if ((int)$user['request_ebay_us_auth_token'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function inProgressMigration($data)
  {

    $user = null;
    $user = $this->getAccount();

    if ((int)$user['request_migration'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  protected function getAccount()
  {

    return $this->_connect_model
                ->relay('m', 'Account')
                ->get($this->_session->get('user')['id']);
  }

  protected function jsonSafeEncode($data)
  {

    return json_encode(
             $data,
             JSON_HEX_TAG  |
             JSON_HEX_AMP  |
             JSON_HEX_APOS |
             JSON_HEX_QUOT
           );
  }

  protected function generateToken()
  {

    return sha1(uniqid(rand(), true));
  }

}
