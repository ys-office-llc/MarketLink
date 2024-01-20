<tr>
  <th class="text-center info">
    <span class="text-primary">共通</span>
  </th>

  <td class="active" style="width: 700px">
    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="accessories[]"
               value="common_manual"
    <?php if (!is_null($item['accessories']) and
              in_array(
                '説明書',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        説明書
      </label>
    
      <label>
        <input type="checkbox"
               name="accessories[]"
               value="common_box"
    <?php if (!is_null($item['accessories']) and
              in_array(
                '元箱',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        元箱
      </label>

    </div>

  </td>
</tr>
