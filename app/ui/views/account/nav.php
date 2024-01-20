<?php if ($this->getUserData()['account_authority_level_id'] > 1): ?>

<li>

  <a href="<?php print 
                   $base_url.
                   '/account/list'
            ?>"
     target="_self">

    管理者メニュー

  </a>

</li>

<?php endif; ?>
