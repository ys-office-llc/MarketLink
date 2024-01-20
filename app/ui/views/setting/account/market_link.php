<tr>

  <th class="text-center info">

    <span class="text-primary">Market Link</span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">

      <tbody>

        <?php

          if (true) {

            print(
              $this->render(
                'setting/account/market_link/display/format',
                array(
                  'account' => $account,
                  'width'   => 900,
                )
              )
            );

          }

        ?>

        <?php

          if (true) {

            print(
              $this->render(
                'setting/account/market_link/user_name',
                array(
                  'account' => $account,
                  'width'   => 600,
                )
              )
            );

          }

        ?>

        <?php

          if (true) {

            print(
              $this->render(
                'setting/account/market_link/password',
                array(
                  'account' => $account,
                  'width'   => 600,
                )
              )
            );

          }

        ?>

        <?php

          if ($account['merchandise_management'] === 'enable') {

            print(
              $this->render(
                'setting/account/market_link/vacation',
                array(
                  'account' => $account,
                  'width'   => 600,
                )
              )
            );

          }

        ?>

      </tbody>
    </table>
  </td>

</tr>
