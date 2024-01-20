<li class="dropdown">
  <a tabindex="0"
     data-toggle="dropdown"
     data-submenu
     data-hover="dropdown"
     data-delay="0"
     data-close-others="false">商品管理<span class="caret"></span>
  </a>

  <ul class="dropdown-menu">
    <li><a href="<?php print $base_url; ?>/item/get">新規追加</a></li>

    <li class="divider"></li>

    <li>
      <a href="<?php print $base_url; ?>/item/list/1">
        入庫
        <span class="badge pull-right">
          <?php print $this->getCounterData()['item']['waiting'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/item/list/3">
        出品中
        <span class="badge pull-right">
          <?php print $this->getCounterData()['item']['exhibit'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/item/list/7">
        販売済
        <span class="badge pull-right">
          <?php print $this->getCounterData()['item']['selling'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/item/list/8">
        入金済
        <span class="badge pull-right">
          <?php print $this->getCounterData()['item']['payment'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/item/list/10">
        出庫
        <span class="badge pull-right">
          <?php print $this->getCounterData()['item']['shipment'] ?>
        </span>
      </a>
    </li>

  </ul>
</li>
