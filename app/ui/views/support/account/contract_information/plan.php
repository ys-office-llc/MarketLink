<tr>

  <th class="text-center info">

    <span class="text-primary">プラン</span>

  </th>

  <td class="active"
      style="width: <?php print($width) ?>px">

    <?php

      print(
        $this->render(
          'select',
          array(
            'name'     => 'account_contract_id',
            'values'   => $table_values['account_contract'],
            'selected' => $account['account_contract_id'],
          )
        )
      )

    ?>

  </td>
</tr>
