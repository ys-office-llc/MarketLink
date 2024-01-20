<?php if ($prepare['basic'] and $prepare['amazon_jp']): ?>

<?php   if ($item['amazon_jp_state_id'] == $state['waiting'] and
            strlen($item['amazon_jp_item_id']) === 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_reserve_add_item"
           value="Amazon.co.jp新規出品" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['waiting'] and
                strlen($item['amazon_jp_item_id']) > 0): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_reserve_resubmit_item"
           value="Amazon.co.jp再出品" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['exhibit']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_reserve_revise_item"
           value="Amazon.co.jp修正" />

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_reserve_end_item"
           value="Amazon.co.jp終了" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['selling']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_waiting_item"
           value="Amazon.co.jp入庫" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['payment']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_waiting_item"
           value="Amazon.co.jp入庫" />

<?php   endif; ?>

<?php else: ?>

<?php   if ($item['amazon_jp_state_id'] == $state['exclude']): ?>

    <input class="btn btn-primary"
           type="submit"
           name="amazon_jp_return_item"
           value="Amazon.co.jp復帰" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['waiting'] and
            strlen($item['amazon_jp_item_id']) === 0): ?>

    <input class="btn btn-primary"
           type="submit"
           name="amazon_jp_exclude_item"
           value="Amazon.co.jp除外" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['selling']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_waiting_item"
           value="Amazon.co.jp入庫" />

<?php   elseif ($item['amazon_jp_state_id'] == $state['payment']): ?>

    <input class="btn btn-primary confirm"
           type="submit"
           name="amazon_jp_waiting_item"
           value="Amazon.co.jp入庫" />

<?php   endif; ?>

<?php endif; ?>
