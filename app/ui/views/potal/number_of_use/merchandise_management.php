<?php if ($account['merchandise_management'] === 'enable'): ?>

<tr>

  <th class="text-center info">

    <span class="text-primary">商品管理</span>

  </th>

  <td class="active" style="width: <?php print $width ?>px">

  <div class="progress">
    <div class="progress-bar <?php
           print $resources['counter']['color'] ?>"
         aria-valuenow="<?php
           print $resources['counter']['ratio'] ?>"
         aria-valuemin="0"
         aria-valuemax="100"
         style="width:<?php
           print $resources['counter']['ratio'] ?>%">
           <?php print $resources['counter']['ratio'] ?>%
    </div>
  </div>

  <?php

    print (int)$resources['counter']['current']

  ?>/<?php

    print $resources['counter']['limit']

  ?>

  </td>
</tr>

<?php endif; ?>
