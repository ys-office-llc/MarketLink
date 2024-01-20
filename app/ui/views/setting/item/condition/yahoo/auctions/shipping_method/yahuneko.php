<tr>
  <th class="text-center info">
    <span class="text-primary">ヤフネコ！パック</span>
  </th>

  <td class="active" style="width: 800px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <tr>
          <th class="text-center info">
            <span class="text-primary">
              ネコポス（角形A4厚さ2.5cm以内）
            </span>
          </th>

          <td class="active" style="width: 500px">

            <div class="checkbox">

              <label>
                <input type="checkbox"
                       name="yahuneko[]"
                       value="ネコポス"

    <?php if (!is_null($condition['yahuneko']) and
              in_array(
                'ネコポス',
                $condition['yahuneko'],
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
              宅急便コンパクト
            </span>
          </th>

          <td class="active" style="width: 500px">

            <div class="checkbox">

              <label>
                <input type="checkbox"
                       name="yahuneko[]"
                       value="宅急便コンパクト"

    <?php if (!is_null($condition['yahuneko']) and
              in_array(
                '宅急便コンパクト',
                $condition['yahuneko'],
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
              宅急便（60～160サイズ）
            </span>
          </th>

          <td class="active" style="width: 500px">

            <table class="table table-bordered table-condensed">
              <tbody>

        <tr>
          <th class="text-center info">
            <span class="text-primary"></span>
          </th>

          <td class="active" style="width: 200px">

            <div class="checkbox">

              <label>
                <input type="checkbox"
                       name="yahuneko[]"
                       value="宅急便"

    <?php if (!is_null($condition['yahuneko']) and
              in_array(
                '宅急便',
                $condition['yahuneko'],
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

          <td class="active" style="width: 200px">

            <?php print $this->render('select',
              array(
                'name'     => 'yahuneko_total_lwh_id',
                'values'   => $table_values['item_condition_yahoo_auctions_yahuneko_total_lwh'],
                'selected' => $condition['yahuneko_total_lwh_id'],
              )); ?>

          </td>
        </tr>

        <tr>
          <th class="text-center info">
            <span class="text-primary">
               重さ（こん包後）：
            </span>
          </th>

          <td class="active" style="width: 200px">

            <?php print $this->render('select',
              array(
                'name'     => 'yahuneko_weight_id',
                'values'   => $table_values['item_condition_yahoo_auctions_yahuneko_weight'],
                'selected' => $condition['yahuneko_weight_id'],
              )); ?>

          </td>
        </tr>

      </tbody>
    </table>

  </td>
</tr>

      </tbody>
    </table>

  </td>
</tr>
