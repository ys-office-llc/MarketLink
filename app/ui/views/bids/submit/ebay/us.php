<?php if ($item['ebay_us_template_id'] > 0 and
          $item['ebay_us_condition_id'] > 0 and
          $item['ebay_us_start_price'] > 0 and
          $item['ebay_us_end_price'] > 0): ?>

<?php   if ($item['ebay_us_state_id'] == $state['waiting'] and
            strlen($item['ebay_us_item_id']) === 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_add_item"
           value="eBay US出品" />

<?php   elseif ($item['ebay_us_state_id'] == $state['waiting'] and
                strlen($item['ebay_us_item_id']) > 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_resubmit_item"
           value="eBay US再出品" />

<?php   elseif ($item['ebay_us_state_id'] == $state['exhibit']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_end_item"
           value="eBay US終了" />

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_revise_item"
           value="eBay US情報修正" />

<?php   elseif ($item['ebay_us_state_id'] == $state['payment']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_shipment_item"
           value="eBay US出荷" />

<?php   endif; ?>

<?php endif; ?>
