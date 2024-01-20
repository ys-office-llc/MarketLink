<tr>
  <th class="text-center info">
    <span class="text-primary">デジタル</span>
  </th>

  <td class="active" style="width: 500px">
    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="accessories[]"
               value="body_digital_software"
    <?php if (!is_null($item['accessories']) and
              in_array(
                'body_digital_software',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        ソフトウェア（CD/DVD-ROM）
      </label>

      <label>
        <input type="checkbox"
               name="accessories[]"
               value="body_digital_charger"
    <?php if (!is_null($item['accessories']) and
              in_array(
                'body_digital_charger',
                $item['accessories'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        充電器
      </label>
    
    </div>

  </td>
</tr>
