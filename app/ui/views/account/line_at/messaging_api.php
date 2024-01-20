<tr>

  <th class="text-center info">

    <span class="text-primary">

      Messaging API

    </span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/line_at/messaging_api/access_token',
              array(
                'width'   => 600,
                'account' => $account,
              )
            )
          )

        ?>

      </tbody>
    </table>

  </td>

</tr>
