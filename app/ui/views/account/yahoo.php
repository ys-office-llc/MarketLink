<tr>

  <th class="text-center info">

    <span class="text-primary">Yahoo!</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/yahoo/seller',
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
              'account/yahoo/buyer',
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
