<tr>

  <th class="text-center info">

    <span class="text-primary">ヤフオクウォッチ</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['research_watch_list']['color'] ?>"
         aria-valuenow="<?php
           print $resources['research_watch_list']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['research_watch_list']['ratio'] ?>%">
           <?php print $resources['research_watch_list']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['research_watch_list']['current']

  ?>/<?php

    print $resources['research_watch_list']['limit']

  ?>

  </td>
</tr>
