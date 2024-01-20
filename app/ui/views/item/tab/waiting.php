<li class="active">
  <a href="<?php print $base_url; ?>/item/list/<?php print $state['waiting'] ?>">
    入庫 (<?php print $this->getCounterData()['item']['waiting'] ?>)
  </a>
</li>

<li>
  <a href="<?php print $base_url; ?>/item/list/<?php print $state['exhibit'] ?>">
    出品中 (<?php print $this->getCounterData()['item']['exhibit'] ?>)
  </a>
</li>

<li>
  <a href="<?php print $base_url; ?>/item/list/<?php print $state['selling'] ?>">
    販売済 (<?php print $this->getCounterData()['item']['selling'] ?>)
  </a>
</li>

<li>
  <a href="<?php print $base_url; ?>/item/list/<?php print $state['payment'] ?>">
    入金済 (<?php print $this->getCounterData()['item']['payment'] ?>)
  </a>
</li>

<li>
  <a href="<?php print $base_url; ?>/item/list/<?php print $state['shipment'] ?>">
    出庫 (<?php print $this->getCounterData()['item']['shipment'] ?>)
  </a>
</li>
