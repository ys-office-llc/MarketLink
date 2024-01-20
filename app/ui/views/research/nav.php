<li class="dropdown">

  <a tabindex="0"
     data-toggle="dropdown"
     data-submenu
     data-hover="dropdown"
     data-delay="0"
     data-close-others="false">

    相場スクリーニング<span class="caret"></span>

  </a>

  <ul class="dropdown-menu">

    <li>
      <a href="<?php print $base_url; ?>/research/watch/list/list">
        ヤフオクウォッチ
        <span class="badge">
          <?php print $this->getCounterData()['research_watch_list'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/use/research/analysis/archive/list">
        マーケットウォッチ
        <span class="badge">
          <?php print $this->getCounterData()['use_research_analysis_archive'] ?>
        </span>
      </a>
    </li>

    <li>
      <a href="<?php print $base_url; ?>/research/stores/list">

        ストアウォッチ

        <span class="badge">
          <?php print $this->getCounterData()['research_stores'] ?>
        </span>
      </a>
    </li>

  <?php if ($this->getUserData()['use_experimental_function'] === 'enable'): ?>

    <li>
      <a href="<?php print $base_url; ?>/research/free/markets/watch/list">

        フリマウォッチ

        <span class="badge">
          <?php print $this->getCounterData()['research_free_markets_watch'] ?>
        </span>

      </a>
    </li>

  <?php endif; ?>

    <li class="divider"></li>

  <?php if ($this->getUserData()['display_format'] === 'personal_computer'): ?>

    <?php print $this->render(
            'research/nav/personal/computer',
            array('' => '')); ?>

  <?php elseif ($this->getUserData()['display_format'] === 'smart_device'): ?>

    <?php print $this->render(
            'research/nav/smart/device',
            array('' => '')); ?>

  <?php endif; ?>

  </ul>
</li>
