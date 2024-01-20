<?php $this->setPageTitle('title', '商品一覧') ?>

<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">
  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>商品管理</li>
  <li class="active">
    商品一覧（<?php print $table_values['item_state'][$state_id]['name'] ?>）
  </li>
</ol>

<ul class="nav nav-tabs">
<!-- switch( )と最初の case の間は空白を含めた何かを出力するとエラーになる。
     よって、switch( )構文は先頭の空白は無くしているのです。-->
<?php switch($state_id): ?>
<?php case $state['waiting']: ?>
<?php   print $this->render('item/tab/waiting', array('state' => $state)); ?>
<?php   break; ?>
<?php case $state['exhibit']: ?>
<?php   print $this->render('item/tab/exhibit', array('state' => $state)); ?>
<?php   break; ?>
<?php case $state['selling']: ?>
<?php   print $this->render('item/tab/selling', array('state' => $state)); ?>
<?php   break; ?>
<?php case $state['payment']: ?>
<?php   print $this->render('item/tab/payment', array('state' => $state)); ?>
<?php   break; ?>
<?php case $state['shipment']: ?>
<?php   print $this->render('item/tab/shipment', array('state' => $state)); ?>
<?php   break; ?>
<?php endswitch; ?>
</ul>

<?php if ($state['shipment'] === $state_id): ?>

<form action="<?php print $base_url; ?>/item/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <input class="btn btn-danger"
           id="confirm_delete"
           type="submit"
           name="delete_by_checked"
           value="選択した商品を一括で削除する" />

  </div>

<?php endif; ?>

<div class="table-responsive">
  <table id="dt"
         class="table display table-striped table-hover table-bordered"
         cellspacing="0"
         width="100%">

    <thead>

      <!-- 1 行目の表を作る -->
      <tr>

<?php if ($state['shipment'] === $state_id): ?>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">
            <input type="checkbox" id="check-all" />
          </span>
        </th>

<?php endif; ?>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">画像</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">メモ</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">SKU</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">タイトル</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">仕入価格</span>
        </th>

        <th colspan="3" class="text-center info">
          <span class="text-primary">状態</span>
        </th>

      <?php if ($state['waiting'] === $state_id): ?>

        <!-- colspanなので注意 -->
        <th colspan="3" class="text-center info">
          <span class="text-primary">ヤフオク</span>
        </th>

      <?php elseif ($state['exhibit'] === $state_id): ?>

        <th colspan="3" class="text-center info">
          <span class="text-primary">ヤフオク</span>
        </th>

      <?php endif; ?>

      <?php if ($state['waiting'] === $state_id): ?>

        <th colspan="3" class="text-center info">
          <span class="text-primary">eBay</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">Amazon</span>
        </th>

      <?php elseif ($state['exhibit'] === $state_id): ?>

        <th colspan="3" class="text-center info">
          <span class="text-primary">eBay</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">Amazon</span>
        </th>

      <?php elseif ($state['selling'] === $state_id or
                    $state['payment'] === $state_id or
                    $state['shipment'] === $state_id): ?>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">販売価格</span>
        </th>

      <?php endif; ?>

      <?php if ($state['waiting'] === $state_id): ?>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">作成日時</span>
        </th>

      <?php endif; ?>

      <?php if ($state['shipment'] === $state_id): ?>

        <th colspan="4" class="text-center info">
          <span class="text-primary">EMS</span>
        </th>

      <?php endif; ?>

      </tr>

      <!-- 2 行目の表を作る -->
      <tr>

        <th class="text-center info">
          <span class="text-primary">ヤフオク</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">eBay</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">Amazon</span>
        </th>

      <?php if ($state['waiting'] === $state_id): ?>

        <th class="text-center info">
          <span class="text-primary">開始価格</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">即決価格</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">最低落札価格</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">Watchers</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">開始価格</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">終了価格</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">出品価格</span>
        </th>

      <?php elseif ($state['exhibit'] === $state_id): ?>

        <th class="text-center info">
          <span class="text-primary">ウォッチ</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">現在価格</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">残り時間</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">Watchers</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">Current Price</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">Time left</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">出品価格</span>
        </th>

      <?php endif; ?>

      <?php if ($state['shipment'] === $state_id): ?>

        <th class="text-center info">
          <span class="text-primary">追跡番号</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">引受日時</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">到着日時</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">配送履歴</span>
        </th>

      <?php endif; ?>

      </tr>

    </thead>

    <tbody>

      <?php foreach ($items as $item): ?>
      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'item'         => $item,
                  'url'          => $url,
                  'view_path'    => $view_path,
                  'table_values' => $table_values,
                  'state_id'     => $state_id,
                  'state'        => $state,
                )
              ); ?>
      <?php endforeach; ?>

    </tbody>
  </table>
</div>

<?php if ($state['shipment'] === $state_id): ?>

<input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>

<?php endif; ?>
