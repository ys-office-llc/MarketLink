<tr>

  <th class="text-center info">

    <span class="text-primary">その他の配送サービス</span>

  </th>

  <td class="active"
      style="width: 800px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <tr>
          <th class="text-center info">
            <span class="text-primary"></span>
          </th>

          <td class="active" style="width: 500px">

            <?php print $this->render('select',
              array(
                'name'     => 'shipname_standard_id',
                'values'   => $table_values['item_condition_yahoo_auctions_shipname_standard'],
                'selected' => $condition['shipname_standard_id'],
              )); ?>

          </td>
        </tr>

        <tr>
          <th class="text-center info">

            <span class="text-primary">

              配送料金

            </span>
          </th>

          <td class="active">

            <input type="text"
                   name="delivery_cost"
                   value="<?php print $condition['delivery_cost'] ?>"
                   size="8" />円

          </td>
        </tr>

        <tr>
          <th class="text-center info">

            <span class="text-primary">

              配送追加料金

            </span>
          </th>

          <td class="active">

            <input type="text"
                   name="delivery_additional_cost"
                   value="<?php print $condition['delivery_additional_cost'] ?>"
                   size="8" />円

          </td>
        </tr>

      </tbody>
    </table>

  </td>
</tr>
