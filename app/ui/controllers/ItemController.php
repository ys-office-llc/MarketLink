<?php
class ItemController extends BasicController
{
  const _INDEX = 'item';
  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';

  protected $_authentication = array(
    self::_LIST,
    self::_GET,
    self::_POST,
  );

  private $search  = array();
  private $replace = array();
  private $submit_type = null;

  private function httpBuildQuery($data)
  {

    $param = array(
      'ebay' => array(
        '_nkw'  => $data['product_name'],
      ),
      'yahoo' => array(
        'auctions' => array(
          'p'   => $data['product_name'],
        ),
      ),
      'amazon' => array(
        'jp' => array(
          'field-keywords' => $data['product_name'],
        ),
      ),
      'mnrate' => array(
         'kwd' => $data['product_name'],
      ),
    );

    return array(
      'ebay' => array(
        'active' => $this->_configure
                         ->current['configure']['mvc'][
                           'controllers'
                         ]['item']['ebay']['url']['active'].'?'.
                    http_build_query(
                      array_merge(
                        $param['ebay'],
                        $this->_configure->current['configure']['mvc'][
                          'controllers'
                        ]['item']['ebay']['query']['active']
                      )
                    ),
        'sold' => $this->_configure
                       ->current['configure']['mvc'][
                         'controllers'
                       ]['item']['ebay']['url']['sold'].'?'.
                  http_build_query(
                    array_merge(
                      $param['ebay'],
                      $this->_configure->current['configure']['mvc'][
                        'controllers'
                      ]['item']['ebay']['query']['sold']
                    )
                  )
      ),
      'yahoo' => array(
        'auctions' => array(
          'selling' => $this->_configure
                            ->current['configure']['mvc']['controllers'][
                              'item'
                            ]['yahoo']['auctions']['url']['selling'].'?'.
                       http_build_query(
                         array_merge(
                           $param['yahoo']['auctions'],
                           $this->_configure->current['configure']['mvc'][
                             'controllers'
                           ]['item']['yahoo']['auctions']['query']['selling']
                         )
                       ),
          'sold' => $this->_configure
                         ->current['configure']['mvc'][
                           'controllers'
                         ]['item']['yahoo']['auctions']['url']['sold'].'?'.
                    http_build_query(
                      array_merge(
                        $param['yahoo']['auctions'],
                        $this->_configure->current['configure']['mvc'][
                          'controllers'
                        ]['item']['yahoo']['auctions']['query']['sold']
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
                           $param['amazon']['jp'],
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
      'mnrate' => $this->_configure
                       ->current['configure']['mvc']['controllers'][
                         'item'
                       ]['mnrate']['url']['search'].'?'.
                       http_build_query(
                         array_merge(
                           $param['mnrate'],
                           $this->_configure->current['configure']['mvc'][
                             'controllers'
                           ]['item']['mnrate']['query']['search']
                         )
                       ),
    );
  }

  public function getUrl()
  {
    return sprintf("%s/%s",
      self::PROTOCOL . $this->_request->getHostName(),
      basename($this->_application->getImagesDirectory())
    );
  }

  public function listAction($params)
  {
    $state = $this->getState(self::_INDEX);
    $state_id = (int)$params['state_id'];
    $user  = $this->_session->get('user');

    if ($user['merchandise_management'] !== 'enable') {

      $this->httpForbidden();
    }

    switch ($state_id) {
      case $state['waiting']:
      case $state['exhibit']:
      case $state['selling']:
      case $state['payment']:
      case $state['shipment']:
        break;
      default:
        $this->httpNotFound();
    }

    //$user  = $this->_session->get('user');
    $items = $this->_connect_model
                  ->get($this->_controller)
                  ->getsByStateId($user['id'], $state_id);

    $this->set();
    return $this->render(array(
      'state'     => $state,
      'state_id'  => $state_id,
      'items'     => $items,
      'table_values' => $this->_connect_model
                             ->get($this->_controller)
                             ->getTableValues(),
      'view_path' => $this->_view_path,
      'go_update' => null,
      'url'       => $this->getUrl(),
      '_token'    => $this->getToken($this->_view_path . '/' . self::_POST),
    ));
  }
 
  private function isReserved($data)
  {
    $state    = $this->getState(self::_INDEX);
    $reserved = array();

    foreach ($data as $key => $value) {

      if (preg_match("/_state_id$/", $key)) {
        switch ((int)$value) {
          case $state['reserve_add_item']:
          case $state['reserve_relist_item']:
          case $state['reserve_revise_item']:
          case $state['reserve_end_item']:
            $reserved[$key] = true;
            break;
          default:
            $reserved[$key] = false;
        }
      }
    }

    if (in_array(true, $reserved)) {
      return true;
      //$this->reserved = true;
    } else {
      //if (preg_match("/_reserve_/", $this->_request->getSubmitType())) {
      if (preg_match("/_reserve_/", $this->submit_type)) {
        return true;
        //$this->reserved = true;
      } else {
        return false;
        //$this->reserved = false;
      }
    }
  }

  private function isWaiting($data)
  {
    $state   = $this->getState(self::_INDEX);
    $waiting = array();

    foreach ($data as $key => $value) {

      if (preg_match("/_state_id$/", $key)) {
        switch ((int)$value) {
          case $state['exclude']:
          case $state['waiting']:
            $waiting[$key] = true;
            break;
          default:
            $waiting[$key] = false;
        }
      }
    }

    if (in_array(false, $waiting)) {
      return false;
    } else {
      // 全ての要素が「待機」「除外」であればtrue
      return true;
    }
  }

  private function isExhibit($data)
  {
    $state   = $this->getState(self::_INDEX);
    $exhibit = array();

    foreach ($data as $key => $value) {

      if (preg_match("/_state_id$/", $key)) {

        switch ((int)$value) {
          case $state['exhibit']:
            $exhibit[$key] = true;
            break;
          default:
            $exhibit[$key] = false;
        }
      }
    }

    if (in_array(true, $exhibit)) {

      // いずれかが「出品中」であればtrue
      return true;
    } else {

      return false;
    }
  }

  private function isShipment($data)
  {
    $state    = $this->getState(self::_INDEX);
    $shipment = array();

    foreach ($data as $key => $value) {

      if (preg_match("/_state_id$/", $key)) {
        switch ((int)$value) {
          case $state['shipment']:
            $shipment[$key] = true;
            break;
          default:
            $shipment[$key] = false;
        }
      }

    }

    if (in_array(false, $shipment)) {

      return false;
    } else {

      // 全ての要素が「出庫」であればtrue
      return true;
    }
  }

  private function isPayment($data)
  {
    $state    = $this->getState(self::_INDEX);
    $payment = array();

    foreach ($data as $key => $value) {

      if (preg_match("/_state_id$/", $key)) {
        switch ((int)$value) {
          case $state['payment']:
            $payment[$key] = true;
            break;
          default:
            $payment[$key] = false;
        }
      }

    }

    if (in_array(false, $payment)) {

      return false;
    } else {

      // 全ての要素が「出庫」であればtrue
      return true;
    }
  }

  public function getAction($params)
  {
    $state = $this->getState(self::_INDEX);
    $data = array();
    $user = $this->_session->get('user');
    $http_query = null;

    if ($user['merchandise_management'] !== 'enable') {

      $this->httpForbidden();
    }

    if (isset($params['id'])) {

      $data = $this->_connect_model
                   ->get($this->_controller)
                   ->get($params['id'], $user['id']);

      $http_query = $this->httpBuildQuery($data);

      if (!$data) {

        $this->httpNotFound();
      }

    } else {
      $data = $this->_connect_model
                   ->get($this->_controller)
                   ->desc();
      $data = $this->fillValue($data);
    }

    $this->set();
    mb_internal_encoding('UTF-8');
    return $this->render(array(
      'item'         => $this->unserializer($this->_controller, $data),
      'state'        => $state,
      'reserved'     => $this->isReserved($data),
      'waiting'      => $this->isWaiting($data),
      'exhibit'      => $this->isExhibit($data),
      'shipment'     => $this->isShipment($data),
      'payment'      => $this->isPayment($data),
      'prepare'      => $this->prepare = array(
                          'basic' => $this->prepareBasic($data),
                          'yahoo_auctions' => $this->prepareYahooAuctions($data),
                          'ebay_us' => $this->prepareEbayUs($data),
                          'amazon_jp' => $this->prepareAmazonJp($data),
                        ),
      'go_update'    => null,
      'endtime'      => $this->getEndDateTimeYahooAuctions($data),
      'yahoo_auctions_product_name_size'
                     =>  strlen(
                           mb_convert_encoding(
                             $data['yahoo_auctions_product_name'],
                             'SJIS',
                             'UTF-8'
                           )
                         ) / 2,
      'ebay_us_product_name_size'
                     =>  strlen($data['ebay_us_product_name']),
      'table_values' => $this->_connect_model
                             ->get($this->_controller)
                             ->getTableValues(),
      'http_query'   => $http_query,
      'view_path'    => $this->_view_path,
      'url'          => $this->getUrl(),
      '_token'       => $this->getToken($this->_view_path . '/' . self::_POST),
    ));
  }

  private function verify($type, $data)
  {
    $errors = array();

    if (!strlen($data['product_name'])) {

      $errors[] = '[商品名]を入力してください';
    }

    if ((int)$data['cost_price'] < 1) {

      $errors[] = '[個体情報] > [仕入価格]を入力してください';
    }

    if (preg_match("/\D+/", $data['yahoo_auctions_start_price'])) {

      $errors[] = sprintf("%s（%s）", '[ヤフオク] > [開始価格]に数値以外の文字列が入力されています。', $data['yahoo_auctions_start_price']);
    }

    if (preg_match("/\D+/", $data['yahoo_auctions_end_price'])) {

      $errors[] = sprintf("%s（%s）", '[ヤフオク] > [終了価格]に数値以外の文字列が入力されています。', $data['yahoo_auctions_end_price']);
    }

    if (preg_match("/\D+/", $data['yahoo_auctions_reserve_price'])) {

      $errors[] = sprintf("%s（%s）", '[ヤフオク] > [最低落札価格]に数値以外の文字列が入力されています。', $data['yahoo_auctions_reserve_price']);
    }

    if (preg_match("/\D+/", $data['ebay_us_start_price'])) {

      $errors[] = sprintf("%s（%s）", '[eBay] > [開始価格]に数値以外の文字列が入力されています。', $data['ebay_us_start_price']);
    }

    if (preg_match("/\D+/", $data['ebay_us_end_price'])) {

      $errors[] = sprintf("%s（%s）", '[eBay] > [終了価格]に数値以外の文字列が入力されています。', $data['ebay_us_end_price']);
    }

    if (preg_match("/\D+/", $data['amazon_jp_price'])) {

      $errors[] = sprintf("%s（%s）", '[Amazon.co.jp] > [出品価格]に数値以外の文字列が入力されています。', $data['amazon_jp_price']);
    }

    if (self::TYPE_CREATE === $type and
        strlen($data['stock_keeping_unit']) > 0 and
        $this->_connect_model
             ->get($this->_controller)
             ->existsSku(
                 array(
                   'stock_keeping_unit' => $data['stock_keeping_unit']
                 )
               )) {

      $errors[] = 'SKUは既に利用されています';
    }

    if (strlen($data['ebay_us_product_name']) > 0 and
        preg_match_all(
          "/([&])/",
          $data['ebay_us_product_name'],
          $matches
        )) {

      $errors[] = sprintf(
                    "[タイトル] > [eBay] > [付け加えたい文字]".
                    "に利用できない文字が含まれています。→（%s）",
                    implode(
                      ' ',
                      $matches[0]
                    )
                  );
    }

    if (preg_match("/_(add|revise|resubmit)_item/", $type, $matches)) {

      if ($this->countProductNameYahooAuctions(
            $data['yahoo_auctions_product_name']
          ) > 65) {

        $errors[] = 'タイトル > ヤフオクの文字数が65文字を超えています';
      }

      if (strlen($data['ebay_us_product_name']) > 80) {

        $errors[] = 'タイトル > eBayの文字数が80文字を超えています';
      }

      if ((int)$data['maker_id'] === 0) {

        $errors[] = '商品情報 > メーカーを入力してください';
      }

      if ((int)$data['category_id'] === 0) {

        $errors[] = '商品情報 > カテゴリーを入力してください';
      }

      if ((int)$data['grade_id'] === 0) {

        $errors[] = '商品情報 > グレードを入力してください';
      }

      if ((int)$data['description_id'] === 0) {

        $errors[] = '商品情報 > 説明文を入力してください';
      }

      if ((int)$data['accessories_id'] === 0) {

        $errors[] = '商品情報 > 付属品を入力してください';
      }

      if (count($errors)) {

        $this->submit_type = self::TYPE_UPDATE;
      }
    }

    return $errors;
  }

  public function postAction()
  {
    $state = $this->getState(self::_INDEX);
    $data = array();
    $this->_user = $this->_session->get('user');
    $http_query = null;
    $delete_by_checked = null;

    if (!$this->_request->isPost()) {
      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {
      return $this->redirect('/');
    }

    $delete_by_checked = $this->_request
                              ->getPost('delete_by_checked');

    if ($delete_by_checked) {

      if (!is_null($this->_request->getPosts()['id'])) {

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

      $state = $this->getState(self::_INDEX);
      $items = $this->_connect_model
                    ->get($this->_controller)
                    ->getsByStateId($this->_user['id'], 10);

      $this->set();
      return $this->render(array(
        'state'     => $state,
        'state_id'  => 10,
        'items'     => $items,
        'table_values' => $this->_connect_model
                               ->get($this->_controller)
                               ->getTableValues(),
        'view_path' => $this->_view_path,
        'go_update' => null,
        'url'       => $this->getUrl(),
        '_token'    => $this->getToken($this->_view_path . '/' . self::_POST),
      ), self::_LIST);
    }

    $type = $this->_request->getSubmitType();
    $this->submit_type = $this->_request->getSubmitType();
    $data = $this->_connect_model
                 ->get($this->_controller)
                 ->desc();
    $data = $this->fillValue($data);
    $data = $this->serializer($this->_controller, $data);

    if ($data['my_pattern_id'] > 0) {

      $data = $this->setMyPattern($data, $type);
    }

    if (self::TYPE_CREATE !== $type) {
      $data = $this->makePageYahooAuctions($data);
      $data = $this->makePageEbayUs($data);
      $data = $this->makePageAmazonJp($data);
    }

    $data['product_name'] = trim($data['product_name']);
    $http_query = $this->httpBuildQuery($data);

    $this->set();
    //$this->isReserved($data);
    $successes = array();
    $render = array(
      'errors'       => $this->verify($type, $data),
      'successes'    => $successes,
      'state'        => $state,
      'reserved'     => $this->isReserved($data),
      //'reserved'     => $this->reserved,
      'waiting'      => $this->isWaiting($data),
      'exhibit'      => $this->isExhibit($data),
      'shipment'     => $this->isShipment($data),
      'pyament'      => $this->isPayment($data),
      'prepare'      => $this->prepare = array(
                          'basic' => $this->prepareBasic($data),
                          'yahoo_auctions' => $this->prepareYahooAuctions($data),
                          'ebay_us' => $this->prepareEbayUs($data),
                          'amazon_jp' => $this->prepareAmazonJp($data),
                        ),
      'go_update'    => null,
      'endtime'      => $this->getEndDateTimeYahooAuctions($data),
      'yahoo_auctions_product_name_size'
                     =>  strlen(
                           mb_convert_encoding(
                             $data['yahoo_auctions_product_name'],
                             'SJIS',
                             'UTF-8'
                           )
                         ) / 2,
      'ebay_us_product_name_size'
                     =>  strlen($data['ebay_us_product_name']),
      self::_INDEX   => $data,
      'table_values' => $this->_connect_model
                             ->get($this->_controller)
                             ->getTableValues(),
      'http_query'   => $http_query,
      'view_path'    => $this->_view_path,
      'url'          => $this->getUrl(),
      '_token'       => $this->getToken($this->_view_path . '/' . self::_POST),
    );

    return $this->commit($type,
                         $render,
                         self::_INDEX,
                         $this->_controller,
                         self::_GET);
  }

  private function getMyPattern($data)
  {
    return $this->_connect_model
                ->get('SettingItemMyPattern')
                ->get(
                    $data['my_pattern_id'],
                    $data['user_id']
                  );
  }

  private function getConditionYahooAuctions($data)
  {
    return $this->_connect_model
                ->get('SettingItemConditionYahooAuctions')
                ->get(
                    $data['yahoo_auctions_condition_id'],
                    $data['user_id']
                  );
  }

  private function getGrade($data)
  {
    return $this->_connect_model
                ->get('SettingItemGrade')
                ->get(
                    $data['grade_id'],
                    $data['user_id']
                  );
  }

  private function getMaker($data)
  {
    $makers = array();

    $values = $this->_connect_model
                   ->get($this->_controller)
                   ->getTableValues();

    $maker = $this->_connect_model
                  ->get('SettingItemMaker')
                  ->get(
                      $data['maker_id'],
                      $data['user_id']
                    );

    if ($maker) {
      foreach ($maker as $key => $value) {
        if (preg_match("/_id$/", $key)) {
          $replace_key = sprintf(
            "item_maker_%s",
            str_replace('_id', '', $key)
          );
      
          if (isset($values[$replace_key])) {
            $makers[$replace_key] =
              $values[$replace_key][$maker[$key]]['name'];
          }
        }
      }

      return array_merge($makers, $maker);
    } else {

      return $makers;
    }
  }

  private function getDescription($data)
  {
    return $this->_connect_model
                ->get('SettingItemDescription')
                ->get(
                    $data['description_id'],
                    $data['user_id']
                  );
  }

  private function getDescriptionCosmetics($data)
  {

    return $this->_connect_model
                ->get('SettingItemDescriptionCosmetics')
                ->get(
                    $data['description_cosmetics_id'],
                    $data['user_id']
                  );
  }

  private function getDescriptionOptics($data)
  {

    return $this->_connect_model
                ->get('SettingItemDescriptionOptics')
                ->get(
                    $data['description_optics_id'],
                    $data['user_id']
                  );
  }

  private function getDescriptionFunctions($data)
  {

    return $this->_connect_model
                ->get('SettingItemDescriptionFunctions')
                ->get(
                    $data['description_functions_id'],
                    $data['user_id']
                  );
  }

  private function getAccessories($data)
  {
    return $this->_connect_model
                ->get('SettingItemAccessories')
                ->get(
                    $data['accessories_id'],
                    $data['user_id']
                  );
  }

  private function getTemplateYahooAuctions($data)
  {
    return $this->_connect_model
                ->get('SettingItemTemplateYahooAuctions')
                ->get(
                    $data['yahoo_auctions_template_id'],
                    $data['user_id']
                  );
  }

  private function getTemplateEbayUs($data)
  {
    return $this->_connect_model
                ->get('SettingItemTemplateEbayUs')
                ->get(
                    $data['ebay_us_template_id'],
                    $data['user_id']
                  );
  }

  private function getTemplateAmazonJp($data)
  {
    return $this->_connect_model
                ->get('SettingItemTemplateAmazonJp')
                ->get(
                    $data['amazon_jp_template_id'],
                    $data['user_id']
                  );
  }

  private function makeTranslator($element_key, $elements)
  {
    if ($elements) {
      foreach ($elements as $key => $value) {
        if (preg_match("/^{$element_key}/", $key)) {
          if (strlen($value) > 0) {
            $this->search[]  = strtoupper($key);
            if (preg_match("/^image_/", $key)) {
              $this->replace[] = $this->getUrl() . '/' . $value;
            } else {
              $this->replace[] = $value;
            }
          }
        }
      }
    }
  }

  private function setMyPattern($data, $type)
  {
    $my_pattern = $this->getMyPattern($data);

    if (self::TYPE_UPDATE === $type) {

      foreach ($my_pattern as $key => $value) {
 
        // マイパターンと一致しない値は除外する
        if (preg_match("/^id$/", $key)   or
            preg_match("/^name$/", $key) or
            preg_match("/_at$/", $key)) {
 
          continue;
        }
 
        if ($value !== $data[$key]) {
 
          $my_pattern[$key]      = $data[$key];
          $data['my_pattern_id'] = 0;
        }
 
      }

    }

    $data['remarks_ja']     = $my_pattern['remarks_ja'];
    $data['remarks_en']     = $my_pattern['remarks_en'];
    $data['recommends_ja']  = $my_pattern['recommends_ja'];
    $data['recommends_en']  = $my_pattern['recommends_en'];
    $data['maker_id']       = $my_pattern['maker_id'];
    $data['category_id']    = $my_pattern['category_id'];
    $data['grade_id']       = $my_pattern['grade_id'];
    $data['description_id'] = $my_pattern['description_id'];
    $data['accessories_id'] = $my_pattern['accessories_id'];
    $data['yahoo_auctions_template_id']
                            = $my_pattern['yahoo_auctions_template_id'];
    $data['ebay_us_template_id']
                            = $my_pattern['ebay_us_template_id'];
    $data['amazon_jp_template_id']
                            = $my_pattern['amazon_jp_template_id'];
    $data['yahoo_auctions_condition_id']
                            = $my_pattern['yahoo_auctions_condition_id'];
    $data['ebay_us_condition_id']
                            = $my_pattern['ebay_us_condition_id'];

    return $data;
  }

  private function makePageYahooAuctions($data)
  {
    $maker       = $this->getMaker($data);
    $grade       = $this->getGrade($data);
    $description = $this->getDescription($data);
    $cosmetics   = $this->getDescriptionCosmetics($data);
    $optics      = $this->getDescriptionOptics($data);
    $functions   = $this->getDescriptionFunctions($data);
    $accessories = $this->getAccessories($data);
    $template    = $this->getTemplateYahooAuctions($data);

    $this->makeTranslator('maker_display_name_ja', $maker);
    $this->makeTranslator('item_maker_yahoo_auctions', $maker);
    $this->makeTranslator('item_maker_amazon_jp', $maker);
    $this->makeTranslator('description_ja', $description);
    $this->makeTranslator('description_cosmetics_ja', $cosmetics);
    $this->makeTranslator('description_optics_ja', $optics);
    $this->makeTranslator('description_functions_ja', $functions);
    $this->makeTranslator('accessories_ja', $accessories);
    $this->makeTranslator('grade_ja', $grade);
    $this->makeTranslator('product_name', $data);
    $this->makeTranslator('remarks_ja', $data);
    $this->makeTranslator('recommends_ja', $data);
    $this->makeTranslator('serial_number', $data);
    $this->makeTranslator('image_', $data);
    $this->makeTranslator('stock_keeping_unit', $data);

    $data['yahoo_auctions_product_name'] = str_replace(
      $this->search,
      $this->replace,
      $template['title']
    );

    $data['yahoo_auctions_page'] = str_replace(
      $this->search,
      $this->replace,
      $template['template']
    );

    return $data;
  }

  private function makePageEbayUs($data)
  {
    $maker       = $this->getMaker($data);
    $grade       = $this->getGrade($data);
    $description = $this->getDescription($data);
    $cosmetics   = $this->getDescriptionCosmetics($data);
    $optics      = $this->getDescriptionOptics($data);
    $functions   = $this->getDescriptionFunctions($data);
    $accessories = $this->getAccessories($data);
    $template    = $this->getTemplateEbayUs($data);

    $this->makeTranslator('item_maker_ebay_us', $maker);
    $this->makeTranslator('description_en', $description);
    $this->makeTranslator('description_cosmetics_en', $cosmetics);
    $this->makeTranslator('description_optics_en', $optics);
    $this->makeTranslator('description_functions_en', $functions);
    $this->makeTranslator('accessories_en', $accessories);
    $this->makeTranslator('grade_en', $grade);
    $this->makeTranslator('product_name', $data);
    $this->makeTranslator('remarks_en', $data);
    $this->makeTranslator('recommends_en', $data);
    $this->makeTranslator('serial_number', $data);
    $this->makeTranslator('image_', $data);
    $this->makeTranslator('stock_keeping_unit', $data);
    $this->search[] = 'IMAGE_EMS';
    $this->search[] = 'IMAGE_PAYPAL';
    $this->replace[] = $this->getUrl() . '/ems.jpg';
    $this->replace[] = $this->getUrl() . '/paypal.jpg';

    $data['ebay_us_product_name'] = str_replace(
      $this->search,
      $this->replace,
      $template['title']
    );

    $data['ebay_us_page'] = str_replace(
      $this->search,
      $this->replace,
      $template['template']
    );

    return $data;
  }
 
  private function makePageAmazonJp($data)
  {
    $maker       = $this->getMaker($data);
    $grade       = $this->getGrade($data);
    $description = $this->getDescription($data);
    $cosmetics   = $this->getDescriptionCosmetics($data);
    $optics      = $this->getDescriptionOptics($data);
    $functions   = $this->getDescriptionFunctions($data);
    $accessories = $this->getAccessories($data);
    $template    = $this->getTemplateAmazonJp($data);

    $this->makeTranslator('item_maker_yahoo_auctions', $maker);
    $this->makeTranslator('item_maker_amazon_jp', $maker);
    $this->makeTranslator('description_ja', $description);
    $this->makeTranslator('description_cosmetics_ja', $cosmetics);
    $this->makeTranslator('description_optics_ja', $optics);
    $this->makeTranslator('description_functions_ja', $functions);
    $this->makeTranslator('accessories_ja', $accessories);
    $this->makeTranslator('grade_ja', $grade);
    $this->makeTranslator('product_name', $data);
    $this->makeTranslator('remarks_ja', $data);
    $this->makeTranslator('recommends_ja', $data);
    $this->makeTranslator('serial_number', $data);
    $this->makeTranslator('image_', $data);
    $this->makeTranslator('stock_keeping_unit', $data);

/*
    $data['amazon_jp_product_name'] = str_replace(
      $this->search,
      $this->replace,
      $template['title']
    );
*/

    $data['amazon_jp_page'] = str_replace(
      $this->search,
      $this->replace,
      $template['template']
    );

    return $data;
  }

  private function getEndDateTimeYahooAuctions($data)
  {
    $values    = null;
    $condition = null;
    $week      = array('日', '月', '火', '水', '木', '金', '土');

    $values = $this->_connect_model
                   ->get($this->_controller)
                   ->getTableValues();

    $condition  = $this->getConditionYahooAuctions($data);
    $datetime_w = clone $this->_datetime;
    $datetime   = clone $this->_datetime;

    if ($condition) {

      $w = (int)$datetime_w->modify(
                   sprintf("+%s days",
                     $condition['sales_period_id']
                   )
                )->format('w');

      return sprintf("%s（%s）%s",
               $datetime->modify(
                 sprintf("+%s days",
                   $condition['sales_period_id']
                 )
               )->format('Y年m月d日'),
               $week[$w],
               $values[
                 'item_condition_yahoo_auctions_endtime'
               ][$condition['endtime_id']]['name']
             );
    } else {

      return null;
    }
  }

  private function countProductNameYahooAuctions($name)
  {

    return strlen(
             mb_convert_encoding(
               $name,
               'SJIS',
               'UTF-8'
             )
           ) / 2;
  }

  private function prepareBasic($data)
  {

    $user = null;
    $user = $this->getAccount();

    if ($data['maker_id'] > 0 and
        $data['category_id'] > 0 and
        $data['grade_id'] > 0 and
        $data['description_id'] > 0 and
        $data['accessories_id'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function prepareYahooAuctions($data)
  {

    $user = null;
    $user = $this->getAccount();

    if (strlen($user['yahooapis_seller_appid']) > 0 and
        strlen($user['yahooapis_seller_secret']) > 0 and
        $data['yahoo_auctions_template_id'] > 0 and
        $data['yahoo_auctions_condition_id'] > 0 and
        $data['yahoo_auctions_start_price'] > 0 and
        $data['yahoo_auctions_end_price'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function prepareEbayUs($data)
  {

    $user = null;
    $user = $this->getAccount();

    if (strlen($user['ebay_us_auth_token']) > 0 and
        $data['ebay_us_template_id'] > 0 and
        $data['ebay_us_condition_id'] > 0 and
        $data['ebay_us_start_price'] > 0 and
        $data['ebay_us_end_price'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function prepareAmazonJp($data)
  {

    $user = null;
    $user = $this->getAccount();

    if (strlen($user['amazon_jp_marketplace_id']) > 0 and
        strlen($user['amazon_jp_merchant_id']) > 0 and
        strlen($user['amazon_jp_access_key']) > 0 and
        strlen($user['amazon_jp_secret_key']) > 0 and
        strlen($user['amazon_jp_auth_token']) > 0 and
        $data['amazon_jp_template_id'] > 0 and
        strlen($data['amazon_jp_asin']) > 0 and
        $data['amazon_jp_price'] > 0) {

      return true;
    } else {

      return false;
    }
  }

}
