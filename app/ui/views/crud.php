<?php if (isset($param['id'])): ?>

  <input class="btn btn-warning"
         type="submit"
         name="update"
         value="変更" />

  <input class="btn btn-danger"
         id="confirm_delete"
         type="submit"
         name="delete"
         value="削除" />

<?php else: ?>

  <input class="btn btn-success"
         type="submit"
         name="create"
         value="新規追加" />

<?php endif; ?>
