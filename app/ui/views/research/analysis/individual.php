<tr>

  <td class="text-center active">

    <input type="checkbox"
           name="id[<?php print $research_analysis['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <td class="text-center active">

    <a href="<?php print $base_url; ?>/<?php print $view_path ?>/get/<?php print $research_analysis['id'] ?>"><?php print $this->escape($research_analysis['name']); ?>
    </a>

  </td>

  <td class="text-center active">

<?php if ($research_analysis['action'] === 'chatwork'): ?>

    ChatWorkへ通知

<?php elseif ($research_analysis['action'] === 'database'): ?>

    データベースへ登録

<?php elseif ($research_analysis['action'] === 'all'): ?>

    すべて実行する

<?php elseif ($research_analysis['action'] === 'do_nothing'): ?>

    何もしない

<?php else: ?>

    何もしない

<?php endif; ?>

  </td>

</tr>
