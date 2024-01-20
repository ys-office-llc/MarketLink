<tr>

  <th class="text-center info">

    <span class="text-primary">パッケージ</span>

  </th>

  <td class="active"
      style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          if ((int)$account['account_contract_id'] === 1) {

            print(
              $this->render(
                'support/account/contract_information/market_screening',
                array(
                  'width'   => 800,
                  'account' => $account,
                )
              )
            );


            print(
              $this->render(
                'support/account/contract_information/merchandise_management',
                array(
                  'width'   => 800,
                  'account' => $account,
                )
              )
            );

          } else {

            print(
              $this->render(
                'support/account/contract_information/hidden',
                array(
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
