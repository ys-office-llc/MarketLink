<?php
class SettingController extends BasicController
{
  const _INDEX_ITEM_TEMPLATE = 'template';
  const _LIST_ITEM_TEMPLATE_YAHOO_AUCTIONS  = 'item/template/yahoo/auctions/list';
  const _GET_ITEM_TEMPLATE_YAHOO_AUCTIONS   = 'item/template/yahoo/auctions/get';
  const _MODEL_ITEM_TEMPLATE_YAHOO_AUCTIONS = 'ItemTemplateYahooAuctionsSetting';
  const _POST_ITEM_TEMPLATE_YAHOO_AUCTIONS  = 'setting/item/template/yahoo/auctions/post';

  const _LIST_ITEM_TEMPLATE_EBAY_US  = 'item/template/ebay/us/list';
  const _GET_ITEM_TEMPLATE_EBAY_US   = 'item/template/ebay/us/get';
  const _MODEL_ITEM_TEMPLATE_EBAY_US = 'ItemTemplateEbayUsSetting';
  const _POST_ITEM_TEMPLATE_EBAY_US  = 'setting/item/template/ebay/us/post';

  const _INDEX_ITEM_CONDITION = 'condition';
  const _LIST_ITEM_CONDITION_YAHOO_AUCTIONS  = 'item/condition/yahoo/auctions/list';
  const _GET_ITEM_CONDITION_YAHOO_AUCTIONS   = 'item/condition/yahoo/auctions/get';
  const _MODEL_ITEM_CONDITION_YAHOO_AUCTIONS = 'ItemConditionYahooAuctionsSetting';
  const _POST_ITEM_CONDITION_YAHOO_AUCTIONS  = 'setting/item/condition/yahoo/auctions/post';

  const _LIST_ITEM_CONDITION_EBAY_US  = 'item/condition/ebay/us/list';
  const _GET_ITEM_CONDITION_EBAY_US   = 'item/condition/ebay/us/get';
  const _MODEL_ITEM_CONDITION_EBAY_US = 'ItemConditionEbayUsSetting';
  const _POST_ITEM_CONDITION_EBAY_US  = 'setting/item/condition/ebay/us/post';

  protected $_authentication = array(
    'listItemTemplateYahooAuctions',
    'getItemTemplateYahooAuctions',
    'postItemTemplateYahooAuctions',
    'listItemTemplateEbayUs',
    'getItemTemplateEbayUs',
    'postItemTemplateEbayUs',
    'listItemConditionYahooAuctions',
    'getItemConditionYahooAuctions',
    'postItemConditionYahooAuctions',
    'listItemConditionEbayUs',
    'getItemConditionEbayUs',
    'postItemConditionEbayUs',
  );


  private function getList($model)
  {
    $this->_user = $this->_session->get('user');

    return $this->_connect_model
                ->get($model)
                ->gets($this->_user['id']);
  }

  public function listItemTemplateYahooAuctionsAction()
  {
    return $this->render(
      array(
        'templates' => $this->getList(
          self::_MODEL_ITEM_TEMPLATE_YAHOO_AUCTIONS
        ),
        '_token'    => $this->getToken(
          self::_POST_ITEM_TEMPLATE_YAHOO_AUCTIONS
        ),
      ), self::_LIST_ITEM_TEMPLATE_YAHOO_AUCTIONS);
  }

  public function listItemTemplateEbayUsAction()
  {
    return $this->render(
      array(
        'templates' => $this->getList(
          self::_MODEL_ITEM_TEMPLATE_EBAY_US
        ),
        '_token'    => $this->getToken(
          self::_POST_ITEM_TEMPLATE_EBAY_US
        ),
      ), self::_LIST_ITEM_TEMPLATE_EBAY_US);
  }

  public function listItemConditionYahooAuctionsAction()
  {
    return $this->render(
      array(
        'conditions' => $this->getList(
          self::_MODEL_ITEM_CONDITION_YAHOO_AUCTIONS
        ),
        '_token'    => $this->getToken(
          self::_POST_ITEM_CONDITION_YAHOO_AUCTIONS
        ),
      ), self::_LIST_ITEM_CONDITION_YAHOO_AUCTIONS);
  }

