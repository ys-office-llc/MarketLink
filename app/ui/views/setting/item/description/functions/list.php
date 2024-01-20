<?php $this->setPageTitle('title', '機能一覧') ?>
<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">

  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>ユーザー設定</li>
  <li>商品管理</li>
  <li>説明文</li>
  <li>機能</li>

  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/list'
              ?>">
      一覧

    </a>
  </li>

</ol>

<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<div class="table-responsive">
  <table id="dt"
         class="table display table-bordered table-hover"
         cellspacing="0"
         width="100%">

    <thead>
      <tr>
        <th class="text-center info"><span class="text-primary">説明文名</span></th>
      </tr>
    </thead>

    <tbody>

      <?php foreach ($description_functionss as $description_functions): ?>
      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'description_functions' => $description_functions,
                  'view_path'   => $view_path,
                )
              );
      ?>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>
