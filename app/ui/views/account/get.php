<?php $this->setPageTitle('title', 'ユーザー登録') ?>
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

  <?php if (isset($account['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $account['id']
              ?>">

      情報修正

    </a>

  <?php else: ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>">

      ユーザー登録

    </a>

  <?php endif; ?>

</ol>

<form action="<?php print($base_url) ?>/account/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php

      print(
        $this->render(
          'account/crudd',
          array(
            'param' => $account,
          )
        )
      )

    ?>

  </div>

  <div class="table-responsive">

  <table class="table display table-bordered">
    <tbody>

    <?php

      if (true) {

        print(
          $this->render(
            'account/use_experimental_function',
            array(
              'width'   => 900,
              'account' => $account,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'account/operation_mode',
            array(
              'width'   => 900,
              'account' => $account,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'account/contract_information',
            array(
              'width'               => 900,
              'account'             => $account,
              'table_values'        => $table_values,
              'administrator_hosts' => $administrator_hosts,
            )
          )
        );

      }

    ?>

    <?php

      print(
        $this->render(
          'account/basic_information',
          array(
            'width'   => 900,
            'account' => $account,
          )
        )
      )

    ?>

    <?php

      print(
        $this->render(
          'account/auxiliary_information',
          array(
            'width'   => 900,
            'account' => $account,
          )
        )
      )

    ?>
    <?php

      print(
        $this->render(
          'account/line_at',
          array(
            'width'   => 900,
            'account' => $account,
          )
        )
      )

    ?>

    <?php

      print(
        $this->render(
          'account/chatwork',
          array(
            'width'   => 900,
            'account' => $account,
          )
        )
      )

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'account/yahoo',
            array(
              'width'   => 900,
              'account' => $account,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'account/ebay',
            array(
              'width'   => 900,
              'account' => $account,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'account/amazon',
            array(
              'width'   => 900,
              'account' => $account,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'account/stores',
            array(
              'width'   => 900,
              'account' => $account,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'footer/management_information',
            array(
              'information' => $account,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'account/hidden',
            array(
              'account' => $account,
              '_token'  => $_token,
            )
          )
        );

      }

    ?>

</form>