  public function listItemConditionEbayUsAction()
  {
    return $this->render(
      array(
        'conditions' => $this->getList(
          self::_MODEL_ITEM_CONDITION_EBAY_US
        ),
        '_token'     => $this->getToken(
          self::_POST_ITEM_CONDITION_EBAY_US
        ),
      ), self::_LIST_ITEM_CONDITION_EBAY_US);
  }

  public function getItemTemplateEbayUsAction($params)
  {
    $template    = null;
    $this->_user = $this->_session->get('user');

    if (isset($params['id'])) {
      $template = $this->_connect_model
                       ->get(self::_MODEL_ITEM_TEMPLATE_EBAY_US)
                       ->get($params['id'], $this->_user['id']);

      if (!$template) {
        $this->httpNotFound();
      }
    }

    return $this->render(
      array(
        'template' => $template,
        '_token'   => $this->getToken(self::_POST_ITEM_TEMPLATE_EBAY_US)
      ), self::_GET_ITEM_TEMPLATE_EBAY_US);
  }

  public function getItemTemplateYahooAuctionsAction($params)
  {
    $template    = null;
    $this->_user = $this->_session->get('user');

    if (isset($params['id'])) {
      $template = $this->_connect_model
                       ->get(self::_MODEL_ITEM_TEMPLATE_YAHOO_AUCTIONS)
                       ->get($params['id'], $this->_user['id']);

      if (!$template) {
        $this->httpNotFound();
      }
    }

    return $this->render(
      array(
        'template' => $template,
        '_token'   => $this->getToken(self::_POST_ITEM_TEMPLATE_YAHOO_AUCTIONS)
      ), self::_GET_ITEM_TEMPLATE_YAHOO_AUCTIONS);
  }

  public function getItemConditionYahooAuctionsAction($params)
  {
    $condition = null;
    $this->_user = $this->_session->get('user');
    if (isset($params['id'])) {
      $condition = $this->_connect_model
                        ->get(self::_MODEL_CONDITION_YAHOO_AUCTIONS)
                        ->get($params['id'], $this->_user['id']);

      if (!$condition) {
        $this->httpNotFound();
      }
    }

    return $this->render(
      array(
        'condition'    => $condition,
        'table_values' => $this->_connect_model
                               ->get(self::_MODEL_ITEM_CONDITION_YAHOO_AUCTIONS)
                               ->getTableValues(),
        '_token'       => $this->getToken(self::_POST_ITEM_CONDITION_YAHOO_AUCTIONS)
      ), self::_GET_ITEM_CONDITION_YAHOO_AUCTIONS);
  }

  public function getItemConditionEbayUsAction($params)
  {
    $condition = null;
    $this->_user = $this->_session->get('user');
    if (isset($params['id'])) {
      $condition = $this->_connect_model
                        ->get(self::_MODEL_CONDITION_EBAY_US)
                        ->get($params['id'], $this->_user['id']);

      if (!$condition) {
        $this->httpNotFound();
      }
    }

    return $this->render(
      array(
        'condition'    => $condition,
        'table_values' => $this->_connect_model
                               ->get(self::_MODEL_ITEM_CONDITION_EBAY_US)
                               ->getTableValues(),
        '_token'       => $this->getToken(self::_POST_ITEM_CONDITION_EBAY_US)
      ), self::_GET_ITEM_CONDITION_EBAY_US);
  }

  private function verifyItemTemplate($template)
  {
    $errors = array();

     if (!strlen($template['name'])) {
      $errors[] = 'テンプレート名を入力してください';
    }

    if (!strlen($template['title'])) {
      $errors[] = 'タイトルフォーマットを入力してください';
    }

    if (!strlen($template['template'])) {
      $errors[] = 'テンプレートを入力してください';
    }

    return $errors;
  }

