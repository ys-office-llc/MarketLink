<?php if (strlen($item['yahoo_auctions_product_name']) > 0 or
          strlen($item['ebay_us_product_name']) > 0): ?>

<tr>
  <th class="text-center info">
    <span class="text-primary">タイトル</span>
  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

      <?php if (strlen($item['yahoo_auctions_product_name']) > 0): ?>

        <?php print $this->render('item/title/yahoo/auctions',
                      array(
                        'item' => $item,
                        'size' => $yahoo_auctions_product_name_size,
                      )
              ); ?>

      <?php endif; ?>

      <?php if (strlen($item['ebay_us_product_name']) > 0): ?>

        <?php print $this->render('item/title/ebay/com',
                      array(
                        'item' => $item,
                        'size' => $ebay_com_product_name_size,
                      )
              ); ?>

      <?php endif; ?>

      <?php if (strlen($item['amazon_jp_product_name']) > 0): ?>

        <?php print $this->render('item/title/amazon/jp',
                      array(
                        'item' => $item,
                      )
              ); ?>

      <?php endif; ?>

      </tbody>
    </table>
  </td>
</tr>

<?php endif; ?>
