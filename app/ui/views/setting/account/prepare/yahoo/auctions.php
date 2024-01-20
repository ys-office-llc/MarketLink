<tr>
  <th class="text-center info">
    <span class="text-primary">ヤフオク</span>
  </th>

  <td class="active" style="width: 800px">
    <table class="table table-bordered table-condensed">
      <tbody>

    <?php if ($account['merchandise_management'] === 'enable'): ?>

    <?php

      print $this->render(
        'setting/account/prepare/yahoo/auctions/seller',
        array(
          'prepare' => $prepare,
          )
      )

    ?>

    <?php endif; ?>

    <?php if ($account['market_screening'] === 'enable'): ?>

    <?php

      print $this->render(
        'setting/account/prepare/yahoo/auctions/buyer',
        array(
          'prepare' => $prepare,
        )
      )

    ?>

    <?php endif; ?>

      </tbody>
    </table>
  </td>
</tr>
