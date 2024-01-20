<?php if (isset($param['id'])): ?>

<?php   if (!$reserved): ?>

  <input class="btn btn-warning"
         type="submit"
         name="update"
         value="変更" />

<?php     if ($waiting or $shipment): ?>

  <input class="btn btn-danger"
         id="confirm_delete"
         type="submit"
         name="delete"
         value="削除" />

<?php     else: ?>

  <input class="btn btn-danger"
         type="submit"
         disabled="disabled"
         name="delete"
         value="削除（全販路で【入庫】【出庫】以外は削除できません）" />

<?php     endif; ?>

<?php     if ($shipment): ?>

  <input class="btn btn-success confirm"
         type="submit"
         name="duplicate"

<?php if ($this->getReached()['counter']): ?>

         disabled="disabled"

<?php endif; ?>

         value="複製" />

<?php     endif; ?>

<?php   else: ?>

  <input class="btn btn-warning"
         type="submit"
         disabled="disabled"
         name="update"
         value="変更（予約中の時は【変更】できません）" />

<?php     if (!$waiting or !$shipment): ?>

  <input class="btn btn-danger"
         type="submit"
         disabled="disabled"
         name="delete"
         value="削除（全販路で【入庫】【出庫】以外は削除できません）" />

<?php     endif; ?>

<?php   endif; ?>

<?php else: ?>

  <input class="btn btn-success"
         type="submit"
         name="create"

<?php if ($this->getReached()['counter']): ?>

         disabled="disabled"
         value="新規追加（商品登録数上限を超えています。【入庫】か【出庫】状態の商品を削除してください。）"

<?php else: ?>

         value="新規追加"

<?php endif; ?>

  />

<?php   if (strlen($go_update) > 0): ?>

  <a href="<?php print $base_url; ?>/<?php print $go_update?>"
     class="btn btn-warning"
     target="_self">

    追加した商品を引き続き編集する

  </a>

<?php   endif; ?>

<?php endif; ?>
