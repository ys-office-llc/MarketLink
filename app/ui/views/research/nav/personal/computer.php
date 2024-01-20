<li class="dropdown-submenu">
  <a>ヤフオク検索</a>
  <ul class="dropdown-menu">
    <li class="dropdown-submenu">
      <li><a href="<?php print $base_url; ?>/research/yahoo/auctions/search/list">一覧</a></li>
      <li><a href="<?php print $base_url; ?>/research/yahoo/auctions/search/get">作成</a></li>
      </li>
   </ul>
</li>

<li class="dropdown-submenu">

  <a>マーケット検索</a>

  <ul class="dropdown-menu">

    <li class="dropdown-submenu">

      <li>
        <a href="<?php print $base_url; ?>/research/analysis/list">
          一覧
        </a>
      </li>

      <li>
        <a href="<?php print $base_url; ?>/research/analysis/get">
          作成
        </a>
      </li>

    </li>

  </ul>
</li>

<li class="dropdown-submenu">

  <a>ストア新着通知</a>

  <ul class="dropdown-menu">

    <li class="dropdown-submenu">
      <li>
        <a href="<?php print $base_url; ?>/research/new/arrival/list">
          一覧
        </a>
      </li>

      <li>
        <a href="<?php print $base_url; ?>/research/new/arrival/get">
          作成
        </a>
      </li>

    </li>

  </ul>
</li>

<?php if ($this->getUserData()['use_experimental_function'] === 'enable'): ?>

<li class="dropdown-submenu">

  <a>フリマ検索</a>

  <ul class="dropdown-menu">

    <li class="dropdown-submenu">
      <li>
        <a href="<?php print $base_url; ?>/research/free/markets/search/list">
          一覧
        </a>
      </li>

      <li>
        <a href="<?php print $base_url; ?>/research/free/markets/search/get">
          作成
        </a>
      </li>

    </li>

  </ul>
</li>

<?php endif; ?>
