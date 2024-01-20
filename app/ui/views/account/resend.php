<?php $this->setPageTitle('title', 'パスワード再設定') ?>

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

<?php elseif (isset($successes) and count($successes) > 0): ?>

<?php print $this->render('successes', array('successes' => $successes)); ?>

<?php endif; ?>

    <div class="panel-body">

      <form action="<?php print $base_url; ?>/account/reset/resend"
            method="post">

        <div class="form-group">

          <label for="user_name">ユーザー名</label>

            <input type="text"
                   class="form-control"
                   name="user_name"
                   value="<?php echo $this->escape($user_name); ?>" />

        </div>

        <div class="form-group">

          <input type="submit"
                 class="btn btn-primary"
                 value="パスワードを再設定する" />

        </div>

        <input type="hidden"
               name="_token"
               value="<?php print $this->escape($_token); ?>" />

      </form>
    </div>

  </div>

</div>
