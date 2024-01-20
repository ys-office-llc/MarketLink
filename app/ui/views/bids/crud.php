<?php if (isset($param['id'])): ?>

<!--
  <input class="btn btn-warning"
         type="submit"
         name="update"
         value="変更" />
-->

  <input class="btn btn-primary confirm"
         type="submit"
         name="delete"
         value="削除" />

<?php else: ?>

  <?php if ($to_close): ?>

    <a href="javascript:window.open('about:blank','_self').close();"
       class="btn btn-primary">
      ページを閉じる
    </a>

  <?php else: ?>

    <input class="btn btn-primary"
           type="submit"
           name="create"
           value="新規追加" />

  <?php endif; ?>

<?php endif; ?>
