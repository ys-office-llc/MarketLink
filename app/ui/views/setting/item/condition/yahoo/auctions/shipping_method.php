<tr>

  <th class="text-center info">

    <span class="text-primary">配送方法</span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php
          print $this->render(
            'setting/item/condition/yahoo/auctions/shipping_method/yahuneko',
            array(
              'table_values' => $table_values,
              'condition'    => $condition,
            ));
         ?>

        <?php

          if (false) {

            print(
              $this->render(
                'setting/item/condition/yahoo/auctions/shipping_method/hacoboon',
                array(
                  'table_values' => $table_values,
                  'condition'    => $condition,
                )
              )
            );
          }

        ?>

        <?php

          if (false) {

            print(
              $this->render(
                'setting/item/condition/yahoo/auctions/shipping_method/hacoboonmini',
                array(
                  'table_values' => $table_values,
                  'condition'    => $condition,
                )
              )
            );
          }

         ?>

        <?php
          print $this->render(
            'setting/item/condition/yahoo/auctions/shipping_method/other',
            array(
              'table_values' => $table_values,
              'condition'    => $condition,
            ));
         ?>

      </tbody>
    </table>

  </td>

</tr>
