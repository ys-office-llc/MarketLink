<tr>

  <th class="text-center info">

    <span class="text-primary">ストアウォッチ</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['research_stores']['color'] ?>"
         aria-valuenow="<?php
           print $resources['research_stores']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['research_stores']['ratio'] ?>%">
           <?php print $resources['research_stores']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['research_stores']['current']

  ?>/<?php

    print $resources['research_stores']['limit']

  ?>

  </td>
</tr>
