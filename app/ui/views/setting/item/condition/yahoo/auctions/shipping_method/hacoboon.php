<tr>
  <th class="text-center info">
    <span class="text-primary">はこBOON</span>
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
                       name="hacoboon[]"
                       value="はこBOON"

    <?php if (!is_null($condition['hacoboon']) and
              in_array(
                'はこBOON',
                $condition['hacoboon'],
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
              縦、横、高さの合計（こん包後）：
            </span>
          </th>

          <td class="active" style="width: 500px">

            <?php print $this->render('select',
              array(
                'name'     => 'hacoboon_total_lwh_id',
                'values'   => $table_values['item_condition_yahoo_auctions_hacoboon_total_lwh'],
                'selected' => $condition['hacoboon_total_lwh_id'],
              )); ?>

          </td>
        </tr>

        <tr>
          <th class="text-center info">
            <span class="text-primary">
               重さ（こん包後）：
            </span>
          </th>

          <td class="active" style="width: 500px">

            <?php print $this->render('select',
              array(
                'name'     => 'hacoboon_weight_id',
                'values'   => $table_values['item_condition_yahoo_auctions_hacoboon_weight'],
                'selected' => $condition['hacoboon_weight_id'],
              )); ?>

          </td>
        </tr>

      </tbody>
    </table>

  </td>
</tr>
