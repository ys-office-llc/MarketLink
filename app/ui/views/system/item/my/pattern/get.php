<?php $this->setPageTitle('title', 'マイパターン作成') ?>

<?php print $this->render('adm', array('' => array())); ?>

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

  <li class="active">

  <?php if (isset($system_item_my_pattern['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $account['id']
              ?>">

      情報修正

    </a>

  <?php else: ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>">

      初期マイパターン登録

    </a>

  <?php endif; ?>

</ol>

<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<form action="<?php print $base_url; ?>/<?php print $view_path ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <?php print $this->render('crudd',
                  array(
                    'param'  => $system_item_my_pattern)); ?>

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
                 value="<?php print $system_item_my_pattern['name'] ?>"
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
          <span class="text-primary">メーカー</span>
        </th>
        <td class="active" style="width: 800px">

          <input type="text"
                 name="maker"
                 value="<?php print $system_item_my_pattern['maker'] ?>"
                 size="96" />

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">カテゴリー</span>
        </th>
        <td class="active" style="width: 800px">

          <input type="text"
                 name="category"
                 value="<?php print $system_item_my_pattern['category'] ?>"
                 size="96" />

        </td>

      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">グレード</span>
        </th>
        <td class="active" style="width: 800px">

          <input type="text"
                 name="grade"
                 value="<?php print $system_item_my_pattern['grade'] ?>"
                 size="96" />

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">説明文</span>
        </th>
        <td class="active" style="width: 800px">

          <input type="text"
                 name="description"
                 value="<?php print $system_item_my_pattern['description'] ?>"
                 size="96" />

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">付属品</span>
        </th>
        <td class="active" style="width: 800px">

          <input type="text"
                 name="accessories"
                 value="<?php print $system_item_my_pattern['accessories'] ?>"
                 size="96" />

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
                            cols="96"><?php print $system_item_my_pattern['remarks_ja'] ?></textarea>
                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">英語</span>
                 </th>
                <td class="text-left active">
                  <textarea name="remarks_en"
                            rows="2"
                            cols="96"><?php print $system_item_my_pattern['remarks_en'] ?></textarea>
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
                  <span class="text-primary">出品条件</span>
                 </th>

                <td class="text-left active" style="width: 800px">

          <input type="text"
                 name="yahoo_auctions_condition"
                 value="<?php print $system_item_my_pattern['yahoo_auctions_condition'] ?>"
                 size="96" />

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">出品ページ</span>
                </th>
                <td class="text-left active" style="width: 800px">

          <input type="text"
                 name="yahoo_auctions_template"
                 value="<?php print $system_item_my_pattern['yahoo_auctions_template'] ?>"
                 size="96" />

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
                  <span class="text-primary">出品条件</span>
                </th>

                <td class="text-left active" style="width: 800px">

          <input type="text"
                 name="ebay_us_condition"
                 value="<?php print $system_item_my_pattern['ebay_us_condition'] ?>"
                 size="96" />

                </td>
              </tr>

              <tr>
                <th class="text-center info">
                  <span class="text-primary">出品ページ</span>
                </th>
                <td class="text-left active" style="width: 800px">

          <input type="text"
                 name="ebay_us_template"
                 value="<?php print $system_item_my_pattern['ebay_us_template'] ?>"
                 size="96" />

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
                  <span class="text-primary">出品ページ</span>
                 </th>

                <td class="text-left active" style="width: 800px">

          <input type="text"
                 name="amazon_jp_template"
                 value="<?php print $system_item_my_pattern['amazon_jp_template'] ?>"
                 size="96" />

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

        <td class="active"><?php print $system_item_my_pattern['id'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">作成日時</span>
       </th>

        <td class="active"><?php print $system_item_my_pattern['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary">変更日時</span>
       </th>

        <td class="active"><?php print $system_item_my_pattern['modified_at'] ?></td>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="created_at" value="<?php print $system_item_my_pattern['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $system_item_my_pattern['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $system_item_my_pattern['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
