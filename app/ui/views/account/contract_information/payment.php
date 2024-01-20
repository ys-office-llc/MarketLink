<tr>

  <th class="text-center info">

    <span class="text-primary">課金</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <?php

      print(
        $this->render(
          'select',
          array(
            'name'     => 'account_is_payment_id',
            'values'   => $table_values['account_is_payment'],
            'selected' => $account['account_is_payment_id'],
          )
        )
      )

    ?>

  </td>
</tr>
