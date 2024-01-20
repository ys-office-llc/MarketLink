<tr>

  <td class="text-center active">

    <input type="checkbox"
           name="id[<?php print $research_new_arrival['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <td class="text-center active">

    <a href="<?php print $base_url; ?>/<?php print $view_path ?>/get/<?php print $research_new_arrival['id'] ?>"><?php print $this->escape($research_new_arrival['name']); ?>
    </a>
  </td>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_new_arrival['rank_kitamura']
                  )
                ) ?>

  </td>

<?php if ($this->getUserData()['account_authority_level_id'] > 1): ?>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_new_arrival['rank_fujiya_camera']
                  )
                ) ?>

  </td>

<?php endif; ?>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_new_arrival['rank_camera_no_naniwa']
                  )
                ) ?>

  </td>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_new_arrival['rank_map_camera']
                  )
                ) ?>

  </td>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_new_arrival['rank_champ_camera']
                  )
                ) ?>

  </td>

  <td class="text-center active">

    <?php print implode(
                  '/',
                  unserialize(
                    $research_new_arrival['rank_hardoff']
                  )
                ) ?>

  </td>

  <td class="text-center active">

    <?php print number_format($research_new_arrival['store_price']) ?>

  </td>

  <td class="text-center active">

<?php if ($research_new_arrival['action'] === 'chatwork'): ?>

    ChatWorkへ通知

<?php elseif ($research_new_arrival['action'] === 'database'): ?>

    データベースへ登録

<?php elseif ($research_new_arrival['action'] === 'all'): ?>

    すべて実行する

<?php elseif ($research_new_arrival['action'] === 'do_nothing'): ?>

    何もしない

<?php else: ?>

    何もしない

<?php endif; ?>

  </td>

  <td class="text-center active">

<?php if ($research_new_arrival['stock'] === 'existence'): ?>

    あり

<?php elseif ($research_new_arrival['stock'] === 'not_existence'): ?>

    なし

<?php elseif ($research_new_arrival['stock'] === 'all'): ?>

    すべて

<?php else: ?>

    すべて

<?php endif; ?>

  </td>

</tr>
