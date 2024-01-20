<?php if ($account['market_screening'] === 'enable'): ?>

<tr>

  <th class="text-center info">

    <span class="text-primary">入札管理</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

    <?php

      print $this->render(
        'potal/number_of_use/bid_management/bid_reservation',
        array(
          'width'     => 500,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

      </tbody>
    </table>

  </td>
</tr>

<?php endif; ?>
