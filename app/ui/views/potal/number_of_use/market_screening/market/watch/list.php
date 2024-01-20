<tr>

  <th class="text-center info">

    <span class="text-primary">マーケットウォッチ</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['use_research_analysis_archive']['color'] ?>"
         aria-valuenow="<?php
           print $resources['use_research_analysis_archive']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['use_research_analysis_archive']['ratio'] ?>%">
           <?php print $resources['use_research_analysis_archive']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['use_research_analysis_archive']['current']

  ?>/<?php

    print $resources['use_research_analysis_archive']['limit']

  ?>

  </td>
</tr>
