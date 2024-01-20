<?php $this->setPageTitle('title', 'サインイン') ?>

<div class="container">

  <div class="panel panel-info">

    <div class="panel-heading">
      <h1 class="panel-title">
        <img src="/images/market_link_logo2.png"
             alt="MarketLink for CAMERA"
             width="150"
             height="33"
             border="0" />
      </h1>
    </div>

    <?php if (isset($errors) and count($errors) > 0): ?>
      <?php print $this->render('errors', array('errors' => $errors)); ?>
    <?php endif; ?>

    <div class="panel-body">
      <form action="<?php print $base_url; ?>/account/authenticate"
            method="post">

        <input type="hidden"
               name="_token"
               value="<?php print $this->escape($_token); ?>" />


        <?php print $this->render('account/inputs', array(
          'user_name' => $user_name, 'password' => $password,
        )); ?>

        <div class="form-group">
          <input type="submit"
                 class="btn btn-primary"
                 value="サインイン" />
        </div>

        <small>

          <a href="<?php
               printf(
                 "%s/account/reset/resend",
                 $this->potal()
               ) ?>"
             target="_blank">

            パスワードを忘れた方はこちら  

          </a>

        </small>

      </form>
    </div>

  </div>

</div>
