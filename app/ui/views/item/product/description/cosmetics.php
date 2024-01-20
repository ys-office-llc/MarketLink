<tr>

  <th class="text-center info">

  <?php if ($item['description_id'] > 0): ?>

    <a href="<?php print $base_url; ?>/setting/item/description/cosmetics/get/<?php print $item['description_cosmetics_id'] ?>"
       target="_blank"
       class="btn btn-primary"
       style="width: 150px;">

      <span class="text-default">外観</span>

    </a>

  <?php else: ?>

    <span class="text-primary">外観</span>

  <?php endif; ?>

  </th>

  <td class="text-left active">

  <?php

    if (true) {

      print(
        $this->render('select',
          array(
            'name'     => 'description_cosmetics_id',
            'values'   => $table_values['item_description_cosmetics'],
            'selected' => $item['description_cosmetics_id'],
            'sort'     => 'asort',
          )
        )
      );
    }

  ?>

  </td>

</tr>
