<tr>

  <th class="text-center info">

    <span class="text-primary">ヤフオク検索</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['research_yahoo_auctions_search']['color'] ?>"
         aria-valuenow="<?php
           print $resources['research_yahoo_auctions_search']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['research_yahoo_auctions_search']['ratio'] ?>%">
           <?php print $resources['research_yahoo_auctions_search']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['research_yahoo_auctions_search']['current']

  ?>/<?php

    print $resources['research_yahoo_auctions_search']['limit']

  ?>

  </td>
</tr>
