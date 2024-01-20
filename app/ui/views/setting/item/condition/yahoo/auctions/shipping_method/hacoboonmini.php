<tr>
  <th class="text-center info">
    <span class="text-primary">はこBOON mini</span>
  </th>

  <td class="active" style="width: 800px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <tr>
          <th class="text-center info">
            <span class="text-primary"></span>
          </th>

          <td class="active" style="width: 500px">

            <div class="checkbox">

              <label>
                <input type="checkbox"
                       name="hacoboonmini[]"
                       value="はこBOON mini"

    <?php if (!is_null($condition['hacoboonmini']) and
              in_array(
                'はこBOON mini',
                $condition['hacoboonmini'],
                true
              )): ?>

                       checked />

    <?php else: ?>

               />

    <?php endif; ?>

              </label>

            </div>
          </td>
        </tr>

        <tr>
          <th class="text-center info">
            <span class="text-primary">
              発送元店舗
            </span>
          </th>

          <td class="active" style="width: 500px">

            <?php print $this->render('select',
              array(
                'name'     => 'hacoboonmini_shipment_source_store_id',
                'values'   => $table_values['item_condition_yahoo_auctions_shipping_origin'],
                'selected' => $condition['hacoboonmini_shipment_source_store_id'],
              )); ?>

          </td>
        </tr>

      </tbody>
    </table>

  </td>
</tr>
