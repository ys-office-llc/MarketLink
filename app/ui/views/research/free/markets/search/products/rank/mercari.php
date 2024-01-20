<tr>
  <th class="text-center info">

    <span class="text-primary">メルカリ</span>

  </th>

  <td class="active" style="width: 600px">

    <div class="checkbox">

      <label>
        <input type="checkbox"
               name="rank_mercari[]"
               value="新品、未使用"
    <?php if (!is_null($research_free_markets_search['rank_mercari']) and
              in_array(
                '新品、未使用',
                $research_free_markets_search['rank_mercari'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        新品、未使用 /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_mercari[]"
               value="未使用に近い"
    <?php if (!is_null($research_free_markets_search['rank_mercari']) and
              in_array(
                '未使用に近い',
                $research_free_markets_search['rank_mercari'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        未使用に近い /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_mercari[]"
               value="目立った傷や汚れなし"
    <?php if (!is_null($research_free_markets_search['rank_mercari']) and
              in_array(
                '目立った傷や汚れなし',
                $research_free_markets_search['rank_mercari'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        目立った傷や汚れなし /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_mercari[]"
               value="やや傷や汚れあり"
    <?php if (!is_null($research_free_markets_search['rank_mercari']) and
              in_array(
                'やや傷や汚れあり',
                $research_free_markets_search['rank_mercari'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        やや傷や汚れあり /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_mercari[]"
               value="傷や汚れあり"
    <?php if (!is_null($research_free_markets_search['rank_mercari']) and
              in_array(
                '傷や汚れあり',
                $research_free_markets_search['rank_mercari'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        傷や汚れあり /
      </label>
    
      <label>
        <input type="checkbox"
               name="rank_mercari[]"
               value="全体的に状態が悪い"
    <?php if (!is_null($research_free_markets_search['rank_mercari']) and
              in_array(
                '全体的に状態が悪い',
                $research_free_markets_search['rank_mercari'],
                true
              )): ?>
               checked />
    <?php else: ?>
               />
    <?php endif; ?>
        全体的に状態が悪い
      </label>
    
    </div>

  </td>
</tr>
