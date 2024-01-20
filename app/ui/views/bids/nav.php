<li class="dropdown">
  <a tabindex="0"
     data-toggle="dropdown"
     data-submenu
     data-hover="dropdown"
     data-delay="0"
     data-close-others="false">入札管理<span class="caret"></span>
  </a>

  <ul class="dropdown-menu">

    <li>
      <a href="<?php print $base_url; ?>/bids/list/1">
        入札予約
        <span class="badge pull-right">
          <?php print $this->getCounterData()['bids']['reserve_place_bids'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/bids/list/2">
        入札中
        <span class="badge pull-right">
          <?php print $this->getCounterData()['bids']['bidding'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/bids/list/3">
        落札
        <span class="badge pull-right">
          <?php print $this->getCounterData()['bids']['win'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/bids/list/4">
        終了
        <span class="badge pull-right">
          <?php print $this->getCounterData()['bids']['end'] ?>
        </span>
      </a>
    </li>

  </ul>
</li>
