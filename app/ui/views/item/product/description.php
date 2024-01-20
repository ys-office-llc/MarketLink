<tr>

  <th class="text-center info">

    <span class="text-primary">説明文</span>

  </th>

  <td class="active" style="width: 800px">

    <table class="table table-bordered table-condensed">

      <tbody>

      <?php

        if (true) {

          print(
            $this->render('item/product/description/custom',
              array(
                'item' => $item,
                'table_values' => $table_values,
              )
            )
          );

        }

      ?>

      <?php

        if (true) {

          print(
            $this->render('item/product/description/cosmetics',
              array(
                'item' => $item,
                'table_values' => $table_values,
              )
            )
          );

        }

      ?>

      <?php

        if (true) {

          print(
            $this->render('item/product/description/optics',
              array(
                'item' => $item,
                'table_values' => $table_values,
              )
            )
          );

        }

      ?>

      <?php

        if (true) {

          print(
            $this->render('item/product/description/functions',
              array(
                'item' => $item,
                'table_values' => $table_values,
              )
            )
          );

        }

      ?>

      </tbody>

    </table>

  </td>
</tr>
