<tr>

  <th class="text-center info">

    <span class="text-primary">

      仕入用

    </span>

  </th>

  <td class="active"
      style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/yahoo/buyer/account',
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
              'account/yahoo/buyer/password',
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
              'account/yahoo/buyer/appid',
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
              'account/yahoo/buyer/secret',
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
