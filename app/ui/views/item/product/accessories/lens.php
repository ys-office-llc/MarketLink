<tr>
  <th class="text-center info">
    <span class="text-primary">レンズ</span>
  </th>

  <td class="active" style="width: 700px">
    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="accessories[]"
               value="lens_front_cap"
    <?php if (!is_null($item['accessories']) and
              in_array(
                'フロントキャップ',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        フロントキャップ
      </label>
    
      <label>
        <input type="checkbox"
               name="accessories[]"
               value="lens_rear_cap"
    <?php if (!is_null($item['accessories']) and
              in_array(
                'リアキャップ',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        リアキャップ
      </label>

      <label>
        <input type="checkbox"
               name="accessories[]"
               value="lens_filter"
    <?php if (!is_null($item['accessories']) and
              in_array(
                'フィルター',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        フィルター
      </label>
    
    </div>

  </td>
</tr>
