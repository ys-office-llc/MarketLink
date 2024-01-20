<tr>

  <th class="text-center info">

    <span class="text-primary">入札予約</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['reserve_place_bids']['color'] ?>"
         aria-valuenow="<?php
           print $resources['reserve_place_bids']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['reserve_place_bids']['ratio'] ?>%">
           <?php print $resources['reserve_place_bids']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['reserve_place_bids']['current']

  ?>/<?php

    print $resources['reserve_place_bids']['limit']

  ?>

  </td>
</tr>
