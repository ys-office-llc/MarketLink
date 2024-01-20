<tr>

  <th class="text-center info">

    <span class="text-primary">マーケット検索</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['research_analysis']['color'] ?>"
         aria-valuenow="<?php
           print $resources['research_analysis']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['research_analysis']['ratio'] ?>%">
           <?php print $resources['research_analysis']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['research_analysis']['current']

  ?>/<?php

    print $resources['research_analysis']['limit']

  ?>

  </td>
</tr>
