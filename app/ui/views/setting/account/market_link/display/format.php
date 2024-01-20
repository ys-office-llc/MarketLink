<tr>
  <th class="text-center info">
    <span class="text-primary">ナビメニュー表示形式</span>
  </th>

  <td class="active">

    <div class="btn-group" data-toggle="buttons">

    <?php if ($account['display_format'] === 'personal_computer'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 150px">

        <input type="radio"
               name="display_format"
               value="personal_computer"
               autocomplete="off"
    <?php if ($account['display_format'] === 'personal_computer'): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>

        パソコン用
      </label>

    <?php if ($account['display_format'] === 'smart_device'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 150px">

        <input type="radio"
               name="display_format"
               value="smart_device"
               autocomplete="off"
    <?php if ($account['display_format'] === 'smart_device'): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>

        スマートデバイス用
      </label>

    </div>

  </td>
</tr>
