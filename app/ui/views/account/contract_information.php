<tr>

  <th class="text-center info">

    <span class="text-primary">契約情報</span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/contract_information/market_screening',
              array(
                'width'     => 700,
                'account'   => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/contract_information/merchandise_management',
              array(
                'width'     => 700,
                'account'   => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/contract_information/plan',
              array(
                'width'        => 600,
                'account'      => $account,
                'table_values' => $table_values,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/contract_information/authority',
              array(
                'width'        => 600,
                'account'      => $account,
                'table_values' => $table_values,
              )
            )
          )

        ?>

        <?php

          print $this->render(
            'account/contract_information/payment',
            array(
              'width'        => 600,
              'account'      => $account,
              'table_values' => $table_values,
            )
          )

        ?>

        <?php

          print $this->render(
            'account/contract_information/accommodated_host',
            array(
              'width'               => 600,
              'account'             => $account,
              'administrator_hosts' => $administrator_hosts,
            )
          )

        ?>

      </tbody>
    </table>

  </td>

</tr>
