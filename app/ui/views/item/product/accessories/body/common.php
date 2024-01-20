<tr>
  <th class="text-center info">
    <span class="text-primary">共通</span>
  </th>

  <td class="active" style="width: 500px">
    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="accessories[]"
               value="body_common_cap"
    <?php if (!is_null($item['accessories']) and
              in_array(
                'ボディキャップ',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        ボディキャップ
      </label>

      <label>
        <input type="checkbox"
               name="accessories[]"
               value="body_common_strap"
    <?php if (!is_null($item['accessories']) and
              in_array(
                'ストラップ',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        ストラップ
      </label>
    
    </div>

  </td>
</tr>
