<?php
class AccountController extends BasicController
{
  protected $_authentication = array('signout');
  const SIGNIN = 'account/signin';

  const _LIST  = 'list';
  const _GET   = 'get';
  const _POST  = 'post';
  const _MODEL = 'Account';
  const _INDEX = 'account';

  private function vefiryMailAddress($mailaddress)
  {
    $pattern = '/@([\w.-]++)\z/';

    return filter_var($mailaddress, FILTER_VALIDATE_EMAIL) &&
      preg_match($pattern, $mailaddress, $matches) &&
      (checkdnsrr($matches[1], 'MX') ||
       checkdnsrr($matches[1], 'A')  ||
       checkdnsrr($matches[1], 'AAAA')
    );
  }

  public function listAction()
  {

    $accounts = array();

    foreach ($this->_connect_model
                  ->relay('m', 'Account')
                  ->gets() as $index => $values) {

      $values['prepare'] = $this->prepare($values);
      $accounts[]        = $values;
    }

    return $this->render(
      array(
        'accounts'  => $accounts,
        'view_path' => $this->_view_path,
        'administrator_hosts' => $this->getAdministratorHosts(),
        '_token'    => $this->getToken(self::_POST)
      ), self::_LIST);
  }

  public function resetAction($params)
  {

    $results = array(
      'successes' => array(),
      'errors'    => array(),
    );

    if ($this->_request->isPost()) {

      $one_time_token = null;

      $user_name = $this->_request->getPost('user_name');
      $password = $this->_request->getPost('password');
      $one_time_token = $this->_request->getPost('one_time_token');

      if (is_null($one_time_token)) {

        $token = $this->_request->getPost('_token');
        if (!$this->checkToken('resend', $token)) {

          return $this->redirect('/');
        }

        $exists = $this->_connect_model
                       ->relay('m', self::_MODEL)
                       ->exists(
                           array(
                             'user_name' => $user_name
                           )
                         );
        $user = $this->_connect_model
                     ->relay('m', self::_MODEL)
                     ->getUserRecord($user_name);

        if (strlen($user_name) === 0) {
        
          $results['errors'][] = 'ユーザー名が入力されていません。';
        } else if (
          !$exists or
           $this->_request->getHostName() !== $this->getAdministratorHosts()[
                                                $user['accommodated_host_id']
                                              ]['name']) {

          $results['errors'][] = sprintf("%sは登録されていません", $user_name);
        } else {

          $one_time_token = $this->generateToken();

          $this->_connect_model
               ->relay('m', $this->_controller)
               ->update(
                   array(
                     'id' => $user['id'],
                     'one_time_token' => $one_time_token,
                     'one_time_token_created_at' => $this->_datetime
                                                         ->format(
                                                             'Y-m-d H:i:s'
                                                           ),
                   )
                 );
             
          $results = $this->sendMail($user_name, $one_time_token);
        }

        return $this->render(
          array(
            'errors'    => $results['errors'],
            'successes' => $results['successes'],
            'user_name' => null,
            'view_path' => $this->_view_path,
            '_token'    => $this->getToken('resend')
          ), 'resend');
      } else {

        if (strlen($password) === 0) {
        
          $results['errors'][] = 'パスワードが入力されていません。';
          // 加えて、入力パスワードの文字列長をチェックする
        } else if (8 > strlen($password) || strlen($password) > 128) {

          $results['errors'][] = 'パスワードは8文字以上128字以内であることが必要です';
        } else {

          $password_hash = password_hash($password, PASSWORD_DEFAULT);

          $user = $this->_connect_model
                       ->relay('m', self::_MODEL)
                       ->getUserRecord($user_name);

          $this->_connect_model
               ->relay('m', $this->_controller)
               ->update(
                   array(
                     'id' => $user['id'],
                     'password' => $password_hash,
                   )
                 );

          $results['successes'][] = 'パスワードを変更しました';
        }

        return $this->render(
          array(
            'errors'    => $results['errors'],
            'successes' => $results['successes'],
            'user_name' => $user_name,
            'password'  => $password,
            'one_time_token' => $one_time_token,
            'token_is' => true,
            'view_path' => $this->_view_path,
            '_token'    => $this->getToken('commit')
          ), 'commit');
      }
    }

    if ($params['operation'] === 'resend') {

      return $this->render(
        array(
          'errors'    => $results['errors'],
          'successes' => $results['successes'],
          'user_name' => null,
          'view_path' => $this->_view_path,
          '_token'      => $this->getToken('resend'),
        ), 'resend');
    } else if ($params['operation'] === 'commit') {

      $token_is = true;

      if (!$this->_connect_model
               ->relay('m', self::_MODEL)
               ->verifyToken(
                   $params['user_name'],
                   $params['one_time_token']
                 )) {

        $results['errors'][] = "無効なリンクです。";
        $token_is = false;
      }

      return $this->render(
        array(
          'errors'    => $results['errors'],
          'successes' => $results['successes'],
          'token_is'  => $token_is,
          'user_name' => $params['user_name'],
          'password'  => null,
          'one_time_token' => $params['one_time_token'],
          'view_path' => $this->_view_path,
          '_token'      => $this->getToken('resend'),
        ), 'commit');
    } else {

      $this->httpNotFound();
    }
  }

