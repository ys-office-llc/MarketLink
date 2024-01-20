<tr>

  <th class="text-center info">

    <span class="text-primary">フロントエンド</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

    <div class="btn-group-vertical center-block">

    <?php if ((int)$interface[0]['user_interface'] === 1): ?>

      <span class="btn btn-success">稼働中</span>

    <?php else: ?>

      <span class="btn btn-warning">メンテナンス中</span>

    <?php endif; ?>

    </div>

  </td>

</tr>
