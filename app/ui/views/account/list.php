<?php $this->setPageTitle('title', 'ユーザー一覧') ?>
<?php print $this->render('adm', array('' => array())); ?>

<ol class="breadcrumb">

  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>

  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/list'
              ?>">
      ユーザー一覧

    </a>
  </li>

</ol>

<?php print(
        $this->render(
          $view_path.'/bar',
          array(
            'view_path' => $view_path
          )
        )
      )
 ?>

<div class="table-responsive">
  <table id="dt" class="table table-striped table-hover table-bordered">

    <thead>

      <tr>

        <th rowspan="3" class="text-center info">
          <span class="text-primary">ユーザーID</span>
        </th>

        <th rowspan="3" class="text-center info">
          <span class="text-primary">表示名</span>
        </th>

        <th rowspan="3" class="text-center info">
          <span class="text-primary">収容ホスト</span>
        </th>

        <th colspan="4" class="text-center info">
          <span class="text-primary">利用可否</span>
        </th>

        <th rowspan="3" class="text-center info">
          <span class="text-primary">ユーザー名</span>
        </th>

        <th colspan="2" class="text-center info">
          <span class="text-primary">Yahoo!</span>
        </th>

      </tr>

      <tr>

        <th colspan="2" class="text-center info">
          <span class="text-primary">ヤフオク</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">eBay</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">Amazon</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">販売用</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">仕入用</span>
        </th>

      </tr>

      <tr>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">販売用</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">仕入用</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">Japan</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">アカウント</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">アカウント</span>
        </th>

      </tr>
    </thead>

    <tbody>

      <?php foreach ($accounts as $account): ?>

      <?php

        print(
          $this->render(
            'account/individual',
            array(
              'account' => $account,
              'administrator_hosts' => $administrator_hosts,
            )
          )
        )

      ?>

      <?php endforeach; ?>

    </tbody>

  </table>
</div>
