<?php $this->setPageTitle('title', '入札一覧') ?>

<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">
  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>入札管理</li>
  <li class="active">入札一覧</li>
</ol>

<ul class="nav nav-tabs">
<!-- switch( )と最初の case の間は空白を含めた何かを出力するとエラーになる。
     よって、switch( )構文は先頭の空白は無くしているのです。-->
<?php switch($state_id): ?>
<?php case $state['reserve_place_bids']: ?>
<?php   print $this->render('bids/tab/reserve_place_bids', array('state' => $state)); ?>
<?php   break; ?>
<?php case $state['bidding']: ?>
<?php   print $this->render('bids/tab/bidding', array('state' => $state)); ?>
<?php   break; ?>
<?php case $state['win']: ?>
<?php   print $this->render('bids/tab/win', array('state' => $state)); ?>
<?php   break; ?>
<?php case $state['end']: ?>
<?php   print $this->render('bids/tab/end', array('state' => $state)); ?>
<?php   break; ?>
<?php endswitch; ?>
</ul>

<form action="<?php print $base_url; ?>/bids/post"
      method="post"
      enctype="multipart/form-data">

<div class="table-responsive">
  <table id="dt"
         class="table display table-striped table-hover table-bordered"
         cellspacing="0"
         width="100%">

    <thead>

      <tr>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">
            <input type="checkbox" id="check-all" />
          </span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">商品写真</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">タイトル</span>
        </th>

        <th colspan="4" class="text-center info">
          <span class="text-primary">価格</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">入札数</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">残り時間</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">相場</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary"></span>
        </th>

      </tr>

      <tr>

        <th colspan="1" class="text-center info">
          <span class="text-primary">開始</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">現在</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">入札</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">即決</span>
        </th>

      </tr>

    </thead>

    <tbody>

      <?php foreach ($bidss as $bids): ?>

      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'bids'      => $bids,
                  'view_path' => $view_path,
                )
              ); ?>

      <?php endforeach; ?>

    </tbody>
  </table>
</div>

<input type="hidden"
       name="state_id"
       value="<?php print($state_id); ?>" />

<input type="hidden"
       name="_token"
       value="<?php print $this->escape($_token); ?>" />

</form>
