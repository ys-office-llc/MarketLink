<li class="active">
  <a href="<?php print $base_url; ?>/bids/list/<?php print $state['reserve_place_bids'] ?>">
    入札予約 (<?php print $this->getCounterData()['bids']['reserve_place_bids'] ?>)
  </a>
</li>

<li>
  <a href="<?php print $base_url; ?>/bids/list/<?php print $state['bidding'] ?>">
    入札中 (<?php print $this->getCounterData()['bids']['bidding'] ?>)
  </a>
</li>

<li>
  <a href="<?php print $base_url; ?>/bids/list/<?php print $state['win'] ?>">
    落札(<?php print $this->getCounterData()['bids']['win'] ?>)
  </a>
</li>

<li>
  <a href="<?php print $base_url; ?>/bids/list/<?php print $state['end'] ?>">
    終了(<?php print $this->getCounterData()['bids']['end'] ?>)
  </a>
</li>
