<?php $this->setPageTitle('title', '出品ページ作成') ?>

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
  <li>出品ページ</li>
  <li>ヤフオク</li>

  <li class="active">

  <?php if (isset($template['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $template['id']
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

    <?php print $this->render('crudd',
                  array(
                    'param'  => $template)); ?>

  </div>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info">

          <span class="text-primary">

            テンプレート名

          </span>
        </th>
        <td class="active">

          <input type="text"
                 name="name"
                 value="<?php print $template['name'] ?>"
                 size="96" />

        </td>
      </tr>

      <tr>
        <th class="text-center info">

          <span class="text-primary">

            タイトルフォーマット

          </span>
        </th>
        <td class="active">

          <input type="text"
                 name="title"
                 value="<?php print $template['title'] ?>"
                 size="128" />

        </td>
      </tr>

      <tr>
        <th class="text-center info">
          <span class="text-primary"></span>
        </th>

        <td class="active" style="width: 800px">
          <table class="table table-bordered table-condensed">
            <tbody>

              <tr>
                <th class="text-center info">

                  <span class="text-primary">

                    テンプレート

                  </span>

                </th>
                <td class="active">

                  <textarea id="preview_textarea"
                            name="template"
                            rows="10"
                            cols="96"><?php print $template['template'] ?></textarea>

                </td>
              </tr>

              <tr>
                <th class="text-center info">

                  <span class="text-primary">

                    プレビュー

                  </span>
                </th>
                <td class="active"><div id="preview_div"></div></td>
              </tr>

            </tbody>
          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">作成日時</span></th>
        <td class="active"><input type="hidden" name="created_at" value="<?php print $template['created_at'] ?>" /><?php print $template['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">変更日時</span></th>
        <td class="active"><input type="hidden" name="modified_at" value="<?php print $template['modified_at'] ?>" /><?php print $template['modified_at'] ?></td>
      </tr>
    </tbody>
  </table>
  </div>
  <input type="hidden" name="id" value="<?php print $template['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
