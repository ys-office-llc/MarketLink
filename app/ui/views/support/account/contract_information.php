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
              'support/account/contract_information/plan',
              array(
                'width'        => 800,
                'account'      => $account,
                'table_values' => $table_values,
              )
            )
          )

        ?>

        <?php

          if (true) {

            print(
              $this->render(
                'support/account/contract_information/packages',
                array(
                  'width'   => 800,
                  'account' => $account,
                )
              )
            );

          }

        ?>

      </tbody>
    </table>

  </td>

</tr>
