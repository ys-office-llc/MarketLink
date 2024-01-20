<li class="dropdown">
  <a tabindex="0"
     data-toggle="dropdown"
     data-submenu
     data-hover="dropdown"
     data-delay="0"
     data-close-others="false">ユーザー設定<span class="caret"></span>
  </a>

  <ul class="dropdown-menu">

    <li>
      <a href="<?php
           printf(
             "%s/setting/account/get/%s",
             $base_url,
             $this->getUserData()['id']
           )
         ?>">

        ユーザー情報

      </a>
    </li>

<?php if ($this->getUserData()['merchandise_management'] === 'enable'): ?>

    <li class="divider"></li>

  <?php if ($this->getUserData()['display_format'] === 'personal_computer'): ?>

    <?php print $this->render(
            'setting/nav/personal/computer',
            array('' => '')); ?>

  <?php elseif ($this->getUserData()['display_format'] === 'smart_device'): ?>

    <?php print $this->render(
            'setting/nav/smart/device',
            array('' => '')); ?>

  <?php endif; ?>

<?php endif; ?>

  </ul>
</li>