  public function getAction($params)
  {

    $account = null;

    if (isset($params['id'])) {

      $account = $this->_connect_model
                      ->relay('m', self::_MODEL)
                      ->get($params['id']);

      if (!$account) {

        $this->httpNotFound();
      }

      // 特権アカウントのみアカウント修正をできる
      if ((int)$this->_session
                    ->get('user')['account_authority_level_id'] <= 1) {

        $this->httpForbidden();
      }
    }

    return $this->render(
      array(
        self::_INDEX   => $account,
        'table_values' => $this->_connect_model
                               ->relay('m', self::_MODEL)
                               ->getTableValues(),
        'administrator_hosts' => $this->getAdministratorHosts(),
        'view_path'    => $this->_view_path,
        '_token'       => $this->getToken(self::_POST)
      ), self::_GET);
  }
		
  public function postAction()
  {
    $account          = array();
    $password_current = null;
    $password_hash    = null;

    if (!$this->_request->isPost()) {
      $this->httpNotFound();
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken(self::_POST, $token)) {
      return $this->redirect('/');
    }

    $type = $this->_request->getSubmitType();
    $account = $this->_connect_model
                    ->relay('m', self::_MODEL)
                    ->desc();
    $account = $this->fillValue($account);
    unset($account['user_id']);

    $errors    = array();
    $successes = array();

    switch ($type) {
      case self::TYPE_CREATE:
      case self::TYPE_UPDATE:
        if ($account['account_contract_id'] < 1) {

          $errors[] = '契約タイプが選択されていません';
        }

        if ($account['accommodated_host_id'] < 1) {

          $errors[] = '収容ホストが選択されていません';
        }

        if (!strlen($account['user_name'])) {

          $errors[] = 'ユーザー名が入力されていません';
        } else if (!$this->vefiryMailAddress($account['user_name'])) {

          $errors[] = 'ユーザー名に指定してあるメールアドレスは無効です';
          // 新規登録(create)のみユーザー存在チェック
        } else if ($type === self::TYPE_CREATE and
                   $this->_connect_model
                        ->relay('m', self::_MODEL)
                        ->exists(array('user_name' => $account['user_name']))) {

          $errors[] = '入力したユーザー名は他のユーザーが使用しています';
        }

        if (!strlen($account['user_name_ja'])) {

          $errors[] = '表示名が入力されていません';
        }

        $password_current = $this->_connect_model
                                 ->relay('m', self::_MODEL)
                                 ->getCurrentPassword($account['user_name']);

        if (!strlen($account['password'])) {
          $errors[] = 'パスワードが入力されていません';
          // $password_currentがfalse(値なし)か入力パスワードが変更されていたら
          // パスワードを作り直す
        } else if (!$password_current ||
                    $password_current !== $account['password']) {
          $password_hash = password_hash($account['password'], PASSWORD_DEFAULT);
          // 加えて、入力パスワードの文字列長をチェックする
          if (8 > strlen($account['password']) ||
              strlen($account['password']) > 128) {
            $errors[] = 'パスワードは8文字以上128字以内であることが必要です';
          }
        }

      if ((int)$this->_session
                    ->get('user')['account_authority_level_id'] === 1) {

        if (!strlen($account['postcode'])) {
          $errors[] = '郵便番号が入力されていません';
        }

        if (!strlen($account['address'])) {
          $errors[] = '住所が入力されていません';
        }

        if (!strlen($account['chatwork_api_tok'])) {
          $errors[] = 'ChatWork APIトークンが入力されていません';
        }

        if (!strlen($account['chatwork_user_id'])) {
          $errors[] = 'ChatWorkユーザー番号が入力されていません';
        }

        if (!strlen($account['chatwork_room1_id'])) {
          $errors[] = 'ChatWorkルーム番号[1]が入力されていません';
        }

        if (!strlen($account['chatwork_room2_id'])) {
          $errors[] = 'ChatWorkルーム番号[2]が入力されていません';
        }

        if (!strlen($account['chatwork_room3_id'])) {
          $errors[] = 'ChatWorkルーム番号[3]が入力されていません';
        }

        if (!strlen($account['chatwork_room4_id'])) {
          $errors[] = 'ChatWorkルーム番号[4]が入力されていません';
        }

        if (!strlen($account['yahoo_seller_account'])) {
          $errors[] = 'Yahoo!アカウントが入力されていません';
        }

        if (!strlen($account['yahoo_seller_password'])) {
          $errors[] = 'Yahoo!パスワードが入力されていません';
        }

        if (!strlen($account['yahooapis_seller_appid'])) {
          $errors[] = 'Yahoo!アプリケーションキーが入力されていません';
        }

        if (!strlen($account['yahooapis_seller_secret'])) {
          $errors[] = 'Yahoo!アプリケーションシークレットが入力されていません';
        }

        if (!strlen($account['yahoo_buyer_account'])) {
          $errors[] = 'Yahoo!バイヤーアカウントが入力されていません';
        }

        if (!strlen($account['yahoo_buyer_password'])) {
          $errors[] = 'Yahoo!バイヤーパスワードが入力されていません';
        }

        if (!strlen($account['yahooapis_buyer_appid'])) {
          $errors[] = 'Yahoo!バイヤーアプリケーションキーが入力されていません';
        }

        if (!strlen($account['yahooapis_buyer_secret'])) {
          $errors[] = 'Yahoo!バイヤーアプリケーションシークレットが入力されていません';
        }

        if (!strlen($account['ebay_us_auth_token'])) {
          $errors[] = 'eBay US認証トークンが入力されていません';
        }

        if (!strlen($account['amazon_jp_marketplace_id'])) {
          $errors[] = 'Amazon.co.jpマーケットプレイスが入力されていません';
        }

        if (!strlen($account['amazon_jp_merchant_id'])) {
          $errors[] = 'Amazon.co.jpマーチャントIDが入力されていません';
        }

        if (!strlen($account['amazon_jp_access_key'])) {
          $errors[] = 'Amazon.co.jpアクセスキーが入力されていません';
        }

        if (!strlen($account['amazon_jp_secret_key'])) {
          $errors[] = 'Amazon.co.jpシークレットが入力されていません';
        }
}

        // 入力不備がなければ、かつハッシュ化されたパスワードがnullでなければ、i
        // hash化したものへ置き換える
        if (count($errors) === 0 && isset($password_hash)) {
          $account['password'] = $password_hash;
        }
    } // switch($type)

    $render = array(
      'errors'       => $errors,
      'successes'    => $successes,
      self::_INDEX   => $account,
      'table_values' => $this->_connect_model
                             ->relay('m', self::_MODEL)
                             ->getTableValues(),
      'administrator_hosts' => $this->getAdministratorHosts(),
      'view_path'    => $this->_view_path,
      '_token'       => $this->getToken(self::_POST),
    );

    return $this->commit($type,
                         $render,
                         self::_INDEX,
                         self::_MODEL,
                         self::_GET);
  }

