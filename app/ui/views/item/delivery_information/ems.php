<tr>

  <th class="text-center info">

    <span class="text-primary">

      追跡番号

    </span>

  </th>

  <td class="active" style="width: 700px">

  <?php if ($payment): ?>

    <input type="text"
           name="tracking_number"
           value="<?php print $item['tracking_number'] ?>"
           size="16"/>

  <?php else: ?>

    <?php print $item['tracking_number'] ?>

    <input type="hidden"
           name="tracking_number"
           value="<?php print $item['tracking_number'] ?>" />

  <?php endif; ?>

  </td>

</tr>
