<tr>

  <th class="text-center info">

    <span class="text-primary">収容ホスト</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <?php

      print(
        $this->render(
          'select',
          array(
            'name'     => 'accommodated_host_id',
            'values'   => $administrator_hosts,
            'selected' => $account['accommodated_host_id'],
          )
        )
      )

    ?>

  </td>
</tr>
