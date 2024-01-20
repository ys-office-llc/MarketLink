<tr>

  <th class="text-center info">

    <span class="text-primary">Amazon</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/amazon/japan',
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