  public function signinAction()
  {
    if ($this->_session->isAuthenticated()) {

      return $this->redirect('/');
    }

    return $this->render(
      array(
        'user_name' => '',
        'password'  => '',
        '_token'    => $this->getToken(self::SIGNIN),
    ));
  }
 
  public function authenticateAction()
  {

    if (!$this->_request->isPost()) {

      $this->httpNotFound();
    }
    
    if ($this->_session->isAuthenticated()) {

      return $this->redirect('/');
    }

    $token = $this->_request->getPost('_token');
    if (!$this->checkToken(self::SIGNIN, $token)) {

      return $this->redirect('/' . self::SIGNIN);
    }

    $user_name = $this->_request->getPost('user_name');
    $password  = $this->_request->getPost('password');

    $errors = array();
    if (!strlen($user_name)) {

      $errors[] = 'ユーザー名を入力してください';
    }

    if (!strlen($password)) {

      $errors[] = 'パスワードを入力してください';
    }

    if (count($errors) === 0) {

      $account = $this->_connect_model
                      ->relay('m', self::_MODEL)
                      ->getUserRecord($user_name);

      if (!$account or
          $this->_request->getHostName() !== $this->getAdministratorHosts()[
                                               $account[
                                                 'accommodated_host_id'
                                               ]
                                             ]['name']) {

        $errors[] = 'ユーザーは登録されていません';
      } else if ((!password_verify($password, $account['password']))) {

        $errors[] = 'パスワードが間違っています';
      } else if ((int)$account['account_is_payment_id'] === 0) {

        $errors[] = '課金されていません';
      } else {

        $this->_session->setAuthenticateStaus(true);
        $account['prepare'] = $this->prepare($account);
        $this->_session->set('user', $account);

        return $this->redirect('/');
      }
    }

    return $this->render(array(
      'user_name' => $user_name,
      'password'  => $password,
      'errors'    => $errors,
      '_token'    => $this->getToken(self::SIGNIN),
    ), 'signin');

  }

