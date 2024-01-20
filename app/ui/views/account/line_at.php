<tr>

  <th class="text-center info">

    <span class="text-primary">LINE@</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/line_at/messaging_api',
              array(
                'width'   => 600,
                'account' => $account,
              )
            )
          )

        ?>

    </table>

  </td>

</tr>
