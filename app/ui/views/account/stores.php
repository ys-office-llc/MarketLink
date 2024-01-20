<tr>

  <th class="text-center info">

    <span class="text-primary">ネットショップ</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/stores/camera_no_kitamura',
              array(
                'width'   => 700,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/stores/fujiya_camera',
              array(
                'width'   => 700,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/stores/map_camera',
              array(
                'width'   => 700,
                'account' => $account,
              )
            )
          )

        ?>

      </tbody>
    </table>

  </td>

</tr>
