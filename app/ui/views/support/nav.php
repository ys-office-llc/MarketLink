<li class="dropdown">
  <a tabindex="0"
     data-toggle="dropdown"
     data-submenu
     data-hover="dropdown"
     data-delay="0"
     data-close-others="false">

    サポート<span class="caret"></span>

  </a>

  <ul class="dropdown-menu">

    <li>

      <a

      <?php if (preg_match(
                  "/^demonstration$/",
                  $this->getUserData()['operation_mode']
                )
            ): ?>

         href="http://tweyes.net/easy_camera/"

      <?php elseif (preg_match(
                      "/^commercial$/",
                      $this->getUserData()['operation_mode']
                    )
            ): ?>

         href="http://tweyes.net/member/"

      <?php endif; ?>

         target="_blank">

        利用ガイド

      </a>
    </li>

    <li class="divider"></li>

    <li>
      <a href="<?php print($base_url.'/support/contact/get') ?>"
         target="_self">

        お問い合わせ

      </a>
    </li>

    <li class="divider"></li>

    <li>
      <a href="<?php
           printf(
             "%s/support/account/get/%s",
             $base_url,
             $this->getUserData()['id']
           )
         ?>"
         target="_self">

        契約管理

      </a>
    </li>

  </ul>
</li>
