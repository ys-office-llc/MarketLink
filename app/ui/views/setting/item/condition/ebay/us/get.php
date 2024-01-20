<?php $this->setPageTitle('title', 'eBay作成') ?>

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
  <li>商品管理</li>
  <li>出品条件</li>
  <li>eBay</li>

  <li class="active">

  <?php if (isset($condition['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $condition['id']
              ?>">

      情報修正

    </a>

  <?php else: ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>">

      作成

    </a>

  <?php endif; ?>

</ol>

<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('crudd',
                  array(
                    'param'  => $condition)); ?>

  </div>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">

    <tbody>
      <tr>
        <th class="text-center info">
          <span class="text-primary">条件名</span>
        </th>

        <td class="active">

          <input type="text"
                 name="name"
                 value="<?php print $condition['name'] ?>"
                 size="96" />

        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">郵便番号</span></th>
        <td class="active"><input type="text" name="postcode" value="<?php print $condition['postcode'] ?>" size="8" /></td>
      </tr>
      <tr>
        <th class="text-center info">
          <span class="text-primary">英語表記住所</span>
        </th>

        <td class="active" style="width: 600px">
          <?php print $this->render('select',
            array(
              'name'     => 'prefs_id',
              'values'   => $table_values['item_condition_ebay_us_prefs'],
              'selected' => $condition['prefs_id'],
            )); ?>
        </td>
      </tr>

<!--

<?php
/*
      <tr>
        <th class="text-center info">
          <span class="text-primary">PayPalメールアドレス</span>
        </th>

        <td class="active">
          <input type="text"
                 name="paypal_mailaddress"
                 value="<?php print $condition['paypal_mailaddress'] ?>"
                 size="32" />
        </td>
      </tr>

*/
?>

-->

      <tr>
        <th class="text-center info"><span class="text-primary">コンディション</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'condition_id',
              'values'   => $table_values['item_condition_ebay_us_item_condition_ids'],
              'selected' => $condition['condition_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">出品形式</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'listing_type_id',
              'values'   => $table_values['item_condition_ebay_us_listing_type'],
              'selected' => $condition['listing_type_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">出品期間</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'listing_duration_id',
              'values'   => $table_values['item_condition_ebay_us_listing_duration'],
              'selected' => $condition['listing_duration_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">数量</span></th>
        <td class="active">
          <input type="text"
                 name="quantity"
                 value="<?php print $condition['quantity'] ?>"
                 size="8" />
        </td>
      </tr>

<!--

<?php

/*

      <tr>
        <th class="text-center info"><span class="text-primary">ハンドリングタイム</span></th>
        <td class="active">
          <input type="text"
                 name="dispatch_time_max"
                 value="<?php print $condition['dispatch_time_max'] ?>"
                 size="8" />
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">配送サービス</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'shipping_service_id',
              'values'   => $table_values['item_condition_ebay_us_shipping_service'],
              'selected' => $condition['shipping_service_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">最短到着日数</span></th>
        <td class="active">
          <input type="text"
                 name="shipping_time_min"
                 value="<?php print $condition['shipping_time_min'] ?>"
                 size="8" />
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">最長到着日数</span></th>
        <td class="active">
          <input type="text"
                 name="shipping_time_max"
                 value="<?php print $condition['shipping_time_max'] ?>"
                 size="8" />
        </td>
      </tr>

*/

?>

-->

      <tr>
        <th class="text-center info"><span class="text-primary">ベストオファー</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'best_offer_id',
              'values'   => $table_values['item_condition_ebay_us_best_offer'],
              'selected' => $condition['best_offer_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">

          <span class="text-primary">フィードバックコメント</span></th>

        </th>

        <td class="active">

          <input type="text"
                 name="feedback_info_comment_text"
                 value="<?php
                   print $condition['feedback_info_comment_text']
                 ?>"
                 size="80" />

        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">国内配送費用</span></th>
        <td class="active">
          <input type="text"
                 name="shipping_service_cost"
                 value="<?php print $condition['shipping_service_cost'] ?>"
                 size="8" />ドル
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">国際配送費用</span></th>
        <td class="active">
          <input type="text"
                 name="international_shipping_service_cost"
                 value="<?php print $condition['international_shipping_service_cost'] ?>"
                 size="8" />ドル
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">返品ポリシー</span></th>
        <td class="active"><textarea name="return_policy_description" cols="48" rows="5"><?php print $condition['return_policy_description'] ?></textarea></td>
      </tr>

*/

?>

-->


      <tr>
        <th class="text-center info">
          <span class="text-primary">ビジネスポリシー</span>
        </th>

        <td class="active" style="width: 700px">

          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">支払いポリシー名</span>
                </th>

                <td class="active">

                  <?php print $this->render('select',
                    array(
                      'name'     => 'payment_policy_id',
                      'values'   => $table_values['item_condition_ebay_us_payment_policy'],
                      'selected' => $condition['payment_policy_id'],
                    )); ?>

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">配送ポリシー名</span>
                </th>
        
                <td class="active">

                  <?php print $this->render('select',
                    array(
                      'name'     => 'shipping_policy_id',
                      'values'   => $table_values['item_condition_ebay_us_shipping_policy'],
                      'selected' => $condition['shipping_policy_id'],
                    )); ?>

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">返品ポリシー名</span>
                </th>
        
                <td class="active">

                  <?php print $this->render('select',
                    array(
                      'name'     => 'return_policy_id',
                      'values'   => $table_values['item_condition_ebay_us_return_policy'],
                      'selected' => $condition['return_policy_id'],
                    )); ?>

                </td>
              </tr>

            </tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">管理番号</span></th>
        <td class="active"><?php print $condition['id'] ?></td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">作成日時</span></th>
        <td class="active"><?php print $condition['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">変更日時</span></th>
        <td class="active"><?php print $condition['modified_at'] ?></td>
      </tr>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="id" value="<?php print $condition['id'] ?>" />
  <input type="hidden" name="created_at" value="<?php print $condition['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $condition['modified_at'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