  public function signoutAction()
  {
    $this->_session->clear();
    $this->_session->setAuthenticateStaus(false);

    return $this->redirect('/' . self::SIGNIN);
  }

  private function prepare($data)
  {

    return array(
             'yahoo_auctions' => array(
               'seller' => $this->prepareYahooAuctionsSeller($data),
               'buyer' => $this->prepareYahooAuctionsBuyer($data),
             ),
             'ebay' => $this->prepareEbayUs($data),
             'amazon' => array(
               'jp' => $this->prepareAmazonJp($data),
             ),
           );
  }

  private function prepareYahooAuctionsSeller($data)
  {

    if (strlen($data['yahoo_seller_account']) > 0 and
        strlen($data['yahoo_seller_password']) > 0 and
        strlen($data['yahooapis_seller_appid']) > 0 and
        strlen($data['yahooapis_seller_secret']) > 0 and
        $data['yahoo_auctions_seller_cookies_is_set'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function prepareYahooAuctionsBuyer($data)
  {

    if (strlen($data['yahoo_buyer_account']) > 0 and
        strlen($data['yahoo_buyer_password']) > 0 and
        strlen($data['yahooapis_buyer_appid']) > 0 and
        strlen($data['yahooapis_buyer_secret']) > 0 and
        $data['yahoo_auctions_buyer_cookies_is_set'] > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function prepareEbayUs($data)
  {

    if (strlen($data['ebay_us_auth_token']) > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function prepareAmazonJp($data)
  {

    if (strlen($data['amazon_jp_marketplace_id']) > 0 and
        strlen($data['amazon_jp_merchant_id']) > 0 and
        strlen($data['amazon_jp_access_key']) > 0 and
        strlen($data['amazon_jp_secret_key']) > 0 and
        strlen($data['amazon_jp_auth_token']) > 0) {

      return true;
    } else {

      return false;
    }
  }

  private function getAdministratorHosts()
  {

    $administrator_hosts = null;

    $administrator_hosts = $this->_connect_model
                                ->relay('m', 'AdministratorHost')
                                ->gets();
    array_unshift(
      $administrator_hosts,
      array(
        'name' => '----'
      )
    );

    return $administrator_hosts;
  }

  private function sendMail($user_name, $one_time_token)
  {

    require_once('Mail.php');

    $results = array(
      'successes' => array(),
      'errors'    => array(),
    );

    $uri = sprintf(
             "https://%s/%s/reset/commit/%s/%s",
             $this->_request->getHostName(),
             $this->_view_path,
             $user_name,
             $one_time_token
           );
             

    define('MAIL_CHARSET', 'ISO-2022-JP');

    $parameters = array(
      'host' => 'mail.ys-office.me',
      'port' => 587,
      'auth' => true,
      'username' => 'info@ys-office.me',
      'password' => 'iq730811',
      'timeout' => 20,
    );

    $to = array($user_name);
    $from = mb_encode_mimeheader('info@ys-office.me', MAIL_CHARSET);
    mb_language('uni');
    mb_internal_encoding('UTF-8');
    $subject = '【マーケットリンク】パスワード再設定';

    $body = <<<BODY

マーケットリンク管理者です。

以下のリンクをクリックしてパスワードを再設定してください。
（有効期限は60分です）

{$uri}

BODY;

    $body = mb_convert_encoding($body, MAIL_CHARSET, 'UTF-8');

    $header = array(
      'From' => $from,
      'To' => implode(',', $to),
      'Subject' => $subject,
      'Content-Type' => 'text/plain; charset=' . MAIL_CHARSET,
    );

    $mail =& Mail::factory('smtp', $parameters);
    $recipients = $to;
    $result = $mail->send($recipients, $header, $body);

    if (PEAR::isError($result)) {

      $results['errors'][] = sprintf(
                               "メール送信に失敗しました。（%s, %s）",
                               $result->getCode(),
                               $result->getMessage()
                             );
    } else {

      $results['successes'][] = sprintf("メールを送信しました。");
    }

    return $results;
  }

}
