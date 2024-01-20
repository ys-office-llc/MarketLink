<?php if ($item['yahoo_auctions_state_id'] == $state['waiting'] and
          strlen($item['yahoo_auctions_item_id']) === 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_reserve_add_item"

<?php   if ($prepare['basic'] and $prepare['yahoo_auctions']): ?>

           value="ヤフオク新規出品"

<?php    else: ?>

           value="ヤフオク新規出品（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

<?php elseif ($item['yahoo_auctions_state_id'] == $state['waiting'] and
              strlen($item['yahoo_auctions_item_id']) > 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_reserve_add_item"

<?php   if ($prepare['basic'] and $prepare['yahoo_auctions']): ?>

           value="ヤフオク新規出品"

<?php    else: ?>

           value="ヤフオク新規出品（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_reserve_resubmit_item"

<?php   if ($prepare['basic'] and $prepare['yahoo_auctions']): ?>

           value="ヤフオク再出品"

<?php    else: ?>

           value="ヤフオク再出品（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

<?php   elseif ($item['yahoo_auctions_state_id'] == $state['exhibit']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_reserve_revise_item"

<?php   if ($prepare['basic'] and $prepare['yahoo_auctions']): ?>

           value="ヤフオク修正"

<?php    else: ?>

           value="ヤフオク修正（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_reserve_end_item"

<?php   if ($prepare['basic'] and $prepare['yahoo_auctions']): ?>

           value="ヤフオク終了"

<?php    else: ?>

           value="ヤフオク終了（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

<?php   elseif ($item['yahoo_auctions_state_id'] == $state['selling']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_waiting_item"
           value="ヤフオク入庫" />

<?php   elseif ($item['yahoo_auctions_state_id'] == $state['payment']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="yahoo_auctions_waiting_item"
           value="ヤフオク入庫" />

<?php endif; ?>
