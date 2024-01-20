<tr>

  <?php if ($state['shipment'] === $state_id):  ?>

  <td class="text-center default">

    <input type="checkbox"
           name="id[<?php print $item['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <?php endif; ?>

  <td class="text-center default">

  <?php if (strlen($item['thumbnail_image_01']) > 0): ?>

    <img src="<?php printf("%s/%s", $url, $item['thumbnail_image_01']) ?>" />

  <?php endif; ?>

  </td>

  <td class="text-center default">

    <?php print $this->escape($item['notes']); ?>

  </td>

  <td class="text-center default">

    <?php print $this->escape($item['stock_keeping_unit']); ?>

  </td>

  <td class="text-center default">

    <a href="<?php print $base_url; ?>/<?php print $view_path ?>/get/<?php print $item['id'] ?>"
       target="_blank">
      <?php print $this->escape($item['product_name']); ?>
    </a>

  </td>

  <td class="text-center default">

    <?php print '\\'.$this->escape(number_format($item['cost_price'])); ?>

  </td>

  <td class="text-center default">

  <?php if ((int)$item['yahoo_auctions_state_id'] === $state_id): ?>

    <strong>

  <?php   if (strlen($item['yahoo_auctions_url']) > 0): ?>

    <a href="<?php print $item['yahoo_auctions_url'] ?>"
       target="_blank">

  <?php   endif; ?>

      <?php print $this->escape(
                    $table_values['item_state'][$item[
                      'yahoo_auctions_state_id'
                    ]]['name']); ?>

  <?php   if ($state['shipment'] === $state_id and
              $item['yahoo_auctions_sale_price'] > 0): ?>

      （<span class="text-info">SOLD</span>）

  <?php   endif; ?>

  <?php   if (strlen($item['yahoo_auctions_url']) > 0): ?>

    </a>

  <?php   endif; ?>

    </strong>

  <?php else: ?>

    <span class="text-muted">

      <?php print $this->escape(
                    $table_values['item_state'][$item[
                      'yahoo_auctions_state_id'
                    ]]['name']); ?>
    </span>

  <?php endif; ?>

  </td>

  <td class="text-center default">

  <?php if ((int)$item['ebay_us_state_id'] === $state_id): ?>

    <strong>

  <?php   if (strlen($item['ebay_us_url']) > 0): ?>

    <a href="<?php print $item['ebay_us_url'] ?>"
       target="_blank">

  <?php   endif; ?>

      <?php print $this->escape(
                    $table_values['item_state'][$item[
                      'ebay_us_state_id'
                    ]]['name']); ?>

    <?php if ($state['shipment'] === $state_id and
              $item['ebay_us_sale_price'] > 0): ?>

      （<span class="text-info">SOLD</span>）

    <?php endif; ?>

  <?php   if (strlen($item['ebay_us_url']) > 0): ?>

    </a>

  <?php   endif; ?>

    </strong>

  <?php else: ?>

    <span class="text-muted">

      <?php print $this->escape(
                    $table_values['item_state'][$item[
                      'ebay_us_state_id'
                    ]]['name']); ?>

    </span>

  <?php endif; ?>

  </td>

  <td class="text-center default">

  <?php if ((int)$item['amazon_jp_state_id'] === $state_id): ?>

    <strong>

  <?php   if (strlen($item['amazon_jp_item_id']) > 0): ?>

    <a href="https://www.amazon.co.jp/gp/offer-listing/<?php print $item['amazon_jp_asin'] ?>/ref=dp_olp_used?ie=UTF8&condition=used"
       target="_blank">

  <?php   endif; ?>

      <?php print $this->escape(
                    $table_values['item_state'][$item[
                      'amazon_jp_state_id'
                    ]]['name']); ?>

    <?php if ($state['shipment'] === $state_id and
              $item['amazon_jp_sale_price'] > 0): ?>

      （<span class="text-info">SOLD</span>）

    <?php endif; ?>

  <?php   if (strlen($item['amazon_jp_item_id']) > 0): ?>

    </a>

  <?php   endif; ?>

    </strong>

  <?php else: ?>

    <span class="text-muted">

      <?php print $this->escape(
                    $table_values['item_state'][$item[
                      'amazon_jp_state_id'
                    ]]['name']); ?>

    </span>

  <?php endif; ?>

  </td>

