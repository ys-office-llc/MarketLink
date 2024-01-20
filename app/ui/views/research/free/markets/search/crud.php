<?php if (isset($param['id'])): ?>

  <input class="btn btn-warning"
         type="submit"
         name="update"
         value="変更" />

  <input class="btn btn-primary confirm"
         type="submit"
         name="delete"
         value="削除" />

<?php else: ?>

  <input class="btn btn-success"
         type="submit"
         name="create"

<?php if ($this->getReached()['research_new_arrival']): ?>

         disabled="disabled"

<?php endif; ?>

         value="新規追加" />

<?php endif; ?>
