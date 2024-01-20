<?php $this->setPageTitle('title', 'ストアウォッチ') ?>
<?php print $this->render('nav', array('' => array())); ?>

<?php if (isset($errors) and count($errors) > 0): ?>

<?php print $this->render('errors', array('errors' => $errors)); ?>

<?php elseif (isset($successes) and count($successes) > 0): ?>

<?php print $this->render('successes', array('successes' => $successes)); ?>

<?php endif; ?>

<ol class="breadcrumb">
  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>相場スクリーニング</li>

  <li class="active">

    <a href="<?php print $base_url; ?>/research/stores/list">

      ストアウォッチ

    </a>

  </li>
</ol>

<form action="<?php print $base_url; ?>/research/stores/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <input class="btn btn-primary confirm"
           type="submit"
           name="export_csv"
           value="選択した商品をCSV形式でダウンロードする（ダウンロード後はF5キーを押してください）" />

    <input class="btn btn-danger"
           id="confirm_delete"
           type="submit"
           name="delete_by_checked"
           value="選択した商品を一括で削除する" />

  </div>

<div class="table-responsive">

  <table id="dt"
         class="table display table-bordered table-hover"
         cellspacing="0"
         width="100%">

    <thead>

      <tr>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">
            <input type="checkbox" id="check-all" />
          </span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">ストア</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">タイトル</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">ランク</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">価格</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">商品状態</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">付属品</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">在庫</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">更新日</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">相場</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary"></span>
        </th>

      </tr>

    </thead>

    <tbody>

      <?php foreach ($research_storess as $index => $research_stores): ?>

      <?php   if ((int)$research_stores['delete_request'] === 0): ?>

      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'index' => $index,
                  'research_stores' => $research_stores,
                  'view_path'           => $view_path,
                )
              );
      ?>
      <?php   endif; ?>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>

<input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />
</form>
