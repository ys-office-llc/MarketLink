<tr>

  <td class="text-center active">

    <input type="checkbox"
           name="id[<?php print $research_yahoo_auctions_search['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <td class="text-center active">
    <a href="<?php print $base_url; ?>/<?php print $view_path ?>/get/<?php print $research_yahoo_auctions_search['id'] ?>"><?php print $this->escape($research_yahoo_auctions_search['name']); ?>
    </a>
  </td>

  <td class="text-center active">

<?php if ($research_yahoo_auctions_search['action'] === 'chatwork'): ?>

    ChatWorkへ通知

<?php elseif ($research_yahoo_auctions_search['action'] === 'watchlist'): ?>

    ウォッチリストへ登録

<?php elseif ($research_yahoo_auctions_search['action'] === 'stockless'): ?>

    無在庫出品

<?php elseif ($research_yahoo_auctions_search['action'] === 'all'): ?>

    すべて実行する

<?php elseif ($research_yahoo_auctions_search['action'] === 'do_nothing'): ?>

    何もしない 

<?php else: ?>

    何もしない 

<?php endif; ?>

  </td>

</tr>
