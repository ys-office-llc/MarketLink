<?php $this->setPageTitle('title', 'ホスト作成') ?>

<?php print $this->render('adm', array('' => array())); ?>

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

  <li class="active">

  <?php if (isset($administrator_host['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $administrator_host['id']
              ?>">

      情報修正

    </a>

  <?php else: ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>">

      ホスト登録

    </a>

  <?php endif; ?>

</ol>

<?php

  print(
    $this->render(
      $view_path.'/bar',
      array(
        'view_path' => $view_path
      )
    )
  )

?>

<form action="<?php print $base_url.'/'.$view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('crud',
                  array(
                    'param'  => $administrator_host)); ?>

  <div class="table-responsive">

  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">

    <tbody>

      <tr>

        <th class="text-center info">

          <span class="text-primary">ホスト名</span>

        </th>

        <td class="active">

          <input type="text"
                 name="name"
                 value="<?php print $administrator_host['name'] ?>"
                 size="96" />

        </td>

      </tr>

      <tr>

        <th class="text-center info">

          <span class="text-primary">最大収容数</span>

        </th>

        <td class="active">

          <input type="text"
                 name="maximum_capacity"
                 value="<?php
                          print(
                            $administrator_host['maximum_capacity']
                          )
                        ?>"
                 size="8" />

        </td>

      </tr>

      <tr>
        <th class="text-center info">

          <span class="text-primary">管理ID</span>

        </th>

        <td class="active">

          <?php print $administrator_host['id'] ?>

        </td>
      </tr>

      <tr>
        <th class="text-center info">

          <span class="text-primary">作成日時</span>

       </th>

        <td class="active">

          <?php print $administrator_host['created_at'] ?>

        </td>
      </tr>

      <tr>
        <th class="text-center info">

          <span class="text-primary">変更日時</span>

       </th>

        <td class="active">

          <?php print $administrator_host['modified_at'] ?>

        </td>

    </tbody>
  </table>
  </div>
  <input type="hidden"
         name="created_at"
         value="<?php print $administrator_host['created_at'] ?>" />

  <input type="hidden"
         name="modified_at"
         value="<?php print $administrator_host['modified_at'] ?>" />

  <input type="hidden"
         name="id"
         value="<?php print $administrator_host['id'] ?>" />

  <input type="hidden"
         name="_token"
         value="<?php print $this->escape($_token); ?>" />

</form>
