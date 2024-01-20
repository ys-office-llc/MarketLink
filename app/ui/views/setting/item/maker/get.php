<?php $this->setPageTitle('title', 'メーカー作成') ?>
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
  <li>ユーザー設定</li>
  <li>商品管理</li>
  <li>メーカー</li>

  <li class="active">

  <?php if (isset($maker['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $maker['id']
              ?>">

      情報修正

    </a>

  <?php else: ?>

    作成

  <?php endif; ?>

</ol>

<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('crudd',
                  array(
                    'param'  => $maker)); ?>

  </div>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info"><span class="text-primary">メーカー名</span></th>
        <td class="active"><input type="text" name="name" value="<?php print $maker['name'] ?>" size="96" /></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">日本語表記（ヤフオク用）</span>
        </th>

        <td class="active">
          <input type="text"
                 name="maker_display_name_ja"
                 value="<?php print $maker['maker_display_name_ja'] ?>"
                 size="32" />
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">ヤフオク</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'yahoo_auctions_id',
              'values'   => $table_values['item_maker_yahoo_auctions'],
              'selected' => $maker['yahoo_auctions_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">eBay US</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'ebay_us_id',
              'values'   => $table_values['item_maker_ebay_us'],
              'selected' => $maker['ebay_us_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">Amazon.co.jp</span></th>
        <td class="active">
          <?php print $this->render('select',
            array(
              'name'     => 'amazon_jp_id',
              'values'   => $table_values['item_maker_amazon_jp'],
              'selected' => $maker['amazon_jp_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">作成日時</span></th>
        <td class="active"><?php print $maker['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">変更日時</span></th>
        <td class="active"><?php print $maker['modified_at'] ?></td>
      </tr>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="created_at" value="<?php print $maker['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $maker['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $maker['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
