<?php if (isset($param['id'])): ?>

  <input class="btn btn-warning"
         type="submit"
         name="update"
         value="変更" />

  <input class="btn btn-success confirm"
         type="submit"
         name="duplicate"
         value="複製" />

  <input class="btn btn-danger"
         id="confirm_delete"
         type="submit"
         name="delete"
         value="削除" />

<?php else: ?>

  <input class="btn btn-success"
         type="submit"
         name="create"

<?php if ($this->getReached()['research_analysis']): ?>

         disabled="disabled"

<?php endif; ?>

         value="新規追加" />

<?php   if (strlen($go_update) > 0): ?>

  <a href="<?php print $base_url.'/'.$go_update?>"
     class="btn btn-warning"
     target="_self">

    追加した商品を引き続き編集する

  </a>

<?php   endif; ?>

<?php endif; ?>
