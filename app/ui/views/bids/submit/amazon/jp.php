<?php if ($item['amazon_jp_template_id'] > 0 and
          strlen($item['amazon_jp_asin']) > 0 and
          $item['amazon_jp_price'] > 0): ?>

<?php   if ($item['amazon_jp_state_id'] == $state['exclude']): ?>

    <input class="btn btn-primary"
           type="submit"
           name="amazon_jp_return_item"
           value="Amazon.co.jp復帰" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['waiting'] and
            strlen($item['amazon_jp_item_id']) === 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_reserve_add_item"
           value="Amazon.co.jp出品" />

    <input class="btn btn-primary"
           type="submit"
           name="amazon_jp_exclude_item"
           value="Amazon.co.jp除外" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['waiting'] and
                strlen($item['amazon_jp_item_id']) > 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_reserve_resubmit_item"
           value="Amazon.co.jp再出品" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['exhibit']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_reserve_end_item"
           value="Amazon.co.jp終了" />

<?php   endif; ?>
<?php endif; ?>
