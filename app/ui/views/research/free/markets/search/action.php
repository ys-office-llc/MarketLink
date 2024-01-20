<tr>

  <th class="text-center info">

    <span class="text-primary">

      アクション

    </span>

  </th>

  <td class="active">

    <div class="btn-group" data-toggle="buttons">

    <?php if ($research_free_markets_search['action'] === 'chatwork'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="action"
               value="chatwork"
               autocomplete="off"
        <?php if ($research_free_markets_search['action'] === 'chatwork'): ?>
               checked />
        <?php else: ?>
               />
        <?php endif; ?>

        ChatWorkへ通知
      </label>

    <?php if ($research_free_markets_search['action'] === 'database'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="action"
               value="database"
               autocomplete="off"
        <?php if ($research_free_markets_search['action'] === 'database'): ?>
               checked />
        <?php else: ?>
               />
        <?php endif; ?>

        データベースへ登録
      </label>

    <?php if ($research_free_markets_search['action'] === 'all'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="action"
               value="all"
               autocomplete="off"
        <?php if ($research_free_markets_search['action'] === 'all'): ?>
               checked />
        <?php else: ?>
               />
        <?php endif; ?>

        すべて実行する
      </label>

    <?php if ($research_free_markets_search['action'] === 'do_nothing'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="action"
               value="do_nothing"
               autocomplete="off"
    <?php if ($research_free_markets_search['action'] === 'do_nothing'): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>

        何もしない
      </label>

    </div>
  </td>
</tr>
