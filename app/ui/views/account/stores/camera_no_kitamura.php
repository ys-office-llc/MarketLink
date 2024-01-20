<tr>

  <th class="text-center info">

    <span class="text-primary">

      カメラのキタムラ

    </span>

  </th>

  <td class="active"
      style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/stores/camera_no_kitamura/account',
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
              'account/stores/camera_no_kitamura/password',
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
              'account/stores/camera_no_kitamura/shopcode',
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
