<?php if ($waiting and in_array(true, $prepare) and !$reserved): ?>

  <input class="btn btn-primary confirm"
         type="submit"
         name="all_reserve_add_item"
         value="全販路一括新規出品" />

<?php endif; ?>

<?php if ($exhibit and !$reserved): ?>

  <input class="btn btn-primary confirm"
         type="submit"
         name="all_reserve_end_item"
         value="全販路一括終了" />

<?php endif; ?>