<?php if ($state['waiting'] === $state_id): ?>

  <td class="text-center default">

    <?php print '\\'.$this->escape(
                       number_format(
                         $item['yahoo_auctions_start_price']
                     )); ?>

  </td>

  <td class="text-center default">

    <?php print '\\'.$this->escape(
                       number_format(
                         $item['yahoo_auctions_end_price']
                     )); ?>

  </td>

  <td class="text-center default">

    <?php print '\\'.$this->escape(
                       number_format(
                         $item['yahoo_auctions_reserve_price']
                     )); ?>

  </td>

  <td class="text-center default">

    <?php print $this->escape(
                  number_format(
                    $item['ebay_us_num_watch']
                )); ?>

  </td>

  <td class="text-center default">

    <?php print '$'.$this->escape(
                      number_format(
                        $item['ebay_us_start_price']
                    )); ?>

  </td>

  <td class="text-center default">

    <?php print '$'.$this->escape(
                       number_format(
                         $item['ebay_us_end_price']
                     )); ?>

  </td>

  <td class="text-center default">

    <?php print '\\'.$this->escape(
                       number_format(
                         $item['amazon_jp_price']
                     )); ?>

  </td>

<?php elseif ($state['exhibit'] === $state_id): ?>

  <td class="text-center default">

    <?php print $this->escape(
                  number_format(
                    $item['yahoo_auctions_num_watch']
                )); ?>

  </td>

  <td class="text-center default">

    <?php print '\\'.$this->escape(
                       number_format(
                         $item['yahoo_auctions_current_price']
                     )); ?>

  </td>

  <td class="text-center default">

    <?php print $this->escape(
                         $item['yahoo_auctions_time_left']
                       ); ?>

  </td>

  <td class="text-center default">

    <?php print $this->escape(
                  number_format(
                    $item['ebay_us_num_watch']
                )); ?>

  </td>

  <td class="text-center default">

    <?php print '$'.$this->escape(
                      number_format(
                        $item['ebay_us_end_price']
                    )); ?>

  </td>

  <td class="text-center default">

    <?php print $this->escape($item['ebay_us_time_left']); ?>

  </td>

  <td class="text-center default">

    <?php print '\\'.$this->escape(
                       number_format(
                         $item['amazon_jp_price']
                     )); ?>

  </td>

<?php elseif ($state['selling'] === $state_id or
              $state['payment'] === $state_id or
              $state['shipment'] === $state_id): ?>

  <?php if ((int)$item['yahoo_auctions_sale_price'] > 0): ?>

  <td class="text-center default">

    <?php print '\\'.$this->escape(
                       number_format(
                         $item['yahoo_auctions_sale_price']
                     )); ?>

  </td>

  <?php elseif ((int)$item['ebay_us_sale_price'] > 0): ?>

  <td class="text-center default">

    <?php print '$'.$this->escape(
                      number_format(
                        $item['ebay_us_sale_price']
                    )); ?>

  </td>

  <?php elseif ((int)$item['amazon_jp_sale_price'] > 0): ?>

  <td class="text-center default">

    <?php print '\\'.$this->escape(
                       number_format(
                         $item['amazon_jp_sale_price']
                     )); ?>

  </td>

  <?php else: ?>

  <td class="text-center default">

  </td>

  <?php endif; ?>

<?php endif; ?>

<?php if ($state['waiting'] === $state_id): ?>

  <td class="text-center default">

    <?php print $this->escape($item['created_at']); ?>

  </td>

<?php endif; ?>

<?php   if ($state['shipment'] === $state_id): ?>

  <td class="text-center default">

    <a href="https://trackings.post.japanpost.jp/services/srv/search/direct?searchKind=S004&locale=ja&reqCodeNo1=<?php print $this->escape($item['ems_tracking_number']) ?>&x=38&y=13"
       target="_blank">

      <?php print $this->escape($item['ems_tracking_number']); ?>

    </a>

  </td>

  <td class="text-center default">

    <?php print $this->escape($item['ems_acceptance_datetime']); ?>

  </td>

  <td class="text-center default">

    <?php print $this->escape($item['ems_arrival_datetime']); ?>

  </td>

  <td class="text-center default">

    <?php print $this->escape($item['ems_delivery_history']); ?>

  </td>

<?php endif; ?>

</tr>
