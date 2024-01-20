<?php
class SettingAccountController extends BasicController
{
  const _INDEX = 'account';
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
    return $this->render(
      array(
        self::_INDEX . 's' => $this->_connect_model
                                   ->relay('m', $this->_controller)
                                   ->gets($this->_session->get('user')['id']),
        'view_path' => $this->_view_path,
        '_token' => $this->getToken($this->_view_path . '/' . self::_POST),
      )
    );
  }

  public function getAction($params)
  {
    $data = null;
    $user = $this->_session->get('user');

    if ($params['id'] !== $user['id']) {

      $this->httpNotFound();
    }

    $data = $this->_connect_model
                 ->relay('m', $this->_controller)
                 ->get($user['id']);

    if (!$data) {

      $this->httpNotFound();
    }

    return $this->render(
      array(
        self::_INDEX  => $data,
        'view_path'   => $this->_view_path,
        'in_progress' => $this->inProgress($data),
        'prepare'     => $this->prepare($data),
        'admin_url'   => $this->getAdminUrl(),
        '_token'      => $this->getToken(
                           $this->_view_path.'/'.self::_POST
                         )
      )
    );
  }

  private function verify(&$data)
  {

    $errors = array();

    $password_current = null;
    $password_hash    = null;

    $password_current = $this->_connect_model
                             ->relay('m', $this->_controller)
                             ->getCurrentPassword($data['user_name']);

    if (!strlen($data['password'])) {

      $errors[] = 'パスワードが入力されていません';
      // $password_currentがfalse(値なし)か入力パスワードが変更されていたら
      // パスワードを作り直す
    } else if (!$password_current ||
                $password_current !== $data['password']) {

      $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);
      // 加えて、入力パスワードの文字列長をチェックする
      if (8 > strlen($data['password']) ||
          strlen($data['password']) > 30) {

        $errors[] = 'パスワードは8文字以上30字以内であることが必要です';
      }
    }

    // 入力不備がなければ、かつハッシュ化されたパスワードがnullでなければ、i
    // hash化したものへ置き換える
    if (count($errors) === 0 && isset($password_hash)) {

      $data['password'] = $password_hash;
    }

    if (strlen($data['vacation_begin_date']) > 0 or
        strlen($data['vacation_end_date']) > 0) {

      if (!(strlen($data['vacation_begin_date']) > 0 and
            strlen($data['vacation_end_date'])) > 0) {

        $errors[] = '[Market Link] > [バケーション設定] は開始～終了年月日を入れてください。';
      } else if (!preg_match(
                   "/^\d{4}-\d{2}-\d{2}$/",
                   $data['vacation_begin_date']
                 ) or
                 !preg_match(
                   "/^\d{4}-\d{2}-\d{2}$/",
                   $data['vacation_end_date']
                 )) {

        $errors[] = '[Market Link] > [バケーション設定] の開始～終了年月日はYYYY-MM-DD形式のみ入力可能です。';
      } else if (strtotime(
                   $data['vacation_begin_date']
                 ) > strtotime(
                       $data['vacation_end_date']
                     )) {

        $errors[] = '[Market Link] > [バケーション設定] の開始年月日が未来になっています。';
      }
    } else if (strlen($data['vacation_begin_date']) === 0 and
               strlen($data['vacation_end_date']) === 0) {

      $data['vacation_begin_date'] = null;
      $data['vacation_end_date'] = null;
    }

    if (strlen($data['chatwork_id']) > 0 and
        preg_match("/[\s　]/", $data['chatwork_id'])) {

      $errors[] = '[ChatWork] > [チャットワークID]に空白文字は利用できません';
    }

    if (strlen($data['yahoo_seller_account']) > 0 and
        preg_match("/[\s　]/", $data['yahoo_seller_account'])) {

      $errors[] = '[Yahoo!] > [販売用] > [Yahoo! JAPAN ID]に空白文字は利用できません';
    }

    if (strlen($data['yahoo_seller_password']) > 0 and
        preg_match("/[\s　]/", $data['yahoo_seller_password'])) {

      $errors[] = '[Yahoo!] > [販売用] > [パスワード]に空白文字は利用できません';
    }

    if (strlen($data['yahoo_buyer_account']) > 0 and
        preg_match("/[\s　]/", $data['yahoo_buyer_account'])) {

      $errors[] = '[Yahoo!] > [仕入用] > [Yahoo! JAPAN ID]に空白文字は利用できません';
    }

    if (strlen($data['yahoo_buyer_password']) > 0 and
        preg_match("/[\s　]/", $data['yahoo_buyer_password'])) {

      $errors[] = '[Yahoo!] > [仕入用] > [パスワード]に空白文字は利用できません';
    }

    if (strlen($data['amazon_jp_merchant_id']) > 0 and
        !preg_match("/^[0-9A-Z]{13,}$/", $data['amazon_jp_merchant_id'])) {

      $errors[] = '[Amazon] > [日本] > [出品者ID]のフォーマットが誤っています。';
    }

    if (strlen($data['amazon_jp_auth_token']) > 0 and
        !preg_match("/^amzn\.mws\.\w{8}\-\w{4}\-\w{4}\-\w{4}\-\w{12}$/", $data['amazon_jp_auth_token'])) {

      $errors[] = '[Amazon] > [日本] > [MWS認証トークン]のフォーマットが誤っています。';
    }

    return $errors;
  }

  public function postAction()
  {

    $data = array();
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
                 ->relay('m', $this->_controller)
                 ->desc();
    $data = $this->fillValue($data);


    if (preg_match("/^bulk_transplant$/", $type)) {

      return $this->renderWrapper($this->bulkTransplant($data));
    } elseif (preg_match("/^create_my_pattern$/", $type)) {

      return $this->renderWrapper($this->createMyPattern($data));
    }
    unset($data['user_id']);

    $successes = array();
    $render = array(
      'errors'      => $this->verify($data),
      'successes'   => $successes,
      self::_INDEX  => $data,
      'in_progress' => $this->inProgress($data),
      'prepare'     => $this->prepare($data),
      'admin_url'   => $this->getAdminUrl(),
      'view_path'   => $this->_view_path,
      '_token'      => $this->getToken(
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

  private function renderWrapper($results)
  {

    return $this->render(
      array(
        'successes'   => $results['successes'],
        'errors'      => $results['errors'],
        self::_INDEX  => $results['data'],
        'in_progress' => $this->inProgress($results['data']),
        'prepare'     => $this->prepare($results['data']),
        'admin_url'   => $this->getAdminUrl(),
        'view_path'   => $this->_view_path,
        '_token'      => $this->getToken(
                           $this->_view_path.'/'.self::_POST
                         ),
      ), self::_GET);
  }

  private function transplant($model, $account)
  {

    foreach ($this->_connect_model
                  ->relay('m', $model)
                  ->gets($account['id']) as $ix => $data) {

      if (!$this->_connect_model
               ->get($model)
               ->get($data['id'], $account['id'])
         ) {

        $this->_connect_model
             ->get($model)
             ->create($data);
      }
    }
  }

  private function bulkTransplant($data)
  {

    $errors  = array();
    $account = null;
    $account = $this->getAccount();

    try {

      $this->transplant('ResearchAnalysis', $account);
      $this->transplant('ResearchNewArrival', $account);
      $this->transplant('ResearchYahooAuctionsSearch', $account);
      $this->transplant('SettingItemAccessories', $account);
      $this->transplant('SettingItemCategory', $account);
      $this->transplant('SettingItemConditionEbayUs', $account);
      $this->transplant('SettingItemConditionYahooAuctions', $account);
      $this->transplant('SettingItemDescription', $account);
      $this->transplant('SettingItemGrade', $account);
      $this->transplant('SettingItemMaker', $account);
      $this->transplant('SettingItemTemplateAmazonJp', $account);
      $this->transplant('SettingItemTemplateEbayUs', $account);
      $this->transplant('SettingItemTemplateYahooAuctions', $account);

      $this->_connect_model
           ->relay('m', $this->_controller)
           ->update(
               array(
                 'id' => $this->_user['id'],
                 'transplant_complete' => 1,
               )
             );
      $data['transplant_complete'] = 1;
    } catch (Exception $e) {

      $errors[] = $e->getMessage();
    }

    return array(
      'data'      => $data,
      'errors'    => $errors,
      'successes' => array('初期設定データを移植しました'),
    );
  }

  private function createMyPattern($data)
  {

    $errors = array();
    $parameters = null;
    $parameters = $this->_connect_model
                       ->get('SystemItemMyPattern')
                       ->gets($this->_user['id']);

    if (is_null($parameters)) {

      return null;
    }

    try {

      foreach ($parameters as $key => $parameter) {

        $my_pattern = array(

          'id' => '',
          'user_id' => $this->_user['id'],
          'name' => $parameter['name'],
          'maker_id' => $this->getIdFromName(
            'SettingItemMaker',
            $parameter['maker']
          ),
          'category_id' => $this->getIdFromName(
            'SettingItemCategory',
            $parameter['category']
          ),
          'grade_id' => $this->getIdFromName(
            'SettingItemGrade',
            $parameter['grade']
          ),
          'description_id' => $this->getIdFromName(
            'SettingItemDescription',
            $parameter['description']
          ),
          'accessories_id' => $this->getIdFromName(
            'SettingItemAccessories',
            $parameter['accessories']
          ),
          'remarks_ja' => $parameter['remarks_ja'],
          'remarks_en' => $parameter['remarks_en'],
          'yahoo_auctions_condition_id' => $this->getIdFromName(
            'SettingItemConditionYahooAuctions',
            $parameter['yahoo_auctions_condition']
          ),
          'yahoo_auctions_template_id' => $this->getIdFromName(
            'SettingItemTemplateYahooAuctions',
            $parameter['yahoo_auctions_template']
          ),
          'ebay_us_condition_id' => $this->getIdFromName(
            'SettingItemConditionEbayUs',
            $parameter['ebay_us_condition']
          ),
          'ebay_us_template_id' => $this->getIdFromName(
            'SettingItemTemplateEbayUs',
            $parameter['ebay_us_template']
          ),
          'amazon_jp_template_id' => $this->getIdFromName(
            'SettingItemTemplateAmazonJp',
            $parameter['amazon_jp_template']
          ),
          'created_at' => $this->_datetime->format('Y-m-d H:i:s'),
          'modified_at' => $this->_datetime->format('Y-m-d H:i:s'),
        );

        $this->_connect_model
             ->get('SettingItemMyPattern')
             ->create($my_pattern);

      }
    } catch (Exception $e) {

      $errors[] = $e->getMessage();
    }

    try {

      $this->_connect_model
           ->relay('m', $this->_controller)
           ->update(
               array(
                 'id' => $this->_user['id'],
                 'my_pattern_created' => 1,
               )
             );
      $data['my_pattern_created'] = 1;
    } catch (Exception $e) {

      $errors[] = $e->getMessage();
    }

    return array(
      'data' => $data,
      'errors' => $errors,
      'successes' => array('デフォルトマイパターンを作成しました'),
    );
  }

  private function getIdFromName($model, $name)
  {

    return $this->_connect_model
                ->get($model)
                ->getIdByName(
                    $this->_user['id'],
                    $name
                  );
  }

  private function getAdminUrl()
  {

    return $this->_configure->current['configure'][
             'controller'
           ]['chatwork']['url']['tweyes_administrator'];
  }

  private function prepare($data)
  {

    return array(
             'yahoo_auctions' => array(
               'seller' => $this->prepareYahooAuctionsSeller($data),
               'buyer' => $this->prepareYahooAuctionsBuyer($data),
             ),
             'ebay_us' => $this->prepareEbayUs($data),
             'amazon_jp' => $this->prepareAmazonJp($data),
           );
  }


  private function prepareYahooAuctionsSeller($data)
  {

    $user = null;
    $user = $this->getAccount();

    if (strlen($user['yahoo_seller_account']) > 0 and
        strlen($user['yahoo_seller_password']) > 0 and
        strlen($user['yahooapis_seller_appid']) > 0 and
        strlen($user['yahooapis_seller_secret']) > 0 and
        $user['yahoo_auctions_seller_cookies_is_set'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function prepareYahooAuctionsBuyer($data)
  {

    $user = null;
    $user = $this->getAccount();

    if (strlen($user['yahoo_buyer_account']) > 0 and
        strlen($user['yahoo_buyer_password']) > 0 and
        strlen($user['yahooapis_buyer_appid']) > 0 and
        strlen($user['yahooapis_buyer_secret']) > 0 and
        $user['yahoo_auctions_buyer_cookies_is_set'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function prepareEbayUs($data)
  {

    $user = null;
    $user = $this->getAccount();

    if (strlen($user['ebay_us_auth_token']) > 0) {

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
        strlen($user['amazon_jp_auth_token']) > 0) {

      return true;
    } else {

      return false;
    }
  }

}
