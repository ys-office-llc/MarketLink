<?php
class BidsController extends BasicController
{
  const _INDEX    = 'bids';
  const _REGISTER = 'register';
  const _LIST     = 'list';
  const _GET      = 'get';
  const _POST     = 'post';

  protected $_authentication = array(
    self::_REGISTER,
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

    return $dom->getElementById('USDJPY_top_bid')->nodeValue;
  }

  private function barrier()
  {

    if (!preg_match(
          "/^enable$/",
          $this->_session->get('user')['market_screening']
        ) and 
        (int)$this->_session->get('user')['account_contract_id'] > 1
       ) {

      $this->httpForbidden();
    }
  }

  public function registerAction($parameters)
  {

    $user = $this->_session->get('user');

    $query_parameters = array(
      'appid'     => $user['yahooapis_buyer_appid'],
      'auctionID' => $parameters['auction_id'],
    );

    $uri = 'https://auctions.yahooapis.jp'.
           '/AuctionWebService/V2/auctionItem?'.
           http_build_query($query_parameters);

    $html = mb_convert_encoding(
              file_get_contents($uri),
              'HTML-ENTITIES',
              'UTF-8'
            );

//var_dump($html);exit;

    $results = new SimpleXMLElement($html);

    $data = array(
      'auction_id' => strval($results->Result->AuctionID),
      'title' => strval($results->Result->Title),
      'seller_id' => strval($results->Result->Seller->Id),
      'rating_point' => strval($results->Result->Seller->Rating->Point),
      'rating_total_good_rating' => strval(
                                      $results->Result
                                              ->Seller
                                              ->Rating
                                              ->TotalGoodRating
                                    ),
      'rating_total_normal_rating' => strval(
                                      $results->Result
                                              ->Seller
                                              ->Rating
                                              ->TotalNormalRating
                                    ),
      'rating_total_bad_rating' => strval(
                                     $results->Result->Seller
                                             ->Rating
                                             ->TotalBadRating
                                   ),
      'auction_item_url' => strval($results->Result->AuctionItemUrl),
      'img_image1' => strval($results->Result->Img->Image1),
      'img_image2' => strval($results->Result->Img->Image2),
      'img_image3' => strval($results->Result->Img->Image3),
      'bids' => strval($results->Result->Bids),
      'initprice' => strval($results->Result->Initprice),
      'price' => strval($results->Result->Price),
      'start_time' => strval($results->Result->StartTime),
      'end_time' => strval($results->Result->EndTime),
      'bidorbuy' => strval($results->Result->Bidorbuy),
      'option_store_icon_url' => strval($results->Result->Option->StoreIcon),
      'option_check_icon_url' => strval($results->Result->Option->CheckIcon),
      'option_new_icon_url' => strval($results->Result->Option->NewItemIcon),
      'option_escrow_icon_url' => strval($results->Result->Option->EscrowIcon),
      'option_featured_icon_url' => strval($results->Result->Option->FeaturedIcon),
      'option_free_shipping_icon_url' => strval($results->Result->Option->FreeShippingIcon),
      'option_wrapping_icon_url' => strval($results->Result->Option->WrappingIcon),
      'option_buynow_icon_url' => strval($results->Result->Option->BuynowIcon),
      'option_easy_payment_icon_url' => strval($results->Result->Option->EasyPaymentIcon),
      'option_gift_icon_url' => strval($results->Result->Option->GiftIcon),
      'option_bundle_icon_url' => strval($results->Result->Option->BundleIcon),
      'option_item_status_new_icon_url' => strval($results->Result->Option->ItemStatusNewIcon),
      'option_y_bank_icon_url' => strval($results->Result->Option->YbankIcon),
      'option_english_icon_url' => strval($results->Result->Option->EnglishIcon),
      'option_star_club_icon_url' => strval($results->Result->Option->StarClubIcon),
      'option_charity_icon_url' => strval($results->Result->Option->CharityIcon),
    );

//var_dump($data);exit;

    $state = $this->getState(self::_INDEX);

    $this->barrier();

    if ($this->_session->get('counter')['bids']['reserve_place_bids'] >=
        $this->_session->get('resources_threads')) {

      $this->httpForbidden();
    }

    return $this->render(array(
      self::_INDEX => $data,
      'to_close' => false,
      'table_values' => $this->_connect_model
                             ->get($this->_controller)
                             ->getTableValues(),
      'view_path'    => $this->_view_path,
      'exchange_usd_jpy' => $this->getExchangeUSDJPY(),
      '_token'       => $this->getToken($this->_view_path . '/' . self::_POST),
    ));
  }

