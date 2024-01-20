<?php

  $this->setPageTitle(
    'title',
    $account['user_name_ja'].' ポータル');

?>

<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($errors) and count($errors) > 0): ?>

<?php print $this->render('errors', array('errors' => $errors)); ?>

<?php endif; ?>

<ol class="breadcrumb">
  <li>
    <a class="active"
       href="<?php print $this->potal().$base_url; ?>">

      ポータル

    </a>
  </li>
</ol>

<div class="panel panel-primary">

  <div class="panel-heading">

    <span class="text-default">

      利用ユーザー&nbsp;<?php print $account['user_name_ja'] ?>様

    </span>

  </div>

  <div class="panel-body">

  <div class="table-responsive">

    <table class="table table-bordered table-condensed">
      <tbody>

    <?php

      if (true) {

        print(
          $this->render(
            'potal/prepare',
            array(
              'width'   => 900,
              'account' => $account,
              'prepare' => $account['prepare'],
            )
          )
        );

      }

    ?>

    <?php

      print $this->render(
        'potal/plan_in_use',
        array(
          'width'        => 900,
          'account'      => $account,
          'table_values' => $table_values,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/statistical_data_holding_period',
        array(
          'width'        => 900,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/number_of_use',
        array(
          'width'     => 900,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/service_status',
        array(
          'width'     => 900,
          'account'   => $account,
          'interface' => $interface,
          'status'    => $status,
        )
      )

    ?>

    </tbody>
  </table>

</div>