  public function postItemTemplateYahooAuctionsAction()
  {
    $template    = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {
      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken(self::_POST_ITEM_TEMPLATE_YAHOO_AUCTIONS, $token)) {
      return $this->redirect('/');
    }

    $type     = $this->_request->getSubmitType();
    $template = $this->_connect_model
                     ->get(self::_MODEL_ITEM_TEMPLATE_YAHOO_AUCTIONS)
                     ->desc();
    $template = $this->fillValue($template);

    $successes = array();
    $render = array(
      'errors'    => $this->verifyItemTemplate($template),
      'successes' => $successes,
      'template'  => $template,
      '_token'    => $this->getToken(self::_POST_ITEM_TEMPLATE_YAHOO_AUCTIONS),
    );

    return $this->commit($type,
                         $render,
                         self::_INDEX_ITEM_TEMPLATE,
                         self::_MODEL_ITEM_TEMPLATE_YAHOO_AUCTIONS,
                         self::_GET_ITEM_TEMPLATE_YAHOO_AUCTIONS);
  }

  public function postItemTemplateEbayUsAction()
  {
    $template    = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {
      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken(self::_POST_ITEM_TEMPLATE_EBAY_US, $token)) {
      return $this->redirect('/');
    }

    $type     = $this->_request->getSubmitType();
    $template = $this->_connect_model
                     ->get(self::_MODEL_ITEM_TEMPLATE_EBAY_US)
                     ->desc();
    $template = $this->fillValue($template);

    $successes = array();
    $render = array(
      'errors'    => $this->verifyItemTemplate($template),
      'successes' => $successes,
      'template'  => $template,
      '_token'    => $this->getToken(self::_POST_ITEM_TEMPLATE_EBAY_US),
    );

    return $this->commit($type,
                         $render,
                         self::_INDEX_ITEM_TEMPLATE,
                         self::_MODEL_ITEM_TEMPLATE_EBAY_US,
                         self::_GET_ITEM_TEMPLATE_EBAY_US);
  }

  private function verifyItemConditionYahooAuctions($condition)
  {
    $errors = array();

    if (!strlen($condition['name'])) {
      $errors[] = '条件名を入力してください';
    }

    if ($condition['exhibits_style_id'] < 1) {
      $errors[] = '販売形式を選択してください';
    }

    if ($condition['sales_period_id'] < 1) {
      $errors[] = '開催期間を選択してください';
    }

    if ($condition['shipping_origin_id'] < 1) {
      $errors[] = '都道府県を選択してください';
    }

    if ($condition['item_status_id'] < 1) {
      $errors[] = '商品の状態を選択してください';
    }

    if ($condition['accept_returns_id'] < 1) {
      $errors[] = '返品可否を選択してください';
    }

    if ($condition['endtime_id'] < 1) {
      $errors[] = '終了時間を選択してください';
    }

    if ($condition['iteration_count_id'] < 1) {
      $errors[] = '自動再出品回数を選択してください';
    }

    if ($condition['shipname_standard_id'] < 1) {
      $errors[] = '配送方法を選択してください';
    }

    if (!strlen($condition['delivery_cost'])) {
      $errors[] = '配送料金を入力してください';
    }

    if (!strlen($condition['delivery_additional_cost'])) {
      $errors[] = '配送追加料金を入力してください';
    }

    return $errors;
  }

  public function postItemConditionYahooAuctionsAction()
  {
    $condition = array();
    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {
      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken(self::_POST_ITEM_CONDITION_YAHOO_AUCTIONS, $token)) {
      return $this->redirect('/');
    }

    $type = $this->_request->getSubmitType();
    $condition = $this->_connect_model
                      ->get(self::_MODEL_ITEM_CONDITION_YAHOO_AUCTIONS)
                      ->desc();
    $condition = $this->fillValue($condition);

    $successes = array();
    $render = array(
      'errors'       => $this->verifyItemConditionYahooAuctions($condition),
      'successes'    => $successes,
      'condition'    => $condition,
      'table_values' => $this->_connect_model
                             ->get(self::_MODEL_ITEM_CONDITION_YAHOO_AUCTIONS)
                             ->getTableValues(),
      '_token'       => $this->getToken(self::_POST_ITEM_CONDITION_YAHOO_AUCTIONS),
    );

    return $this->commit($type,
                         $render,
                         self::_INDEX_ITEM_CONDITION,
                         self::_MODEL_ITEM_CONDITION_YAHOO_AUCTIONS,
                         self::_GET_ITEM_CONDITION_YAHOO_AUCTIONS);
  }

}
