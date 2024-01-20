<tr>

  <th class="text-center info">

  <?php if ($item['description_id'] > 0): ?>

    <a href="<?php print $base_url; ?>/setting/item/description/functions/get/<?php print $item['description_functions_id'] ?>"
       target="_blank"
       class="btn btn-primary"
       style="width: 150px;">

      <span class="text-default">機能</span>

    </a>

  <?php else: ?>

    <span class="text-primary">機能</span>

  <?php endif; ?>

  </th>

  <td class="text-left active">

  <?php

    if (true) {

      print(
        $this->render('select',
          array(
            'name'     => 'description_functions_id',
            'values'   => $table_values['item_description_functions'],
            'selected' => $item['description_functions_id'],
            'sort'     => 'asort',
          )
        )
      );
    }

  ?>

  </td>

</tr>
