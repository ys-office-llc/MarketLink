<tr>

  <th class="text-center info">

    <span class="text-primary">権限</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <?php

      print(
        $this->render(
          'select',
          array(
            'name'     => 'account_authority_level_id',
            'values'   => $table_values['account_authority_level'],
            'selected' => $account['account_authority_level_id'],
          )
        )
      )

    ?>

  </td>
</tr>
