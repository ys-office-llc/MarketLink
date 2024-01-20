<?php if ($item['ebay_us_state_id'] == $state['waiting'] and
          strlen($item['ebay_us_item_id']) === 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_add_item"

<?php   if ($prepare['basic'] and $prepare['ebay_us']): ?>

           value="eBay新規出品"

<?php    else: ?>

           value="eBay新規出品（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

<?php elseif ($item['ebay_us_state_id'] == $state['waiting'] and
                strlen($item['ebay_us_item_id']) > 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_add_item"

<?php   if ($prepare['basic'] and $prepare['ebay_us']): ?>

           value="eBay新規出品"

<?php    else: ?>

           value="eBay新規出品（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_resubmit_item"

<?php   if ($prepare['basic'] and $prepare['ebay_us']): ?>

           value="eBay再出品"

<?php    else: ?>

           value="eBay再出品（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

<?php   elseif ($item['ebay_us_state_id'] == $state['exhibit']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_revise_item"

<?php   if ($prepare['basic'] and $prepare['ebay_us']): ?>

           value="eBay修正"

<?php    else: ?>

           value="eBay修正（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_reserve_end_item"

<?php   if ($prepare['basic'] and $prepare['ebay_us']): ?>

           value="eBay終了"

<?php    else: ?>

           value="eBay終了（設定していない項目があります）"
           disabled="disabled"

<?php    endif; ?>

    />

<?php   elseif ($item['ebay_us_state_id'] == $state['selling']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_waiting_item"
           value="eBay入庫" />

<?php   elseif ($item['ebay_us_state_id'] == $state['payment']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="ebay_us_waiting_item"
           value="eBay入庫" />

<?php endif; ?>
