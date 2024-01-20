<tr>

  <th class="text-center info">

    <span class="text-primary">

      Japan

    </span>

  </th>

  <td class="active"
      style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/amazon/japan/account',
              array(
                'width'   => 500,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/amazon/japan/password',
              array(
                'width'   => 500,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/amazon/japan/merchant_id',
              array(
                'width'   => 500,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/amazon/japan/marketplace_id',
              array(
                'width'   => 500,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/amazon/japan/access_key',
              array(
                'width'   => 500,
                'account' => $account,
              )
            )
          )

        ?>
        <?php

          print(
            $this->render(
              'account/amazon/japan/secret_key',
              array(
                'width'   => 500,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/amazon/japan/auth_token',
              array(
                'width'   => 500,
                'account' => $account,
              )
            )
          )

        ?>

      </tbody>
    </table>

  </td>

</tr>
