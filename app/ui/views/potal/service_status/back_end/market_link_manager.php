<tr>

  <th class="text-center info">

    <span class="text-primary">商品管理</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

    <div class="btn-group-vertical center-block">

  <?php if (count($status) > 0 and
           (int)$status[0]['market_link_manager'] === 0): ?>

      <span class="btn btn-success">稼働中</span>

  <?php else: ?>

      <span class="btn btn-danger">停止中</span>

  <?php endif; ?>

    </div>

  </td>

</tr>
