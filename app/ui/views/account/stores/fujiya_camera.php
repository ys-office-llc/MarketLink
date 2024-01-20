<tr>

  <th class="text-center info">

    <span class="text-primary">

      フジヤカメラ

    </span>

  </th>

  <td class="active"
      style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/stores/fujiya_camera/account',
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
              'account/stores/fujiya_camera/password',
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
