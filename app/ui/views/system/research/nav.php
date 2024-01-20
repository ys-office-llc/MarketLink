<li class="dropdown">
  <a tabindex="0"
     data-toggle="dropdown"
     data-submenu
     data-hover="dropdown"
     data-delay="0"
     data-close-others="false">自動リサーチ<span class="caret"></span>
  </a>

  <ul class="dropdown-menu">

  <?php if ($this->getUserData()['account_market_research_id'] > 0): ?>

  <?php   if ($this->getUserData()['account_automatic_watch_list_id'] > 0): ?>

    <li><a href="<?php print $base_url; ?>/research/watch/list/list">ウォッチリスト連動</a></li>

    <li class="divider"></li>

    <li class="dropdown-submenu">
      <a>ヤフオク検索条件</a>
      <ul class="dropdown-menu">

        <li class="dropdown-submenu">
          <li><a href="<?php print $base_url; ?>/research/yahoo/auctions/search/list">一覧</a></li>
          <li><a href="<?php print $base_url; ?>/research/yahoo/auctions/search/get">作成</a></li>
        </li>

      </ul>
    </li>

  <?php   endif; ?>

  <?php   if ($this->getUserData()['account_automatic_new_list_id'] > 0): ?>

    <li class="dropdown-submenu">
      <a>新着通知</a>
      <ul class="dropdown-menu">

        <li class="dropdown-submenu">
          <li><a href="<?php print $base_url; ?>/research/new/arrival/list">一覧</a></li>
          <li><a href="<?php print $base_url; ?>/research/new/arrival/get">作成</a></li>
        </li>

      </ul>
    </li>

  <?php   endif; ?>

    <li class="dropdown-submenu">
      <a>商品力分析</a>
      <ul class="dropdown-menu">

        <li class="dropdown-submenu">
          <li><a href="<?php print $base_url; ?>/research/analysis/list">一覧</a></li>
          <li><a href="<?php print $base_url; ?>/research/analysis/get">作成</a></li>
        </li>

      </ul>
    </li>

  <?php endif; ?>

  </ul>
</li>
