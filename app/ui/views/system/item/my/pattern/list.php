<?php $this->setPageTitle('title', '初期マイパターン一覧') ?>
<?php print $this->render('adm', array('' => array())); ?>

<ol class="breadcrumb">

  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>

  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/list'
              ?>">
      初期マイパターン一覧

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
        <th class="text-center info">

          <span class="text-primary">

            マイパターン名

          </span>
        </th>
      </tr>
    </thead>

    <tbody>

      <?php foreach ($system_item_my_patterns as $system_item_my_pattern): ?>
      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'system_item_my_pattern' => $system_item_my_pattern,
                  'view_path'  => $view_path,
                )
              );
      ?>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>
