<tr>

  <th class="text-center info">

    <span class="text-primary">在庫</span>

  </th>

  <td class="active">

    <div class="btn-group" data-toggle="buttons">

    <?php if ($research_free_markets_search['stock'] === 'existence'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="stock"
               value="existence"
               autocomplete="off"
        <?php if ($research_free_markets_search['chatwork_to'] === 'existence'): ?>
               checked />
        <?php else: ?>
             />
        <?php endif; ?>

        あり
      </label>

    <?php if ($research_free_markets_search['stock'] === 'not_existence'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="stock"
               value="not_existence"
               autocomplete="off"
        <?php if ($research_free_markets_search['stock'] === 'not_existence'): ?>
               checked />
        <?php else: ?>
               />
        <?php endif; ?>

        なし
      </label>

    <?php if ($research_free_markets_search['stock'] === 'all'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="stock"
               value="all"
               autocomplete="off"
        <?php if ($research_free_markets_search['stock'] === 'all'): ?>
               checked />
        <?php else: ?>
               />
        <?php endif; ?>

        すべて
      </label>

    </div>

  </td>
</tr>
