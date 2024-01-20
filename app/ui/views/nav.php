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

    <a href="<?php print $this->potal().$base_url; ?>"
       class="navbar-brand">

      <img src="/images/market_link_logo2.png"
           alt="Market Link for CAMERA"
           width="150"
           height="33"
           border="0" />
    </a>

  </div>
  <!--/.navbar-header-->

  <div class="collapse navbar-collapse navbar-ex1-collapse">

    <ul class="nav navbar-nav">

      <?php print $this->render('account/nav', array('' => array())); ?>

   <?php if ($this->getUserData()['market_screening'] === 'enable'): ?>

      <?php print $this->render('research/nav', array('' => array())); ?>

   <?php   if ($this->getUserData()['account_contract_id'] > 1): ?>

      <?php print $this->render('bids/nav', array('' => array())); ?>

   <?php   endif; ?>

   <?php endif; ?>

   <?php if ($this->getUserData()['merchandise_management'] === 'enable'): ?>

      <?php print $this->render('item/nav', array('' => array())); ?>

   <?php endif; ?>

      <?php print $this->render('setting/nav', array('' => array())); ?>

   <?php if ($this->getUserData()['market_screening'] === 'enable'): ?>

      <?php print $this->render('import/nav', array('' => array())); ?>

   <?php endif; ?>

      <?php print $this->render('support/nav', array('' => array())); ?>

      <li>
        <a href="<?php print $base_url; ?>/account/signout">

          サインアウト

        </a>
      </li>

    </ul>

  </div>

</nav>
<h1 class="page-header"></h1>
