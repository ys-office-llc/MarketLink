<tr>

  <th class="text-center info">

    <span class="text-primary">サービス状態</span>

  </th>

  <td class="active" style="width: <?php $width ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print $this->render(
            'potal/service_status/front_end',
            array(
              'width'     => 700,
              'interface' => $interface,
            )
          )

        ?>

        <?php

          print $this->render(
            'potal/service_status/back_end',
            array(
              'width'     => 700,
              'account'   => $account,
              'status'    => $status,
            )
          )

        ?>

      </tbody>
    </table>

  </td>

</tr>
