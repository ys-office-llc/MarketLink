<?php $this->setPageTitle('title', $bids['title']) ?>
<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($errors) and count($errors) > 0): ?>
<?php print $this->render('errors', array('errors' => $errors)); ?>
<?php endif; ?>
<?php if (isset($successes) and count($successes) > 0): ?>
<?php print $this->render('successes', array('successes' => $successes)); ?>
<?php endif; ?>

<form action="<?php print $base_url; ?>/bids/post"
      class="repeater"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

  <?php   if ($to_close): ?>

    <a href="javascript:window.open('about:blank','_self').close();"
       class="btn btn-success">
      ページを閉じる
    </a>

  <?php   else: ?>

    <input class="btn btn-success"
           type="submit"
           name="reservation"

    <?php if ((int)$this->getUserData()['account_contract_id'] < 2): ?>

           disabled="disabled"
           value="入札予約（スタンダードプラン以上で利用可能）"

    <?php elseif ($this->getCounterData()['bids'][
                    'reserve_place_bids'
                  ] > $this->getThreads()): ?>

           disabled="disabled"
           value="入札予約（予約上限を超えています）"

    <?php else: ?>

           value="入札予約"

    <?php endif; ?>

    />

  <?php   endif; ?>

  </div>

  <div class="table-responsive">
  <table class="table display table-bordered table-condensed">

    <tbody>

    <?php if ($bids['state_id'] > 0): ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">状態</span>
        </th>

        <td class="active" style="width: 800px">
          <div class="progress">
            <div class="progress-bar"
                 aria-valuenow="50"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 style="width:<?php print $bids['state_id'] * 50 ?>%">
              <?php print $table_values['bids_state'][$bids['state_id']]['name'] ?>
            </div>
          </div>
        </td>
      </tr>

    <?php endif; ?>

      <tr>

        <th class="text-center info">

          <span class="text-primary">タイトル</span>

        </th>

        <td class="active">
          <a href="<?php print $bids['auction_item_url'] ?>"
             target="_blank">

          <?php print $bids['title'] ?>

          </a>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">画像</span>
        </th>

        <td class="text-left active" style="width: 800px">
          <?php print $this->render('bids/modal',
                               array(
                                 'bids' => $bids
                               )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">出品者</span>
        </th>

        <td class="text-left active" style="width: 800px">

          <a href="http://sellinglist.auctions.yahoo.co.jp/user/<?php print $bids['seller_id'] ?>"
             target="_blank">

            <?php print $bids['seller_id'] ?>

          </a>
          （評価：<?php print $bids['rating_point'] ?> 良い：<?php print $bids['rating_total_good_rating'] ?> 悪い：<a href="http://auctions.yahoo.co.jp/jp/show/rating?userID=<?php print $bids['seller_id'] ?>&filter=-1#comment_list" target="_blank"><?php print $bids['rating_total_bad_rating'] ?>）
          </a>

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">価格</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>

                <th class="text-center info">
                  <span class="text-primary">開始</span>
                </th>

                <td class="active" style="width: 650px">
                  <?php print number_format(
                          $bids['initprice']
                        ) ?>円
                </td>

              </tr>

              <tr>

                <th class="text-center info">
                  <span class="text-primary">現在</span>
                </th>

                <td class="active" style="width: 650px">
                  <?php print number_format(
                          $bids['price']
                        ) ?>円
                </td>

              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">入札</span>
                </th>

                <td class="active" style="width: 650px">
                  <input type="text"
                         id="bids_price"
                         name="bids_price"
                         value="<?php print $bids['bids_price'] ?>"
                         size="8"/>円
                </td>
              </tr>

              <tr>

                <th class="text-center info">
                  <span class="text-primary">即決</span>
                </th>

                <td class="active" style="width: 650px">
                  <?php print number_format(
                          $bids['bidorbuy']
                        ) ?>円
                </td>

              </tr>

            </tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">入札上限価格計算表</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">獲得利益率</span>
                </th>

                <td class="active" style="width: 650px">
                  <input type="text"
                         id="earned_margin"
                         name="earned_margin"
                         value="15"
                         size="4"/>%
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">為替レート</span>
                </th>

                <td class="active"
                    style="width: 650px">

                  <input type="text"
                         id="exchange_usd_jpy"
                         name="exchange_usd_jpy"
                         value="<?php print $exchange_usd_jpy ?>"
                         size="8"/>米ドル／円

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">ヤフオク</span>
                </th>

                <td class="active" style="width: 650px">

                  <table class="table table-bordered table-condensed">
        
                    <thead>
        
                      <tr>

                        <th class="text-center info">
                          <span class="text-primary">相場</span>
                        </th>

                        <th class="text-center info">
                          <span class="text-primary">販売手数料</span>
                        </th>

                        <th class="text-center info">
                          <span class="text-primary">入札上限価格</span>
                        </th>

                      </tr>
        
                    </thead>
        
                    <tbody>

                      <tr>
                        <td class="text-center active" style="width: 450px">

                  <input type="text"
                         id="yahoo_auctions_market_price"
                         name="yahoo_auctions_market_price"
                         value=""
                         size="8"/>円

                        </td>

                        <td class="text-center active" style="width: 450px">

                          <span id="yahoo_auctions_commission_rate">0.91</span>%

                        </td>

                        <td class="text-center active"
                            style="width: 450px">

                          <span id="yahoo_auctions_bid_maximum_price">0</span>円

                        </td>
                      </tr>
        
                    </tbody>
                  </table>

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">eBay</span>
                </th>

                <td class="active" style="width: 650px">

                  <table class="table table-bordered table-condensed">

                    <thead>

                      <tr>

                        <th class="text-center info">
                          <span class="text-primary">相場</span>
                        </th>

                        <th class="text-center info">
                          <span class="text-primary">販売手数料</span>
                        </th>

                        <th class="text-center info">
                          <span class="text-primary">入札上限価格</span>
                        </th>

                      </tr>

                    </thead>

                    <tbody>

                      <tr>
                        <td class="text-center active" style="width: 450px">

                  <input type="text"
                         id="ebay_us_market_price"
                         name="ebay_us_market_price"
                         value=""
                         size="8"/>ドル

                        </td>

                        <td class="text-center active" style="width: 450px">

                          <span id="ebay_us_commission_rate">0.877</span>%

                        </td>

                        <td class="text-center active"
                            style="width: 450px">

                          <span id="ebay_us_bid_maximum_price">0</span>円

                        </td>
                      </tr>

                    </tbody>
                  </table>

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">Amazon.co.jp</span>
                </th>

                <td class="active" style="width: 650px">

                  <table class="table table-bordered table-condensed">

                    <thead>

                      <tr>

                        <th class="text-center info">
                          <span class="text-primary">相場</span>
                        </th>

                        <th class="text-center info">
                          <span class="text-primary">販売手数料</span>
                        </th>

                        <th class="text-center info">
                          <span class="text-primary">入札上限価格</span>
                        </th>

                      </tr>

                    </thead>

                    <tbody>

                      <tr>
                        <td class="text-center active" style="width: 450px">

                  <input type="text"
                         id="amazon_jp_market_price"
                         name="amazon_jp_market_price"
                         value=""
                         size="8"/>円

                        </td>

                        <td class="text-center active" style="width: 450px">

                          <span id="amazon_jp_commission_rate">0.9</span>%

                        </td>

                        <td class="text-center active"
                            style="width: 450px">

                          <span id="amazon_jp_bid_maximum_price">0</span>円

                        </td>
                      </tr>

                    </tbody>
                  </table>

                </td>
              </tr>

            </tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">管理ID</span>
        </th>

        <td class="active"><?php print $bids['id'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">作成日時</span>
        </th>

        <td class="active"><?php print $bids['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
        </th>

        <td class="active"><?php print $bids['modified_at'] ?></td>
      </tr>

    </tbody>
  </table>
  </div>
</table>
</div>
  <input type="hidden" name="id" value="<?php print $bids['id'] ?>" />
  <input type="hidden" name="user_id" value="<?php print $bids['user_id'] ?>" />
  <input type="hidden" name="state_id" value="<?php print $bids['state_id'] ?>" />
  <input type="hidden" name="auction_id" value="<?php print $bids['auction_id'] ?>" />
  <input type="hidden" name="seller_id" value="<?php print $bids['seller_id'] ?>" />
  <input type="hidden" name="title" value="<?php print $bids['title'] ?>" />
  <input type="hidden" name="rating_point" value="<?php print $bids['rating_point'] ?>" />
  <input type="hidden" name="rating_total_good_rating" value="<?php print $bids['rating_total_good_rating'] ?>" />
  <input type="hidden" name="rating_total_normal_rating" value="<?php print $bids['rating_total_normal_rating'] ?>" />
  <input type="hidden" name="rating_total_bad_rating" value="<?php print $bids['rating_total_bad_rating'] ?>" />
  <input type="hidden" name="auction_item_url" value="<?php print $bids['auction_item_url'] ?>" />
  <input type="hidden" name="img_image1" value="<?php print $bids['img_image1'] ?>" />
  <input type="hidden" name="img_image2" value="<?php print $bids['img_image2'] ?>" />
  <input type="hidden" name="img_image3" value="<?php print $bids['img_image3'] ?>" />
  <input type="hidden" name="initprice" value="<?php print $bids['initprice'] ?>" />
  <input type="hidden" name="price" value="<?php print $bids['price'] ?>" />
  <input type="hidden" name="bids" value="<?php print $bids['bids'] ?>" />
  <input type="hidden" name="start_time" value="<?php print $bids['start_time'] ?>" />
  <input type="hidden" name="end_time" value="<?php print $bids['end_time'] ?>" />
  <input type="hidden" name="bidorbuy" value="<?php print $bids['bidorbuy'] ?>" />
  <input type="hidden" name="option_store_icon_url" value="<?php print $bids['option_store_icon_url'] ?>" />
  <input type="hidden" name="option_check_icon_url" value="<?php print $bids['option_check_icon_url'] ?>" />
  <input type="hidden" name="option_new_icon_url" value="<?php print $bids['option_new_icon_url'] ?>" />
  <input type="hidden" name="option_escrow_icon_url" value="<?php print $bids['option_escrow_icon_url'] ?>" />
  <input type="hidden" name="option_featured_icon_url" value="<?php print $bids['option_featured_icon_url'] ?>" />
  <input type="hidden" name="option_free_shipping_icon_url" value="<?php print $bids['option_free_shipping_icon_url'] ?>" />
  <input type="hidden" name="option_wrapping_icon_url" value="<?php print $bids['option_wrapping_icon_url'] ?>" />
  <input type="hidden" name="option_buynow_icon_url" value="<?php print $bids['option_buynow_icon_url'] ?>" />
  <input type="hidden" name="option_easy_payment_icon_url" value="<?php print $bids['option_easy_payment_icon_url'] ?>" />
  <input type="hidden" name="option_gift_icon_url" value="<?php print $bids['option_gift_icon_url'] ?>" />
  <input type="hidden" name="option_bundle_icon_url" value="<?php print $bids['option_bundle_icon_url'] ?>" />
  <input type="hidden" name="option_item_status_new_icon_url" value="<?php print $bids['option_item_status_new_icon_url'] ?>" />
  <input type="hidden" name="option_y_bank_icon_url" value="<?php print $bids['option_y_bank_icon_url'] ?>" />
  <input type="hidden" name="option_english_icon_url" value="<?php print $bids['option_english_icon_url'] ?>" />
  <input type="hidden" name="option_star_club_icon_url" value="<?php print $bids['option_star_club_icon_url'] ?>" />
  <input type="hidden" name="option_charity_icon_url" value="<?php print $bids['option_charity_icon_url'] ?>" />
  <input type="hidden" name="created_at" value="<?php print $bids['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $bids['modified_at'] ?>" />
  <input type="hidden" name="deleted_at" value="<?php print $bids['deleted_at'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
