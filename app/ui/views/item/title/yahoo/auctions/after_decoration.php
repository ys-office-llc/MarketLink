<tr>
  <th class="text-center info">
    <span class="text-primary">適用後</span>
  </th>

  <td class="active" style="width: 800px">

  <?php if ($size > 65): ?>

    <span style="color: #ff0000; font-weight: bold">

  <?php print $item['yahoo_auctions_product_name'] ?>

    </span>

  <?php else: ?>

    <?php print $item['yahoo_auctions_product_name'] ?>

  <?php endif; ?>

    <span class="text-muted" style="font-weight: bold">
      [<?php print $size ?>/65文字]
    </span>

  </td>
</tr>
