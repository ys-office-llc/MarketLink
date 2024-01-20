<?php $this->setPageTitle('title', 'ユーザー情報') ?>

<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($errors) and count($errors) > 0): ?>

<?php print $this->render('errors', array('errors' => $errors)); ?>

<?php elseif (isset($successes) and count($successes) > 0): ?>

<?php print $this->render('successes', array('successes' => $successes)); ?>

<?php endif; ?>

<ol class="breadcrumb">

  <li>

    <a href="<?php print $this->potal().$base_url; ?>">

      ポータル

    </a>
  </li>
  <li>ユーザー設定</li>
  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $account['id']
              ?>"
       target="_self">ユーザー情報
    </a>

  </li>

</ol>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

  <?php if (isset($account['id'])): ?>

  <?php   if ((int)$account['transplant_complete'] === 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="bulk_transplant"
           value="初期設定データを移植する" />

  <?php   endif; ?>

  <?php   if ((int)$account['my_pattern_created'] === 0 and
              $prepare['yahoo_auctions']['seller']): ?>

    <input class="btn btn-primary"
           type="submit"
           name="create_my_pattern"
           value="デフォルトマイパターン作成" />

  <?php   endif; ?>

    <?php if (strlen($account['chatwork_room1_id']) > 0 and
              strlen($account['chatwork_room2_id']) > 0 and
              strlen($account['chatwork_room3_id']) > 0 and
              strlen($account['chatwork_room4_id']) > 0 and
              strlen($account['chatwork_room5_id']) > 0): ?>

    <?php   if (isset($account['request_ebay_us_auth_token']) and
                !$in_progress['ebay']): ?>

    <input class="btn btn-primary confirm"
           type="submit"

    <?php     if ($in_progress['yahoo_auctions']['seller'] or
                  $in_progress['yahoo_auctions']['buyer']): ?>

           disabled="disabled"

    <?php     endif; ?>

           name="request_ebay_us_auth_token"
           value="eBay認証トークン取得" />

    <?php   endif; ?>

    <?php   if ($account['merchandise_management'] === 'enable' and
                strlen($account['yahoo_seller_account']) > 0 and
                strlen($account['yahoo_seller_password']) > 0 and
                (int)$account['yahoo_auctions_seller_request_captcha'] === 0): ?>
    <input class="btn btn-primary confirm"
           type="submit"

    <?php     if ($in_progress['yahoo_auctions']['buyer'] or
                  $in_progress['ebay']): ?>

           disabled="disabled"

    <?php     endif; ?>
    
           name="yahoo_auctions_seller_request_captcha"
           value="ヤフオク販売用アカウント認証要求" />

    <?php   endif; ?>

    <?php   if ($account['market_screening'] === 'enable' and
                strlen($account['yahoo_buyer_account']) > 0 and
                strlen($account['yahoo_buyer_password']) > 0 and
                (int)$account['yahoo_auctions_buyer_request_captcha'] === 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"

    <?php     if ($in_progress['yahoo_auctions']['seller'] or
                  $in_progress['ebay']): ?>

           disabled="disabled"

    <?php     endif; ?>

           name="yahoo_auctions_buyer_request_captcha"
           value="ヤフオク仕入用アカウント認証要求" />

    <?php   endif; ?>

    <?php endif; ?>

    <?php if ((int)$account['chatwork_contact_with_admin'] > 0 and
              (int)$account['chatwork_create_rooms'] === 0 and
              strlen($account['chatwork_account_id']) > 0): ?>

    <?php   if (strlen($account['chatwork_room1_id']) === 0 and
                strlen($account['chatwork_room2_id']) === 0 and
                strlen($account['chatwork_room3_id']) === 0 and
                strlen($account['chatwork_room4_id']) === 0 and
                strlen($account['chatwork_room5_id']) === 0): ?>

  <input class="btn btn-primary confirm"
         type="submit"
         name="chatwork_create_rooms"
         value="ChatWorkルーム作成" />

    <?php   endif; ?>

    <?php endif; ?>

    <input class="btn btn-warning"
           type="submit"

  <?php   if ($in_progress['ebay'] or
              $account['chatwork_create_rooms'] > 0 or
              $in_progress['chatwork']['contact_with_admin'] or
              strlen($account['yahoo_auctions_seller_captcha']) > 0 or
              strlen($account['yahoo_auctions_buyer_captcha']) > 0): ?>

           disabled="disabled"

  <?php   endif; ?>

           name="update"
           value="変更" />

  <?php endif; ?>

  </div>

  <div class="table-responsive">

  <table class="table display table-bordered">
    <tbody>

      <?php

        if (true) {

          print(
            $this->render(
              'setting/account/market_link',
              array(
                'account' => $account,
              )
           )
          );

        }

      ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">ChatWork</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">チャットワークID</span>
                </th>

                <td class="active" style="width: 600px">

                  <input type="text"
                         name="chatwork_id"
                         value="<?php print $account['chatwork_id'] ?>"
                         size="16" />

                <?php if ((int)$account['chatwork_contact_with_admin'] === 0 and
                          strlen($account['chatwork_id']) > 0 and
                          !preg_match("/[\s　]/", $account['chatwork_id'])): ?>

                  <a class="btn btn-primary"
                     href="<?php print $admin_url ?>"
                     target="_blank">

                    管理者へリクエスト

                  </a>

                <?php endif; ?>

                </td>
              </tr>

            <tbody>
          </table>
        </td>
      </tr>

    <?php   if (strlen($account['chatwork_room1_id']) > 0 and
                strlen($account['chatwork_room2_id']) > 0 and
                strlen($account['chatwork_room3_id']) > 0 and
                strlen($account['chatwork_room4_id']) > 0 and
                strlen($account['chatwork_room5_id']) > 0): ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">

            <a class="btn btn-primary"
              href="https://login.yahoo.co.jp/config/login?.src=auc&.done=http%3A%2F%2Fauctions.yahoo.co.jp%2F"
              target="_blank">
              Yahoo!
            </a>

          </span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

            <?php if ($account['merchandise_management'] === 'enable'): ?>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">販売用</span>
                </th>

                <td class="active">
                  <table class="table table-bordered table-condensed">
                    <tbody>

                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">Yahoo! JAPAN ID</span>
                        </th>

                        <td class="active" style="width: 600px">
                          <input type="text"
                                 name="yahoo_seller_account"
                                 value="<?php print $account['yahoo_seller_account'] ?>"
                                 size="16" />
                        </td>
                      </tr>

                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">パスワード</span>
                        </th>

                        <td class="active" style="width: 500px">
                          <input type="password"
                                 name="yahoo_seller_password"
                                 value="<?php print $account['yahoo_seller_password'] ?>"
                                 size="32" />
                        </td>
                      </tr>

                    <?php if (strlen($account['yahoo_seller_account']) > 0 and
                              strlen($account['yahoo_seller_password']) > 0 and
                              $account['yahoo_auctions_seller_request_captcha'] > 0): ?>

                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">画像認証用文字列</span>
                        </th>

                        <td class="active" style="width: 500px">

                    <?php   if ($account['yahoo_auctions_seller_request_captcha'] > 0): ?>

                          <input type="text"
                                 name="yahoo_auctions_seller_captcha"
                                 value="<?php print $account['yahoo_auctions_seller_captcha'] ?>"
                                 size="32" />

                    <?php   endif; ?>

                        </td>
                      </tr>

                    <?php endif; ?>

                    <tbody>
                  </table>
                </td>
              </tr>

            <?php endif; ?>

            <?php if ($account['market_screening'] === 'enable'): ?>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">仕入用</span>
                </th>

                <td class="active">
                  <table class="table table-bordered table-condensed">
                    <tbody>

                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">Yahoo! JAPAN ID</span>
                        </th>

                        <td class="active" style="width: 600px">
                          <input type="text"
                                 name="yahoo_buyer_account"
                                 value="<?php print $account['yahoo_buyer_account'] ?>"
                                 size="16" />
                        </td>
                      </tr>

                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">パスワード</span>
                        </th>

                        <td class="active" style="width: 500px">
                          <input type="password"
                                 name="yahoo_buyer_password"
                                 value="<?php print $account['yahoo_buyer_password'] ?>"
                                 size="32" />
                        </td>
                      </tr>

                    <?php if (strlen($account['yahoo_buyer_account']) > 0 and
                              strlen($account['yahoo_buyer_password']) > 0 and
                              $account['yahoo_auctions_buyer_request_captcha'] > 0): ?>

                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">画像認証用文字列</span>
                        </th>

                        <td class="active" style="width: 500px">

                    <?php   if ($account['yahoo_auctions_buyer_request_captcha'] > 0): ?>

                          <input type="text"
                                 name="yahoo_auctions_buyer_captcha"
                                 value="<?php print $account['yahoo_auctions_buyer_captcha'] ?>"
                                 size="32" />

                    <?php   endif; ?>

                        </td>
                      </tr>

                    <?php endif; ?>

<tr>

  <th class="text-center info">

    <span class="text-primary">

      <p>除外アカウントリスト</p>

    </span>

  </th>

  <td class="active"
      style="width: <?php print($width) ?>px">

    <textarea name="yahoo_auctions_buyer_excluded_accounts"
              cols="72"
              rows="5"><?php
                print($account['yahoo_auctions_buyer_excluded_accounts'])
              ?></textarea>

  </td>

</tr>

                    <tbody>
                  </table>
                </td>
              </tr>

            <?php endif; ?>

            <tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">Amazon</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">日本</span>
                </th>

                <td class="active">
                  <table class="table table-bordered table-condensed">
                    <tbody>

                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">出品者ID</span>
                        </th>

                        <td class="active" style="width: 600px">
                          <input type="text"
                                 name="amazon_jp_merchant_id"
                                 value="<?php print $account['amazon_jp_merchant_id'] ?>"
                                 size="48" />
                        </td>
                      </tr>

                      <tr>
                        <th class="text-center info">
                          <span class="text-primary">MWS認証トークン</span>
                        </th>

                        <td class="active" style="width: 600px">
                          <input type="text"
                                 name="amazon_jp_auth_token"
                                 value="<?php print $account['amazon_jp_auth_token'] ?>"
                                 size="48" />
                        </td>
                      </tr>

                    <tbody>
                  </table>
                </td>
              </tr>

            <tbody>
          </table>

        </td>
      </tr>
    <?php else: ?>

      <input type="hidden" name="yahoo_seller_account" value="<?php print $account['yahoo_seller_account'] ?>" />
      <input type="hidden" name="yahoo_seller_password" value="<?php print $account['yahoo_seller_password'] ?>" />
      <input type="hidden" name="yahoo_buyer_account" value="<?php print $account['yahoo_buyer_account'] ?>" />
      <input type="hidden" name="yahoo_buyer_password" value="<?php print $account['yahoo_buyer_password'] ?>" />
      <input type="hidden" name="amazon_jp_account" value="<?php print $account['amazon_jp_account'] ?>" />
      <input type="hidden" name="amazon_jp_password" value="<?php print $account['amazon_jp_password'] ?>" />

    <?php endif; ?>

    <?php if ($this->getUserData()['account_authority_level_id']  > 1): ?>
      <tr>
        <th class="text-center info">
          <span class="text-primary">カメラのキタムラ</th></span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">アカウント</span>
                </th>

                <td class="active" style="width: 800px">
                  <input type="text"
                         name="kitamura_account"
                         value="<?php print $account['kitamura_account'] ?>"
                         size="32" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">パスワード</span>
                </th>

                <td class="active" style="width: 800px">
                  <input type="password"
                         name="kitamura_password"
                         value="<?php print $account['kitamura_password'] ?>"
                         size="16" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">店舗コード</span>
                </th>

                <td class="active" style="width: 800px">
                  <input type="text"
                         name="kitamura_shopcode"
                         value="<?php print $account['kitamura_shopcode'] ?>"
                         size="8" />
                </td>
              </tr>

            <tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">フジヤカメラ</th></span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">アカウント</span>
                </th>

                <td class="active" style="width: 800px">
                  <input type="text"
                         name="fujiya_account"
                         value="<?php print $account['fujiya_account'] ?>"
                         size="32" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">パスワード</span>
                </th>

                <td class="active" style="width: 800px">
                  <input type="password"
                         name="fujiya_password"
                         value="<?php print $account['fujiya_password'] ?>"
                         size="16" />
                </td>
              </tr>

            <tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">マップカメラ</th></span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">アカウント</span>
                </th>

                <td class="active" style="width: 800px">
                  <input type="text"
                         name="fujiya_account"
                         value="<?php print $account['fujiya_account'] ?>"
                         size="32" />
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">パスワード</span>
                </th>

                <td class="active" style="width: 800px">
                  <input type="password"
                         name="fujiya_password"
                         value="<?php print $account['fujiya_password'] ?>"
                         size="16" />
                </td>
              </tr>

            <tbody>
          </table>
        </td>
      </tr>
    <?php endif; ?>

    <?php

      print $this->render(
        'setting/account/prepare',
        array(
          'account' => $account,
          'prepare' => $prepare,
        )
      )

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'footer/management_information',
            array(
              'information' => $account,
            )
          )
        );

      }

    ?>

    </tbody>
  </table>

  </div>

  <input type="hidden" name="operation_mode" value="<?php print $account['operation_mode'] ?>" />
  <input type="hidden" name="use_experimental_function" value="<?php print $account['use_experimental_function'] ?>" />
  <input type="hidden" name="market_screening" value="<?php print $account['market_screening'] ?>" />
  <input type="hidden" name="merchandise_management" value="<?php print $account['merchandise_management'] ?>" />
  <input type="hidden" name="account_contract_id" value="<?php print $account['account_contract_id'] ?>" />
  <input type="hidden" name="account_authority_level_id" value="<?php print $account['account_authority_level_id'] ?>" />
  <input type="hidden" name="account_is_payment_id" value="<?php print $account['account_is_payment_id'] ?>" />
  <input type="hidden" name="accommodated_host_id" value="<?php print $account['accommodated_host_id'] ?>" />
  <input type="hidden" name="migration_contract_id" value="<?php print $account['migration_contract_id'] ?>" />
  <input type="hidden" name="migration_plans_datetime" value="<?php print $account['migration_plans_datetime'] ?>" />
  <input type="hidden" name="migration_packages_datetime" value="<?php print $account['migration_packages_datetime'] ?>" />
  <input type="hidden" name="transplant_complete" value="<?php print $account['transplant_complete'] ?>" />
  <input type="hidden" name="yahoo_auctions_seller_cookies_is_set" value="<?php print $account['yahoo_auctions_seller_cookies_is_set'] ?>" />
  <input type="hidden" name="yahoo_auctions_seller_cookies_set_datetime" value="<?php print $account['yahoo_auctions_seller_cookies_set_datetime'] ?>" />
  <input type="hidden" name="yahoo_auctions_buyer_cookies_is_set" value="<?php print $account['yahoo_auctions_buyer_cookies_is_set'] ?>" />
  <input type="hidden" name="yahoo_auctions_buyer_cookies_set_datetime" value="<?php print $account['yahoo_auctions_buyer_cookies_set_datetime'] ?>" />
  <input type="hidden" name="yahoo_auctions_seller_cookies_is_set" value="<?php print $account['yahoo_auctions_seller_cookies_is_set'] ?>" />
  <input type="hidden" name="yahoo_auctions_buyer_cookies_is_set" value="<?php print $account['yahoo_auctions_buyer_cookies_is_set'] ?>" />
  <input type="hidden" name="yahoo_auctions_seller_request_captcha" value="<?php print $account['yahoo_auctions_seller_request_captcha'] ?>" />
  <input type="hidden" name="yahoo_auctions_buyer_request_captcha" value="<?php print $account['yahoo_auctions_buyer_request_captcha'] ?>" />
  <input type="hidden" name="yahooapis_seller_appid" value="<?php print $account['yahooapis_seller_appid'] ?>" />
  <input type="hidden" name="yahooapis_seller_secret" value="<?php print $account['yahooapis_seller_secret'] ?>" />
  <input type="hidden" name="yahooapis_buyer_appid" value="<?php print $account['yahooapis_buyer_appid'] ?>" />
  <input type="hidden" name="yahooapis_buyer_secret" value="<?php print $account['yahooapis_buyer_secret'] ?>" />
  <input type="hidden" name="ebay_us_auth_token" value="<?php print $account['ebay_us_auth_token'] ?>" />
  <input type="hidden" name="user_name" value="<?php print $account['user_name'] ?>" />
  <input type="hidden" name="user_name_ja" value="<?php print $account['user_name_ja'] ?>" />
  <input type="hidden" name="accommodated_host_id" value="<?php print $account['accommodated_host_id'] ?>" />
  <input type="hidden" name="line_messaging_api_access_token" value="<?php print $account['line_messaging_api_access_token'] ?>" />
  <input type="hidden" name="chatwork_request_was_made" value="<?php print $account['chatwork_request_was_made'] ?>" />
  <input type="hidden" name="chatwork_account_id" value="<?php print $account['chatwork_account_id'] ?>" />
  <input type="hidden" name="chatwork_contact_with_admin" value="<?php print $account['chatwork_contact_with_admin'] ?>" />
  <input type="hidden" name="chatwork_create_rooms" value="<?php print $account['chatwork_create_rooms'] ?>" />
  <input type="hidden" name="chatwork_delete_rooms" value="<?php print $account['chatwork_delete_rooms'] ?>" />
  <input type="hidden" name="chatwork_api_admin_token" value="<?php print $account['chatwork_api_admin_token'] ?>" />
  <input type="hidden" name="chatwork_api_tokens" value="<?php print $account['chatwork_api_tokens'] ?>" />
  <input type="hidden" name="chatwork_members_admin_ids" value="<?php print $account['chatwork_members_admin_ids'] ?>" />
  <input type="hidden" name="chatwork_members_member_ids" value="<?php print $account['chatwork_members_member_ids'] ?>" />
  <input type="hidden" name="chatwork_members_readonly_ids" value="<?php print $account['chatwork_members_readonly_ids'] ?>" />
  <input type="hidden" name="chatwork_members_readonly_names" value="<?php print $account['chatwork_members_readonly_names'] ?>" />
  <input type="hidden" name="chatwork_room1_id" value="<?php print $account['chatwork_room1_id'] ?>" />
  <input type="hidden" name="chatwork_room2_id" value="<?php print $account['chatwork_room2_id'] ?>" />
  <input type="hidden" name="chatwork_room3_id" value="<?php print $account['chatwork_room3_id'] ?>" />
  <input type="hidden" name="chatwork_room4_id" value="<?php print $account['chatwork_room4_id'] ?>" />
  <input type="hidden" name="chatwork_room5_id" value="<?php print $account['chatwork_room5_id'] ?>" />
  <input type="hidden" name="chatwork_room6_id" value="<?php print $account['chatwork_room6_id'] ?>" />
  <input type="hidden" name="chatwork_work_place_members" value="<?php print $account['chatwork_work_place_members'] ?>" />
  <input type="hidden" name="chatwork_work_place_room1_id" value="<?php print $account['chatwork_work_place_room1_id'] ?>" />
  <input type="hidden" name="amazon_jp_marketplace_id" value="<?php print $account['amazon_jp_marketplace_id'] ?>" />
  <input type="hidden" name="amazon_jp_access_key" value="<?php print $account['amazon_jp_access_key'] ?>" />
  <input type="hidden" name="amazon_jp_secret_key" value="<?php print $account['amazon_jp_secret_key'] ?>" />
  <input type="hidden" name="my_pattern_created" value="<?php print $account['my_pattern_created'] ?>" />
  <input type="hidden" name="created_at" value="<?php print $account['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $account['modified_at'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
