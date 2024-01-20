<?php $this->setPageTitle('title', 'フリマ検索作成') ?>

<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($errors) and count($errors) > 0): ?>

<?php print $this->render('errors', array('errors' => $errors)); ?>

<?php elseif (isset($successes) and count($successes) > 0): ?>

<?php print $this->render('successes', array('successes' => $successes)); ?>

<?php endif; ?>

<ol class="breadcrumb">

  <li>

    <a href="<?php print $this->potal().$base_url; ?>">

      ポータル

    </a>
  </li>
  <li>相場スクリーニング</li>
  <li>フリマ検索</li>

  <li class="active">

  <?php if (isset($research_free_markets_search['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $research_free_markets_search['id']
              ?>">

      情報修正

    </a>

  <?php else: ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>">

      作成

    </a>

  <?php endif; ?>

</ol>

<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('research/new/arrival/crudd',
                  array(
                    'param'  => $research_free_markets_search)); ?>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">
            <p>タイトル</p>
          </span>
        </th>
        <td class="active">
          <input type="text"
                 name="name"
                 value="<?php print $research_free_markets_search['name'] ?>"
                 size="96" />
        </td>
      </tr>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/action',
            array(
              'width' => 600,
              'research_free_markets_search' => $research_free_markets_search,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/chatwork',
            array(
              'width' => 600,
              'research_free_markets_search' => $research_free_markets_search,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/products',
            array(
              'width' => 600,
              'research_free_markets_search' => $research_free_markets_search,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/seller',
            array(
              'width' => 600,
              'research_free_markets_search' => $research_free_markets_search,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'footer/management_information',
            array(
              'information' => $research_free_markets_search,
            )
          )
        );

      }

    ?>

    </tbody>
  </table>
  </div>

  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>

