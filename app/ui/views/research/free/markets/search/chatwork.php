<tr>

  <th class="text-center info">

    <span class="text-primary">ChatWork宛先</span>

  </th>

  <td class="active">

    <div class="btn-group" data-toggle="buttons">

    <?php if ($research_free_markets_search['chatwork_to'] === 'grant'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="chatwork_to"
               value="grant"
               autocomplete="off"
        <?php if ($research_free_markets_search['chatwork_to'] === 'grant'): ?>
               checked />
        <?php else: ?>
               />
        <?php endif; ?>

        付与する
      </label>

    <?php if ($research_free_markets_search['chatwork_to'] === 'do_not_grant'): ?>

      <label class="btn btn-default active"
    <?php else: ?>

      <label class="btn btn-default"
    <?php endif; ?>
             style="width: 160px">

        <input type="radio"
               name="chatwork_to"
               value="do_not_grant"
               autocomplete="off"
        <?php if ($research_free_markets_search['chatwork_to'] === 'do_not_grant'): ?>
               checked />
        <?php else: ?>
               />
        <?php endif; ?>

        付与しない
      </label>

    </div>

  </td>
</tr>
