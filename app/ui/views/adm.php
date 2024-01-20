<nav class="navbar navbar-default navbar-fixed-top"
     role="navigation">

  <div class="navbar-header">

    <button type="button"
            class="navbar-toggle"
            data-toggle="collapse"
            data-target=".navbar-ex1-collapse">

      <span class="sr-only"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>

    </button>

    <a href="<?php print $base_url; ?>"
       class="navbar-brand">

      <img src="/images/market_link_logo2.png"
           alt="Market Link for CAMERA"
           width="150"
           height="33"
           border="0" />

    </a>

  </div>

  <div class="collapse navbar-collapse navbar-ex1-collapse">

    <ul class="nav navbar-nav">

      <li>
        <a href="<?php
                   print(
                     $base_url.
                     '/account/get'
                   )
                 ?>">

          ユーザー登録

        </a>
      </li>

      <li>
        <a href="<?php
                   print(
                     $base_url.
                     '/account/list'
                   )
                 ?>">

          ユーザー一覧

        </a>
      </li>

      <li>
        <a href="<?php
                   print(
                     $base_url.
                     '/administrator/host/get'
                   )
                 ?>">

          ホスト登録

        </a>
      </li>

      <li>
        <a href="<?php
                   print(
                     $base_url.
                     '/administrator/host/list'
                   )
                 ?>">

          ホスト一覧

        </a>
      </li>

      <li>
        <a href="<?php
                   print(
                     $base_url.
                     '/system/item/my/pattern/get'
                   )
                 ?>">

          初期マイパターン登録

        </a>
      </li>

      <li>
        <a href="<?php
                   print(
                     $base_url.
                     '/system/item/my/pattern/list'
                   )
                 ?>">

          初期マイパターン一覧

        </a>
      </li>

      <li>
        <a href="<?php print $base_url ?>/">サインイン</a>
      </li>

    </ul>
  </div>
</nav>

<h1 class="page-header"></h1>
