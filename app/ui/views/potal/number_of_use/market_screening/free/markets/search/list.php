<tr>

  <th class="text-center info">

    <span class="text-primary">フリマ検索</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['research_free_markets_search']['color'] ?>"
         aria-valuenow="<?php
           print $resources['research_free_markets_search']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['research_free_markets_search']['ratio'] ?>%">
           <?php print $resources['research_free_markets_search']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['research_free_markets_search']['current']

  ?>/<?php

    print $resources['research_free_markets_search']['limit']

  ?>

  </td>
</tr>