  public function listAction($params)
  {
    $bids     = array();
    $data     = array();
    $state    = $this->getState(self::_INDEX);
    $state_id = (int)$params['state_id'];
    $user     = $this->_session->get('user');

    $this->barrier();

    switch ($state_id) {
      case $state['reserve_place_bids']:
      case $state['bidding']:
      case $state['win']:
      case $state['end']:
        break;
      default:
        $this->httpNotFound();
    }

    $data = $this->_connect_model
                 ->get($this->_controller)
                 ->getsByStateId($user['id'], $state_id);

    $this->set();
    return $this->render(array(
      'state'          => $state,
      'state_id'       => $state_id,
      self::_INDEX.'s' => $data,
      'view_path'      => $this->_view_path,
      '_token'         => $this->getToken(
                            $this->_view_path.'/'.self::_POST
                          ),
    ));
  }
 
  public function getAction($params)
  {
    $data  = array();
    $research_watch_list = array();
    $state = $this->getState(self::_INDEX);
    $user  = $this->_session->get('user');

    $this->barrier();

    if (isset($params['id'])) {

      $data = $this->_connect_model
                   ->get($this->_controller)
                   ->get($params['id'], $user['id']);

      $research_watch_list = $this->_connect_model
                   ->get('ResearchWatchList')
                   ->get(
                       $data['research_watch_list_id'],
                       $user['id']
                     );

      if (!$data or !$research_watch_list) {

        $this->httpNotFound();
      }
    } else {

      $this->httpNotFound();
    }

    return $this->render(array(
      self::_INDEX => $data,
      'research_watch_list' => $research_watch_list,
      'to_close'   => false,
      'table_values' => $this->_connect_model
                             ->get($this->_controller)
                             ->getTableValues(),
      'view_path'   => $this->_view_path,
      '_token'      => $this->getToken($this->_view_path . '/' . self::_POST),
    ));
  }

  private function verify($type, $data)
  {
    $errors = array();

    if (self::TYPE_RESERVATION === $type) {

      if (!strlen($data['bids_price'])) {

        $errors[] = '価格 > 入札価格を入力してください';
      }

      if ((int)$data['price'] >= (int)$data['bids_price']) {

        $errors[] = '価格 > 入札価格は現在価格以上に設定してください';
      }

      if ($this->_connect_model
               ->get($this->_controller)
               ->exists(array(
                   'auction_id' => $data['auction_id']
         ))) {

        $errors[] = '入札予約は完了しています。';
      }
    }

    return $errors;
  }

  public function postAction()
  {

    $data         = array();
    $page         = null;
    $delete_by_id = null;

    $this->_user = $this->_session->get('user');

    if (!$this->_request->isPost()) {

      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken($this->_view_path . '/' . self::_POST, $token)) {
      return $this->redirect('/');
    }

    $delete_by_id = $this->_request->getPost('delete_by_id');
    $state_id     = $this->_request->getPost('state_id');
    $state        = $this->getState(self::_INDEX);

    if ($delete_by_id) {

      $this->_connect_model
           ->get($this->_controller)
           ->update(
               array(
                 'id' => key($delete_by_id),
                 'user_id' => $this->_user['id'],
                 'deleted' => 1,
               )
             );

      $data = $this->_connect_model
                   ->get($this->_controller)
                   ->getsByStateId($this->_user['id'], $state_id);

      $this->set();
      return $this->render(array(
        'state'          => $state,
        'state_id'       => $state_id,
        self::_INDEX.'s' => $data,
        'view_path'      => $this->_view_path,
        '_token'         => $this->getToken(
                              $this->_view_path.'/'.self::_POST
                            ),
      ), self::_LIST);
    }

    $type = $this->_request->getSubmitType();
    $data = $this->_connect_model
                 ->get($this->_controller)
                 ->desc();
    $data = $this->fillValue($data);

    $page = self::_GET;

    if ((int)$data['state_id'] === 0) {

      // state_id が 0 の場合のみ null にする
      $data['id'] = null;
      $page = self::_REGISTER;
    }

    $successes = array();
    $render = array(
      'errors'     => $this->verify($type, $data),
      'successes'  => $successes,
      self::_INDEX => $data,
      'to_close'   => false,
      'table_values' => $this->_connect_model
                             ->get($this->_controller)
                             ->getTableValues(),
      'exchange_usd_jpy' => $this->getExchangeUSDJPY(),
      'view_path'  => $this->_view_path,
      '_token'     => $this->getToken($this->_view_path . '/' . self::_POST),
    );

    return $this->commit($type,
                         $render,
                         self::_INDEX,
                         $this->_controller,
                         $page);
  }

}
