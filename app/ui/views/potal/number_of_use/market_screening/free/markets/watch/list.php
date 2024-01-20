<tr>

  <th class="text-center info">

    <span class="text-primary">フリマウォッチ</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['research_free_markets_watch']['color'] ?>"
         aria-valuenow="<?php
           print $resources['research_free_markets_watch']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['research_free_markets_watch']['ratio'] ?>%">
           <?php print $resources['research_free_markets_watch']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['research_free_markets_watch']['current']

  ?>/<?php

    print $resources['research_free_markets_watch']['limit']

  ?>

  </td>
</tr>
