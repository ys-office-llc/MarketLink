<?php $this->setPageTitle('title', 'eBay一覧') ?>
<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">

  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li><a href="#">ユーザー設定</a></li>
  <li><a href="#">商品管理</a></li>
  <li><a href="#">出品条件</a></li>
  <li><a href="#">eBay</a></li>
  <li class="active">一覧</li>

</ol>

<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<div class="table-responsive">
  <table id="dt"
         class="table display table-bordered table-hover"
         cellspacing="0"
         width="100%">

    <thead>
      <tr>
        <th class="text-center info"><span class="text-primary">条件名</span></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($conditions as $condition): ?>
      <?php   print $this->render($view_path . '/individual',
                array(
                  'condition' => $condition,
                  'view_path' => $view_path
                )
              ); ?>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
