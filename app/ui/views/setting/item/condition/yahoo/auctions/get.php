e?php $this->setPageTitle('title', 'ヤフオク作成') ?>

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
  <li>ヤフオク</li>

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
                    'param' => $condition)); ?>

  </div>

  <div class="table-responsive">
  <table class="table display table-bordered" cellspacing="0" width="100%">
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
        <th class="text-center info">
          <span class="text-primary">カテゴリー選択</span>
        </th>

        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'select_category_id',
              'values'   => $table_values['item_condition_yahoo_auctions_select_category'],
              'selected' => $condition['select_category_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">販売形式</span>
        </th>

        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'exhibits_style_id',
              'values'   => $table_values['item_condition_yahoo_auctions_exhibits_style'],
              'selected' => $condition['exhibits_style_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">開催期間</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'sales_period_id',
              'values'   => $table_values['item_condition_yahoo_auctions_sales_period'],
              'selected' => $condition['sales_period_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">都道府県</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'shipping_origin_id',
              'values'   => $table_values['item_condition_yahoo_auctions_shipping_origin'],
              'selected' => $condition['shipping_origin_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">商品の状態</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'item_status_id',
              'values'   => $table_values['item_condition_yahoo_auctions_item_status'],
              'selected' => $condition['item_status_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">商品備考</span></th>
        <td class="active"><input type="text" name="item_remarks" value="<?php print $condition['item_remarks'] ?>" size="32" /></td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">返品可否</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'accept_returns_id',
              'values'   => $table_values['item_condition_yahoo_auctions_accept_returns'],
              'selected' => $condition['accept_returns_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">返品備考</span></th>
        <td class="active"><input type="text" name="returns_remarks" value="<?php print $condition['returns_remarks'] ?>" size="32" /></td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">終了時間</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'endtime_id',
              'values'   => $table_values['item_condition_yahoo_auctions_endtime'],
              'selected' => $condition['endtime_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">注目のオークション</span></th>
        <td class="active"><input type="text" name="attention_price" value="<?php print $condition['attention_price'] ?>" size="8" />円</td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">自動再出品回数</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'iteration_count_id',
              'values'   => $table_values['item_condition_yahoo_auctions_iteration_count'],
              'selected' => $condition['iteration_count_id'],
            )); ?>
        </td>
      </tr>

      <?php
        print $this->render(
          'setting/item/condition/yahoo/auctions/shipping_method',
          array(
            'table_values' => $table_values,
            'condition'    => $condition,
          ));
       ?>

      <tr>
        <th class="text-center info">

          <span class="text-primary">

            発送日までの日数

          </span>
        </th>

        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'transport_days_id',
              'values'   => $table_values['item_condition_yahoo_auctions_transport_days'],
              'selected' => $condition['transport_days_id'],
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">配送確認URL</span></th>
        <td class="active"><input type="text" name="tracking_url" value="<?php print $condition['tracking_url'] ?>" size="64" /></td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">評価コメント</span></th>
        <td class="active">

          <textarea name="value_comment"
               rows="4"
               cols="96"><?php print $condition['value_comment'] ?></textarea>

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
