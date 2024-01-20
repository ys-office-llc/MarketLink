<?php $this->setPageTitle('title', 'CSVインポート') ?>

<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($successes) and count($successes) > 0): ?>

<?php print $this->render('successes', array('successes' => $successes)); ?>

<?php endif; ?>

<?php if (isset($errors) and count($errors) > 0): ?>

<?php print $this->render('errors', array('errors' => $errors)); ?>

<?php endif; ?>

<ol class="breadcrumb">

  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>データ取り込み</li>

  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>">

      CSVインポート

    </a>

  </li>

</ol>

<form action="<?php print $base_url.'/'.$view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <?php

    if (true) {

      print(
        $this->render(
          'import/submit',
          array(
            'import' => $import,
          )
        )
      );

    }

  ?>

<?php if (isset($import) and count($import) > 0): ?>

  <?php

    if (true) {

      print(
        $this->render(
          'import/reflection',
          array(
            'import' => $import,
          )
        )
      );

    }

  ?>

<?php else: ?>

  <?php

    if (true) {

      print(
        $this->render(
          'import/capture',
          array(
          )
        )
      );

    }

  ?>

<?php endif; ?>

<?php if (isset($_type)): ?>

  <input type="hidden"
         name="_type"
         value="<?php print $this->escape($_type); ?>" />

<?php endif; ?>

  <input type="hidden"
         name="_token"
         value="<?php print $this->escape($_token); ?>" />

</form>
