<?php $this->setPageTitle('title', '光学系作成') ?>

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
  <li>説明文</li>
  <li>光学系</li>

  <li class="active">

  <?php if (isset($description_optics['id'])): ?>

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get/'.
               $description_optics['id']
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
                    'param'  => $description_optics)); ?>

  </div>

  <div class="table-responsive">
  <table class="table display table-bordered"
         cellspacing="0"
         width="100%">
    <tbody>

      <tr>
        <th class="text-center info"><span class="text-primary">光学系名</span></th>
        <td class="active">

          <input type="text"
                 name="name"
                 value="<?php print $description_optics['name'] ?>"
                 size="96" />

        </td>
      </tr>

      <tr>
        <th class="text-center info">

          <span class="text-primary">

            光学系

          </span>

        </th>
        <td class="active">
          <table>

            <thead>
              <tr>
                <th class="text-center info"><span class="text-primary">日本語</span></th>
                <th class="text-center info"><span class="text-primary">英語</span></th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td class="active">
                  <textarea name="description_optics_ja"
                            rows="6"
                            cols="60"><?php print $description_optics['description_optics_ja'] ?></textarea>
                </td>
                <td class="active">
                  <textarea name="description_optics_en"
                            rows="6"
                            cols="60"><?php print $description_optics['description_optics_en'] ?></textarea>
                </td>
              </tr>
            </tbody>

          </table>
        </td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">作成日時</span></th>
        <td class="active"><?php print $description_optics['created_at'] ?></td>
      </tr>

      <tr>
        <th class="text-center info"><span class="text-primary">変更日時</span></th>
        <td class="active"><?php print $description_optics['modified_at'] ?></td>
      </tr>

    </tbody>
  </table>
  </div>
  <input type="hidden" name="created_at" value="<?php print $description_optics['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $description_optics['modified_at'] ?>" />
  <input type="hidden" name="id" value="<?php print $description_optics['id'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
