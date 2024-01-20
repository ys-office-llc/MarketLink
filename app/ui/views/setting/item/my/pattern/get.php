<?php $this->setPageTitle('title', 'マイパターン作成') ?>
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
  <li>マイパターン</li>

  <li class="active">

  <?php if (isset($my_pattern['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $my_pattern['id']
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

<?php print $this->render(
              $view_path.'/bar',
              array(
                'view_path' => $view_path
              )
            ); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('crudd',
                  array(
                    'param'  => $my_pattern)); ?>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">マイパターン名</span>
       </th>

        <td class="active">

          <input type="text"
                 name="name"
                 value="<?php print $my_pattern['name'] ?>"
                 size="96" />

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品情報</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">
            <tbody>
      <tr>
        <th class="text-center info">
        <?php if ($my_pattern['maker_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/maker/get/<?php print $my_pattern['maker_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">メーカー</span>

          </a>
        <?php else: ?>
          <span class="text-primary">メーカー</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'maker_id',
              'values'   => $table_values['item_maker'],
              'selected' => $my_pattern['maker_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if ($my_pattern['category_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/category/get/<?php print $my_pattern['category_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">カテゴリー</span>
          </a>
        <?php else: ?>
          <span class="text-primary">カテゴリー</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'category_id',
              'values'   => $table_values['item_category'],
              'selected' => $my_pattern['category_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if ($my_pattern['grade_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/grade/get/<?php print $my_pattern['grade_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">グレード</span>
          </a>
        <?php else: ?>
          <span class="text-primary">グレード</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'grade_id',
              'values'   => $table_values['item_grade'],
              'selected' => $my_pattern['grade_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if ($my_pattern['description_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/item/description/get/<?php print $my_pattern['description_id'] ?>"
             target="_blank"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">説明文</span>
          </a>
        <?php else: ?>
          <span class="text-primary">説明文</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'description_id',
              'values'   => $table_values['item_description'],
              'selected' => $my_pattern['description_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
        <?php if ($my_pattern['accessories_id'] > 0): ?>
          <a href="<?php print $base_url; ?>/setting/condition/get/<?php print $my_pattern['accessories_id'] ?>"
             class="btn btn-primary"
             style="width: 150px;">
            <span class="text-default">付属品</span>
          </a>
        <?php else: ?>
          <span class="text-primary">付属品</span>
        <?php endif; ?>
        </th>
        <td class="active" style="width: 800px">
          <?php print $this->render('select',
            array(
              'name'     => 'accessories_id',
              'values'   => $table_values['item_accessories'],
              'selected' => $my_pattern['accessories_id'],
              'sort'     => 'asort',
            )); ?>
        </td>
      </tr>
      <tr>
        <th class="text-center info">
          <span class="text-primary">備考</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>
              <tr>
                <th class="text-center info">
                  <span class="text-primary">日本語</span>
                </th>
                <td class="text-left active">
                  <textarea name="remarks_ja"
                            rows="2"
                            cols="64"><?php print $my_pattern['remarks_ja'] ?></textarea>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">英語</span>
                 </th>
                <td class="text-left active">
                  <textarea name="remarks_en"
                            rows="2"
                            cols="64"><?php print $my_pattern['remarks_en'] ?></textarea>
                </td>
              </tr>
            </tbody>

          </table>
        </td>
      </tr>

            </tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">ヤフオク</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>

              <tr>
                <th class="text-center info">
                <?php if ($my_pattern['yahoo_auctions_condition_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/condition/yahoo/auctions/get/<?php print $my_pattern['yahoo_auctions_condition_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品条件</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品条件</span>
                <?php endif; ?>
                 </th>

                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'yahoo_auctions_condition_id',
                      'values'   => $table_values['item_condition_yahoo_auctions'],
                      'selected' => $my_pattern['yahoo_auctions_condition_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                <?php if ($my_pattern['yahoo_auctions_template_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/template/yahoo/auctions/get/<?php print $my_pattern['yahoo_auctions_template_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品ページ</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品ページ</span>
                <?php endif; ?>
                </th>
                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'yahoo_auctions_template_id',
                      'values'   => $table_values['item_template_yahoo_auctions'],
                      'selected' => $my_pattern['yahoo_auctions_template_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>

            </tbody>

          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">eBay</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>

              <tr>
                <th class="text-center info">
                <?php if ($my_pattern['ebay_us_condition_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/condition/ebay/us/get/<?php print $my_pattern['ebay_us_condition_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品条件</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品条件</span>
                <?php endif; ?>
                </th>

                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'ebay_us_condition_id',
                      'values'   => $table_values['item_condition_ebay_us'],
                      'selected' => $my_pattern['ebay_us_condition_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                <?php if ($my_pattern['ebay_us_template_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/template/ebay/us/get/<?php print $my_pattern['ebay_us_template_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品ページ</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品ページ</span>
                <?php endif; ?>
                </th>
                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'ebay_us_template_id',
                      'values'   => $table_values['item_template_ebay_us'],
                      'selected' => $my_pattern['ebay_us_template_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>

            </tbody>

          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">Amazon.co.jp</span>
        </th>

        <td class="active">
          <table class="table table-bordered table-condensed">

            <tbody>
              <tr>
                <th class="text-center info">
                <?php if ($my_pattern['amazon_jp_template_id'] > 0): ?>
                  <a href="<?php print $base_url; ?>/setting/item/template/amazon/jp/get/<?php print $my_pattern['amazon_jp_template_id'] ?>"
                     target="_blank"
                     class="btn btn-primary"
                     style="width: 150px;">
                    <span class="text-default">出品ページ</span>
                  </a>
                <?php else: ?>
                  <span class="text-primary">出品ページ</span>
                <?php endif; ?>
                 </th>

                <td class="text-center active" style="width: 800px">
                  <?php print $this->render('select',
                    array(
                      'name'     => 'amazon_jp_template_id',
                      'values'   => $table_values['item_template_amazon_jp'],
                      'selected' => $my_pattern['amazon_jp_template_id'],
                      'sort'     => 'asort',
                  )); ?>
                </td>
              </tr>
            </tbody>

          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">管理ID</span>
       </th>

        <td class="active"><?php print $my_pattern['id'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">作成日時</span>
       </th>

        <td class="active"><?php print $my_pattern['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
       </th>

        <td class="active"><?php print $my_pattern['modified_at'] ?></td>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="created_at" value="<?php print $my_pattern['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $my_pattern['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $my_pattern['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
