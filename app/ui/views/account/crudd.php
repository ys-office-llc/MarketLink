<?php if (isset($param['id'])): ?>

<?php   if ((int)$param['chatwork_delete_rooms'] === 0): ?>

<?php     if (strlen($param['chatwork_room1_id']) > 0 or
              strlen($param['chatwork_room2_id']) > 0 or
              strlen($param['chatwork_room3_id']) > 0 or
              strlen($param['chatwork_room4_id']) > 0 or
              strlen($param['chatwork_room5_id']) > 0): ?>

  <input class="btn btn-danger"
         id="confirm_delete"
         type="submit"
         name="chatwork_delete_rooms"
         value="ChatWorkルーム削除" />

<?php     else: ?>

  <input class="btn btn-success confirm"
         type="submit"
         name="duplicate"
         value="複製" />

  <input class="btn btn-danger"
         id="confirm_delete"
         type="submit"
         name="delete"
         value="削除" />

<?php     endif; ?>

<?php   endif; ?>

  <input class="btn btn-warning"
         type="submit"

<?php  if ((int)$param['chatwork_delete_rooms'] === 1): ?>

         disabled="disabled"
         value="変更（ChatWorkルーム削除実行中は【変更】できません）"

<?php  endif ?>

         value="変更"
         name="update" />


<?php else: ?>

  <input class="btn btn-success"
         type="submit"
         name="create"
         value="新規追加" />

<?php endif; ?>
