<tr>

  <td class="text-center default">

    <?php print $account['id'] ?>

  </td>

  <td class="text-center default">

    <a href="<?php print $base_url; ?>/account/get/<?php print $account['id'] ?>">

      <?php print $this->escape($account['user_name_ja']); ?>

    </a>

  </td>

  <td class="text-center default">

    <?php

      print(
        $administrator_hosts[$account['accommodated_host_id']]['name']
      )

     ?>

  </td>

  <td class="text-center default">

<?php if ($account['prepare']['yahoo_auctions']['seller']): ?>

    <span class="btn btn-success">OK</span>

<?php else: ?>

    <span class="btn btn-danger">NG</span>

<?php endif; ?>

  </td>

  <td class="text-center default">

<?php if ($account['prepare']['yahoo_auctions']['buyer']): ?>

    <span class="btn btn-success">OK</span>

<?php else: ?>

    <span class="btn btn-danger">NG</span>

<?php endif; ?>

  </td>

  <td class="text-center default">

<?php if ($account['prepare']['ebay']): ?>

    <span class="btn btn-success">OK</span>

<?php else: ?>

    <span class="btn btn-danger">NG</span>

<?php endif; ?>

  </td>

  <td class="text-center default">

<?php if ($account['prepare']['amazon']['jp']): ?>

    <span class="btn btn-success">OK</span>

<?php else: ?>

    <span class="btn btn-danger">NG</span>

<?php endif; ?>

  </td>

  <td class="text-center default">

    <?php print $account['user_name'] ?>

  </td>

  <td class="text-center default">

    <?php print $account['yahoo_seller_account'] ?>

  </td>

  <td class="text-center default">

    <?php print $account['yahoo_buyer_account'] ?>

  </td>

</tr>
