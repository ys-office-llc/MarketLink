<tr>

    <th class="text-center info">

    <?php if ($item['description_id'] > 0): ?>

      <a href="<?php print $base_url; ?>/setting/item/description/get/<?php print $item['description_id'] ?>"
         target="_blank"
         class="btn btn-primary"
         style="width: 150px;">

        <span class="text-default">カスタム</span>

      </a>

    <?php else: ?>

      <span class="text-primary">カスタム</span>

    <?php endif; ?>

    </th>

    <td class="text-left active">

    <?php

      if (true) {

        print(
          $this->render('select',
            array(
              'name'     => 'description_id',
              'values'   => $table_values['item_description'],
              'selected' => $item['description_id'],
              'sort'     => 'asort',
            )
          )
        );
      }

    ?>

</td>
