<?php $this->setPageTitle('title', 'ホスト一覧') ?>
<?php print $this->render('adm', array('' => array())); ?>

<ol class="breadcrumb">

  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>

  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/list'
              ?>">

      ホスト一覧

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

            ホスト名

          </span>

        </th>
      </tr>

    </thead>

    <tbody>

      <?php foreach ($administrator_hosts as $administrator_host): ?>

      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'administrator_host' => $administrator_host,
                  'view_path'          => $view_path,
                )
              )
      ?>

      <?php endforeach; ?>

    </tbody>
  </table>

</div>
