<tr>

  <th class="text-center info">

    <span class="text-primary">利用可否</span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

    <?php print $this->render('setting/account/prepare/yahoo/auctions',
                  array(
                    'account' => $account,
                    'prepare' => $prepare,
                  )); ?>

    <?php print $this->render('setting/account/prepare/ebay',
                  array(
                    'prepare' => $prepare,
                  )); ?>

    <?php print $this->render('setting/account/prepare/amazon',
                  array(
                    'prepare' => $prepare,
                  )); ?>

      </tbody>
    </table>

  </td>
</tr>
