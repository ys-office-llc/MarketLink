<?php $this->setPageTitle('title', 'お問い合わせ') ?>

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
  <li>サポート</li>
  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/get'
              ?>"
       target="_self">

      お問い合わせ

    </a>

  </li>

</ol>

<form action="<?php print($base_url.'/'.$view_path) ?>/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <input class="btn btn-success"
           type="submit"
           name="create"
           value="問い合わせをする" />

  </div>

  <div class="table-responsive">

  <table class="table display table-bordered">
    <tbody>

      <tr>
        <th class="text-center info">
          <span class="text-primary">タイトル</th></span>
        </th>

        <td class="active">

          <input type="text"
                 name="title"
                 value="<?php print $contact['title'] ?>"
                 size="64" />

        </td>
      </tr>

      <tr>

        <th class="text-center info">

          <span class="text-primary">内容</span>

        </th>

        <td class="active" style="width: 900px">

          <textarea name="inquiry"
                    cols="96"
                    rows="10"
                    placeholder="わからないこと、解決したいことの詳細を書いてください。"><?php print($contact['inquiry']) ?></textarea>

        </td>
      </tr>

      <tr>

        <th class="text-center info">

          <span class="text-primary">

            <p>添付画像<p>
            <p>※ JPEGかPNG形式のみ<p>

          </span>

        </th>

        <td class="active">

          <input id="input-ja"
                 name="images[]"
                 type="file"
                 class="file"
                 multiple
                 data-show-upload="false"
                 data-show-caption="false">

        </td>

      </tr>

    </tbody>
  </table>

  </div>

  <input type="hidden" name="id" value="<?php print $contact['id'] ?>" />
  <input type="hidden" name="created_at" value="<?php print $contact['created_at'] ?>" />
  <input type="hidden" name="modified_at" value="<?php print $contact['modified_at'] ?>" />
  <input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
