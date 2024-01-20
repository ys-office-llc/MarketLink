<tr>

  <td class="text-center active">

    <input type="checkbox"
           name="id[<?php print $research_free_markets_search['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <td class="text-center active">

    <a href="<?php print $base_url; ?>/<?php print $view_path ?>/get/<?php print $research_free_markets_search['id'] ?>"><?php print $this->escape($research_free_markets_search['name']); ?>
    </a>
  </td>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_free_markets_search['rank_mercari']
                  )
                ) ?>

  </td>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_free_markets_search['rank_rakuma']
                  )
                ) ?>

  </td>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_free_markets_search['rank_map_fril']
                  )
                ) ?>

  </td>

  <td class="text-center active">

<?php if ($research_free_markets_search['action'] === 'chatwork'): ?>

    ChatWorkへ通知

<?php elseif ($research_free_markets_search['action'] === 'database'): ?>

    データベースへ登録

<?php elseif ($research_free_markets_search['action'] === 'all'): ?>

    すべて実行する

<?php elseif ($research_free_markets_search['action'] === 'do_nothing'): ?>

    何もしない

<?php else: ?>

    何もしない

<?php endif; ?>

  </td>

  <td class="text-center active">

<?php if ($research_free_markets_search['stock'] === 'existence'): ?>

    あり

<?php elseif ($research_free_markets_search['stock'] === 'not_existence'): ?>

    なし

<?php elseif ($research_free_markets_search['stock'] === 'all'): ?>

    すべて

<?php else: ?>

    すべて

<?php endif; ?>

  </td>

</tr>
