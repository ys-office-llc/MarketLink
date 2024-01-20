<?php $this->setPageTitle('title', $item['product_name']) ?>
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
  <li>商品管理</li>

<?php if (isset($item['id'])): ?>

  <li class="active">

    <a href="<?php
         print $base_url.
               '/item/get/'.
               $item['id']
              ?>"
       target="_self">

    情報修正

    </a>

  </li>

<?php else: ?>

  <li class="active">新規追加</li>

<?php endif; ?>

</ol>

<?php print $this->render('item/page', array('item' => $item)); ?>

<form action="<?php print $base_url; ?>/item/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

  <?php   if (isset($item['id'])): ?>

    <?php print $this->render('item/submit/all',
                  array(
                    'waiting'  => $waiting,
                    'exhibit'  => $exhibit,
                    'reserved' => $reserved,
                    'prepare'  => $prepare,
                  )); ?>


    <?php print $this->render('item/submit/yahoo/auctions',
                  array(
                    'prepare' => $prepare,
                    'state'   => $state,
                    'item'    => $item,
                  )); ?>

    <?php print $this->render('item/submit/ebay/us',
                  array(
                    'prepare' => $prepare,
                    'state'   => $state,
                    'item'    => $item,
                  )); ?>

    <?php print $this->render('item/submit/amazon/jp',
                  array(
                    'prepare' => $prepare,
                    'state'   => $state,
                    'item'    => $item,
                  )); ?>

  <?php   endif; ?>

    <?php print $this->render('item/crud',
                  array(
                    'prepare'  => $prepare,
                    'reserved'  => $reserved,
                    'waiting'   => $waiting,
                    'shipment'  => $shipment,
                    'go_update' => $go_update,
                    'param'     => $item)); ?>

  </div>

  <div class="table-responsive">
  <table class="table display table-bordered table-condensed">
    <tbody>

    <?php if (!is_null($item['id'])):  ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">状態</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

    <?php if ($item['yahoo_auctions_state_id'] > 0): ?>
      <tr>
        <th class="text-center info">
        <?php if (strlen($item['yahoo_auctions_url']) > 0): ?>
          <a href="<?php print $item['yahoo_auctions_url'] ?>"
             class="btn btn-primary"
             style="width: 150px;"
             target="_blank">
            <span class="text-default">ヤフオク</span>
          </a>
        <?php else: ?>
          <span class="text-primary">ヤフオク</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <div class="progress">
            <div class="progress-bar"
                 aria-valuenow="10"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 style="width:<?php print $item['yahoo_auctions_state_id'] * 10 ?>%">
              <?php print $table_values['item_state'][$item['yahoo_auctions_state_id']]['name'] ?>
            </div>
          </div>
        </td>
      </tr>

    <?php if ($item['ebay_us_state_id'] > 0): ?>
      <tr>
        <th class="text-center info">
        <?php if (strlen($item['ebay_us_url']) > 0): ?>
          <a href="<?php print $item['ebay_us_url'] ?>"
             class="btn btn-primary"
             style="width: 150px;"
             target="_blank">
            <span class="text-default">eBay</span>
          </a>
        <?php else: ?>
          <span class="text-primary">eBay</span>
        <?php endif; ?>
        </th>

        <td class="active" style="width: 800px">
          <div class="progress">
            <div class="progress-bar"
                 aria-valuenow="10"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 style="width:<?php print $item['ebay_us_state_id'] * 10 ?>%">
              <?php print $table_values['item_state'][$item['ebay_us_state_id']]['name'] ?>
            </div>
          </div>
        </td>
      </tr>
    <?php endif; ?>

    <?php if ($item['amazon_jp_state_id'] > 0): ?>
      <tr>
        <th class="text-center info">
          <span class="text-primary">Amazon.co.jp</span>
        </th>

        <td class="active" style="width: 800px">
          <div class="progress">
            <div class="progress-bar"
                 aria-valuenow="10"
                 aria-valuemin="0"
                 aria-valuemax="100"
                 style="width:<?php print $item['amazon_jp_state_id'] * 10 ?>%">
              <?php print $table_values['item_state'][$item['amazon_jp_state_id']]['name'] ?>
            </div>
          </div>
        </td>
      </tr>
    <?php endif; ?>
            </tbody>
          </table>
        </td>
      </tr>
    <?php endif; ?>
    <?php endif; ?>

    <?php

      if (true) {

        print(
          $this->render(
            'item/delivery_information',
            array(
              'item'     => $item,
              'shipment' => $shipment,
              'payment'  => $payment,
            )
          )
        );

      }

    ?>

      <tr>
        <th class="text-center info">
        <?php if ($item['my_pattern_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/my/pattern/get/<?php print $item['my_pattern_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
             <span class="text-default">マイパターン</span>
          </a>
        <?php else: ?>
          <span class="text-primary">マイパターン</span>
        <?php endif; ?>
        </th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'my_pattern_id',
              'values'   => $table_values['item_my_pattern'],
              'selected' => $item['my_pattern_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>

        <th class="text-center info">

        <?php if (strlen($item['yahoo_auctions_stockless_url']) > 0): ?>

          <a href="<?php print $item['yahoo_auctions_stockless_url'] ?>"
             class="btn btn-primary"
             style="width: 150px;"
             target="_blank">

            <span class="text-default">商品名</span>

          </a>

        <?php else: ?>

          <span class="text-primary">商品名</span>

        <?php endif; ?>

        </th>

        <td class="active">
          <input type="text"
                 name="product_name"
                 value="<?php print $item['product_name'] ?>"
                 size="96" />
        </td>
      </tr>

    <?php
      print $this->render('item/title',
        array(
          'item' => $item,
          'yahoo_auctions_product_name_size' => $yahoo_auctions_product_name_size,
          'ebay_com_product_name_size' => $ebay_us_product_name_size,
        )
      ); ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">個体情報</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">SKU</span>
                </th>

                        <td class="text-left active" style="width: 800px">
                  <input type="text"
                         name="stock_keeping_unit"
                         value="<?php print $item['stock_keeping_unit'] ?>"
                         size="16"/>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">仕入価格</span>
                </th>
        
                        <td class="text-left active" style="width: 800px">
                  <input type="text"
                         name="cost_price"
                         value="<?php print $item['cost_price'] ?>"
                         size="8"/>円
                </td>
              </tr>
        
              <tr>
                <th class="text-center info">
                  <span class="text-primary">シリアルナンバー</span>
                </th>
        
                        <td class="text-left active" style="width: 800px">
                  <input type="text"
                         name="serial_number"
                         value="<?php print $item['serial_number'] ?>"
                         size="16" />
                </td>
              </tr>
        
              <tr>
                <th class="text-center info">
                  <span class="text-primary">画像</span>
                </th>
        
                        <td class="text-left active" style="width: 800px">
                  <?php print $this->render('item/modal',
                                array(
                                  'item' => $item,
                                  'url'  => $url)); ?>
        
                  <input id="input-ja"
                         name="files[]"
                         type="file"
                         class="file"
                         multiple
                         data-show-upload="false"
                         data-show-caption="false">
                </td>
              </tr>

            </tbody>
          </table>
        </td>
      </tr>

    <?php if (!is_null($item['id'])):  ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品情報</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>

      <tr>
        <th class="text-center info">
        <?php if ($item['maker_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/maker/get/<?php print $item['maker_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">メーカー</span>

          </a>
        <?php else: ?>
          <span class="text-primary">メーカー</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'maker_id',
              'values'   => $table_values['item_maker'],
              'selected' => $item['maker_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if ($item['category_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/category/get/<?php print $item['category_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">カテゴリー</span>
          </a>
        <?php else: ?>
          <span class="text-primary">カテゴリー</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'category_id',
              'values'   => $table_values['item_category'],
              'selected' => $item['category_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if ($item['grade_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/grade/get/<?php print $item['grade_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">グレード</span>
          </a>
        <?php else: ?>
          <span class="text-primary">グレード</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'grade_id',
              'values'   => $table_values['item_grade'],
              'selected' => $item['grade_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

  <?php

    if (true) {

      print(
        $this->render('item/product/description',
          array(
            'item' => $item,
            'table_values' => $table_values,
          )
        )
      );

    }

  ?>

      <tr>
        <th class="text-center info">
        <?php if ($item['accessories_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/accessories/get/<?php print $item['accessories_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">付属品</span>
          </a>
        <?php else: ?>
          <span class="text-primary">付属品</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'accessories_id',
              'values'   => $table_values['item_accessories'],
              'selected' => $item['accessories_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

<!--

<?php /*

    <?php print $this->render('item/product/grade',
                  array(
                    'item' => $item,
                  )
          ); ?>

    <?php print $this->render('item/product/accessories',
                  array(
                    'item' => $item,
                  )
          ); ?>

*/ ?>

-->

      <tr>
        <th class="text-center info">
          <span class="text-primary">備考</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">日本語</span>
                </th>
                <td class="text-left active">
                  <textarea name="remarks_ja"
                            rows="2"
                            cols="64"><?php print $item['remarks_ja'] ?></textarea>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">英語</span>
                 </th>
                <td class="text-left active">
                  <textarea name="remarks_en"
                            rows="2"
                            cols="64"><?php print $item['remarks_en'] ?></textarea>
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

    <?php endif; ?>

    <?php if ($item['yahoo_auctions_state_id'] > 0): ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">ヤフオク</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">開始価格</span>
                 </th>

                <td class="text-left active" style="width: 800px">
                  <input type="text"
                         name="yahoo_auctions_start_price"
                         value="<?php print $item['yahoo_auctions_start_price'] ?>"
                         size="8" />円

          <a href="<?php print $http_query['yahoo']['auctions']['sold'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">
            <span class="text-default">過去相場</span>
          </a>

          <a href="<?php print $http_query['yahoo']['auctions']['selling'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">
            <span class="text-default">現在相場</span>
          </a>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">即決価格</span>
                </th>

                <td class="text-left active" style="width: 800px">
                  <input type="text"
                         name="yahoo_auctions_end_price"
                         value="<?php print $item['yahoo_auctions_end_price'] ?>"
                         size="8" />円
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">最低落札価格</span>
                </th>

                <td class="text-left active" style="width: 800px">

                  <input type="text"
                         name="yahoo_auctions_reserve_price"
                         value="<?php print $item['yahoo_auctions_reserve_price'] ?>"
                         size="8" />円

                <?php if ($this->getUserData()['account_contract_id']  > 2): ?>

                  <label>
                    <input type="checkbox"
                           name="do_snipe[]"
                           value="yahoo_auctions"
                <?php if (!is_null($item['do_snipe']) and
                          in_array(
                            'yahoo_auctions',
                            $item['do_snipe'],
                            true
                          )): ?>
                           checked />
                <?php else: ?>
                           />
                <?php endif; ?>

                    <span class="text-muted">

                      スナイプ出品停止機能を有効にする

                    </span>

                  </label>
                <?php endif; ?>

                </td>
              </tr>

            <?php if ($item['yahoo_auctions_sale_price'] > 0): ?>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">落札価格</span>
                </th>

                <td class="text-left active" style="width: 800px">
                  <?php print number_format(
                          $item['yahoo_auctions_sale_price']) ?>円
                </td>
              </tr>

            <?php endif; ?>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">終了時間帯</span>
                </th>

                <td class="text-left active" style="width: 800px">
                  <span class="text-muted">
                    <strong><?php print $endtime ?></strong>
                  </span>
                </td>
              </tr>


              <tr>
                <th class="text-center info">
                <?php if ($item['yahoo_auctions_condition_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/condition/yahoo/auctions/get/<?php print $item['yahoo_auctions_condition_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品条件</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品条件</span>
                <?php endif; ?>
                 </th>

                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'yahoo_auctions_condition_id',
                      'values'   => $table_values['item_condition_yahoo_auctions'],
                      'selected' => $item['yahoo_auctions_condition_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                <?php if ($item['yahoo_auctions_template_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/template/yahoo/auctions/get/<?php print $item['yahoo_auctions_template_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品ページ</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品ページ</span>
                <?php endif; ?>
                </th>
                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'yahoo_auctions_template_id',
                      'values'   => $table_values['item_template_yahoo_auctions'],
                      'selected' => $item['yahoo_auctions_template_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>

            </tbody>

          </table>
        </td>
      </tr>

    <?php endif; ?>

    <?php if ($item['ebay_us_state_id'] > 0): ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">eBay</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">開始価格</span>
                </th>

                <td class="text-left active" style="width: 800px">
                  <input type="text"
                         id="ebay_us_start_price"
                         name="ebay_us_start_price"
                         value="<?php print $item['ebay_us_start_price'] ?>"
                         size="8" />ドル

          <a href="<?php print $http_query['ebay']['sold'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">

            <span class="text-default">過去相場</span>

          </a>

          <a href="<?php print $http_query['ebay']['active'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">

            <span class="text-default">現在相場</span>

          </a>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">終了価格</span>
                </th>

                <td class="text-left active" style="width: 800px">
                  <input type="text"
                         id="ebay_us_end_price"
                         name="ebay_us_end_price"
                         value="<?php print $item['ebay_us_end_price'] ?>"
                         size="8" />ドル
                </td>
              </tr>

            <?php if ($item['ebay_us_sale_price'] > 0): ?>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">落札価格</span>
                </th>

                <td class="text-left active" style="width: 800px">
                  <?php print number_format(
                          $item['ebay_us_sale_price']) ?>ドル
                </td>
              </tr>
            <?php endif; ?>

              <tr>
                <th class="text-center info">
                <?php if ($item['ebay_us_condition_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/condition/ebay/us/get/<?php print $item['ebay_us_condition_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品条件</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品条件</span>
                <?php endif; ?>
                </th>

                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'ebay_us_condition_id',
                      'values'   => $table_values['item_condition_ebay_us'],
                      'selected' => $item['ebay_us_condition_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                <?php if ($item['ebay_us_template_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/template/ebay/us/get/<?php print $item['ebay_us_template_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品ページ</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品ページ</span>
                <?php endif; ?>
                </th>
                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'ebay_us_template_id',
                      'values'   => $table_values['item_template_ebay_us'],
                      'selected' => $item['ebay_us_template_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>

            </tbody>

          </table>
        </td>
      </tr>

    <?php endif; ?>

    <?php if ($item['amazon_jp_state_id'] > 0): ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">Amazon.co.jp</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">ASINコード</span>
                </th>

                <td class="text-left active" style="width: 800px">
                  <input type="text"
                         name="amazon_jp_asin"
                         value="<?php print $item['amazon_jp_asin'] ?>"
                         size="16" />

          <a href="<?php print $http_query['amazon']['jp']['dp'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">

            <span class="text-default">ASINによるリンク</span>

          </a>

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">出品価格</span>
                </th>

                <td class="text-left active" style="width: 800px">
                  <input type="text"
                         name="amazon_jp_price"
                         value="<?php print $item['amazon_jp_price'] ?>"
                         size="8" />円

          <a href="<?php print $http_query['mnrate'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">

            <span class="text-default">過去相場</span>

          </a>
          <a href="<?php print $http_query['amazon']['jp']['offer'] ?>"
             class="btn btn-primary"
             target="_blank"
             style="width: 150px;">

            <span class="text-default">現在相場</span>

          </a>
                </td>
              </tr>

            <?php if ($item['amazon_jp_state_id'] > 0): ?>
              <tr>
                <th class="text-center info">
                <?php if ($item['amazon_jp_template_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/template/amazon/jp/get/<?php print $item['amazon_jp_template_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品ページ</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品ページ</span>
                <?php endif; ?>
                 </th>

                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'amazon_jp_template_id',
                      'values'   => $table_values['item_template_amazon_jp'],
                      'selected' => $item['amazon_jp_template_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>
            <?php endif; ?>

            </tbody>

          </table>
        </td>
      </tr>

    <?php endif; ?>

    <?php if (!is_null($item['id'])):  ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">出品制御</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">再出品</span>
                </th>

                <td class="text-left active" style="width: 800px">

                  <label>
                    <input type="checkbox"
                           name="do_repeat[]"
                           value="yahoo_auctions"
                <?php if (!is_null($item['do_repeat']) and
                          in_array(
                            'yahoo_auctions',
                            $item['do_repeat'],
                            true
                          )): ?>
                           checked />
                <?php else: ?>
                           />
                <?php endif; ?>

                    <span class="text-muted">
                      ヤフオク
                    </span>

                  </label>

                  <label>
                    <input type="checkbox"
                           name="do_repeat[]"
                           value="ebay_us"
                <?php if (!is_null($item['do_repeat']) and
                          in_array(
                            'ebay_us',
                            $item['do_repeat'],
                            true
                          )): ?>
                           checked />
                <?php else: ?>
                           />
                <?php endif; ?>

                    <span class="text-muted">
                      eBay
                    </span>
                  </label>

                </td>

              </tr>

            </tbody>

          </table>
        </td>
      </tr>

    <?php endif; ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">メモ</span>
        </th>

        <td class="text-left active">

          <textarea name="notes"
                    rows="5"
                    cols="64"><?php print $item['notes'] ?></textarea>
        </td>
      </tr>

    <?php if (!is_null($item['id'])):  ?>

      <tr>
        <th class="text-center info">
          <span class="text-primary">管理ID</span>
        </th>

        <td class="active"><?php print $item['id'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">作成日時</span>
        </th>

        <td class="active"><?php print $item['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
        </th>

        <td class="active"><?php print $item['modified_at'] ?></td>
      </tr>

    <?php endif; ?>

    </tbody>
  </table>
  </div>
</table>
</div>
  <input type="hidden" name="id" value="<?php print $item['id'] ?>" />
  <input type="hidden" name="user_id" value="<?php print $item['user_id'] ?>" />
  <input type="hidden" name="yahoo_auctions_state_id" value="<?php print $item['yahoo_auctions_state_id'] ?>" />
  <input type="hidden" name="ebay_us_state_id" value="<?php print $item['ebay_us_state_id'] ?>" />
  <input type="hidden" name="amazon_jp_state_id" value="<?php print $item['amazon_jp_state_id'] ?>" />
  <input type="hidden" name="yahoo_auctions_item_id" value="<?php print $item['yahoo_auctions_item_id'] ?>" />
  <input type="hidden" name="yahoo_auctions_stockless_item_id" value="<?php print $item['yahoo_auctions_stockless_item_id'] ?>" />
  <input type="hidden" name="yahoo_auctions_stockless_url" value="<?php print $item['yahoo_auctions_stockless_url'] ?>" />
  <input type="hidden" name="ebay_us_item_id" value="<?php print $item['ebay_us_item_id'] ?>" />
  <input type="hidden" name="amazon_jp_item_id" value="<?php print $item['amazon_jp_item_id'] ?>">
  <input type="hidden" name="yahoo_auctions_sale_price" value="<?php print $item['yahoo_auctions_sale_price'] ?>" />
  <input type="hidden" name="ebay_us_sale_price" value="<?php print $item['ebay_us_sale_price'] ?>" />
<?php if ($item['amazon_jp_state_id'] == $state['exclude']): ?>
  <!-- 除外からの復帰時に利用する -->
  <input type="hidden" name="amazon_jp_price" value="<?php print $item['amazon_jp_price'] ?>">
  <input type="hidden" name="amazon_jp_template_id" value="<?php print $item['amazon_jp_template_id'] ?>">
  <input type="hidden" name="amazon_jp_asin" value="<?php print $item['amazon_jp_asin'] ?>">
<?php endif; ?>
  <input type="hidden" name="feed_message_id" value="<?php print $item['feed_message_id'] ?>" />
  <input type="hidden" name="request_to_bids" value="<?php print $item['request_to_bids'] ?>" />
  <input type="hidden" name="yahoo_auctions_current_price" value="<?php print $item['yahoo_auctions_current_price'] ?>">
  <input type="hidden" name="yahoo_auctions_end_time" value="<?php print $item['yahoo_auctions_end_time'] ?>">
  <input type="hidden" name="yahoo_auctions_time_left" value="<?php print $item['yahoo_auctions_time_left'] ?>">
  <input type="hidden" name="ebay_us_time_left" value="<?php print $item['ebay_us_time_left'] ?>">
  <input type="hidden" name="ebay_us_end_time" value="<?php print $item['ebay_us_end_time'] ?>">
  <input type="hidden" name="yahoo_auctions_num_watch" value="<?php print $item['yahoo_auctions_num_watch'] ?>">
  <input type="hidden" name="ebay_us_num_watch" value="<?php print $item['ebay_us_num_watch'] ?>">
  <input type="hidden" name="completed" value="<?php print $item['completed'] ?>">
  <input type="hidden" name="yahoo_auctions_url" value="<?php print $item['yahoo_auctions_url'] ?>" />
  <input type="hidden" name="ebay_us_url" value="<?php print $item['ebay_us_url'] ?>" />
  <input type="hidden" name="amazon_jp_product_name" value="<?php print $item['amazon_jp_product_name'] ?>" />
  <input type="hidden" name="amazon_jp_url" value="<?php print $item['amazon_jp_url'] ?>" />

  <input type="hidden"
         name="ems_tracking_number"
         value="<?php print $item['ems_tracking_number'] ?>" />

  <input type="hidden"
         name="ems_delivery_history"
         value="<?php print $item['ems_delivery_history'] ?>" />

  <input type="hidden"
         name="ems_acceptance_datetime"
         value="<?php print $item['ems_acceptance_datetime'] ?>" />

  <input type="hidden"
         name="ems_arrival_datetime"
         value="<?php print $item['ems_arrival_datetime'] ?>" />

  <input type="hidden" name="created_at" value="<?php print $item['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $item['modified_at'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
