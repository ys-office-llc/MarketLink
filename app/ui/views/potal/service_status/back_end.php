<tr>

  <th class="text-center info">

    <span class="text-primary">バックエンド</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

    <?php

      print $this->render(
        'potal/service_status/back_end/authentication_settings',
        array(
          'width'  => 500,
          'status' => $status,
        )
      )

    ?>

  <?php if ($account['market_screening'] === 'enable'): ?>

    <?php

      print $this->render(
        'potal/service_status/back_end/synchronize_watch_list',
        array(
          'width'  => 500,
          'status' => $status,
        )
      )

    ?>

    <?php

      print $this->render(
        'potal/service_status/back_end/store_new_arrival_notice',
        array(
          'width'  => 500,
          'status' => $status,
        )
      )

    ?>

  <?php endif; ?>

  <?php if ($account['merchandise_management'] === 'enable'): ?>

    <?php

      print $this->render(
        'potal/service_status/back_end/market_link_manager',
        array(
          'width'  => 500,
          'status' => $status,
        )
      )

    ?>

  <?php endif; ?>

      </tbody>
    </table>

  </td>

</tr>
