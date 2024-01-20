<?php $this->setPageTitle('title', '契約情報') ?>

<?php print $this->render('nav', array('' => array())); ?>

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
  <li>サポート</li>
  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $account['id']
              ?>"
       target="_self">

      契約管理

    </a>

  </li>

</ol>

<form action="<?php
        printf(
          "%s/%s/post",
          $base_url,
          $view_path
        )
      ?>"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <input class="btn btn-primary"
           id="confirm_migration_plans"
           type="submit"

    <?php if ((int)$in_progress['migration'] === 1 or
              $is_migration['plans'] or
              $account['operation_mode'] === 'demonstration'): ?>

           disabled="disabled"

    <?php endif; ?>

           name="migration_plans"
           value="プランを切り替える" />

  <?php if ((int)$account['account_contract_id'] === 1): ?>

    <input class="btn btn-primary"
           id="confirm_migration_packages"
           type="submit"

    <?php if ((int)$in_progress['migration'] === 1 or
              $is_migration['packages'] or
              $account['operation_mode'] === 'demonstration'): ?>

           disabled="disabled"

    <?php endif; ?>

           name="migration_packages"
           value="パッケージを切り替える（ライトプランのみ）" />

  <?php endif; ?>

  </div>

  <div class="table-responsive">

    <table class="table display table-bordered">
      <tbody>

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
              'support/account/contract_information',
              array(
                'width'        => 800,
                'account'      => $account,
                'table_values' => $table_values,
                'in_progress'  => $in_progress,
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

      </tbody>
    </table>

  </div>

  <?php

    if (true) {

      print(
        $this->render(
          'support/account/hidden',
          array(
            'account' => $account,
            '_token'   => $_token,
          )
        )
      );

    }

  ?>

</form>
