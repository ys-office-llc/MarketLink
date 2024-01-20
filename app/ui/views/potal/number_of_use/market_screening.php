<?php if ($account['market_screening'] === 'enable'): ?>

<tr>

  <th class="text-center info">

    <span class="text-primary">相場スクリーニング</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

    <?php

      print $this->render(
        'potal/number_of_use/market_screening/yahoo/auctions/watch/list',
        array(
          'width'     => 500,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/number_of_use/market_screening/market/watch/list',
        array(
          'width'     => 500,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/number_of_use/market_screening/store/watch/list',
        array(
          'width'     => 500,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/number_of_use/market_screening/free/markets/watch/list',
        array(
          'width'     => 500,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/number_of_use/market_screening/yahoo/auctions/search',
        array(
          'width'     => 500,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/number_of_use/market_screening/market/search',
        array(
          'width'     => 500,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/number_of_use/market_screening/store/new/arrival',
        array(
          'width'     => 500,
          'account'   => $account,
          'resources' => $resources,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/number_of_use/market_screening/free/markets/search/list',
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
