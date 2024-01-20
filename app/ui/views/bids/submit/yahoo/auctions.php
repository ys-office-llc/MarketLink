<?php if ($item['yahoo_auctions_template_id'] > 0 and
          $item['yahoo_auctions_condition_id'] > 0 and
          $item['yahoo_auctions_start_price'] > 0 and
          $item['yahoo_auctions_end_price'] > 0): ?>

<?php   if ($item['yahoo_auctions_state_id'] == $state['waiting'] and
            strlen($item['yahoo_auctions_item_id']) === 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_reserve_add_item"
           value="ヤフオク出品" />

<?php   elseif ($item['yahoo_auctions_state_id'] == $state['waiting'] and
                strlen($item['yahoo_auctions_item_id']) > 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_reserve_resubmit_item"
           value="ヤフオク再出品" />

<?php   elseif ($item['yahoo_auctions_state_id'] == $state['exhibit']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_reserve_end_item"
           value="ヤフオク終了" />

<?php   endif; ?>
<?php endif; ?>
