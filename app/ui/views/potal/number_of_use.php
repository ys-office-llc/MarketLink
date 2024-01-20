<tr>

  <th class="text-center info">

    <span class="text-primary">使用数</span>

  </th>

  <td class="active" style="width: <?php $width ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print $this->render(
            'potal/number_of_use/market_screening',
            array(
              'width'     => 700,
              'account'   => $account,
              'resources' => $resources,
            )
          )

        ?>

  <?php if ($this->getUserData()['account_contract_id'] > 2): ?>

    <?php

      print $this->render(
        'potal/number_of_use/bid_management',
        array(
          'width'     => 700,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

  <?php endif; ?>

    <?php

      print $this->render(
        'potal/number_of_use/merchandise_management',
        array(
          'width'     => 700,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

      </tbody>
    </table>

  </td>

</tr>
