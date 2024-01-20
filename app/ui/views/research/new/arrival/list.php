<?php $this->setPageTitle('title', 'ストア新着一覧') ?>
<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">

  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>相場スクリーニング</li>
  <li>ストア新着</li>

  <li class="active">

    <a href="<?php
         print $base_url.'/'.
               $view_path.'/list'
              ?>">
      一覧

    </a>
  </li>

</ol>

<?php print $this->render(
        $view_path.'/bar',
        array(
          'view_path' => $view_path
        )
      );
 ?>

<form action="<?php
        print $base_url.'/'.
              $view_path.'/post'
               ?>"
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

          <th rowspan="2" class="text-center info">

            <span class="text-primary">
              <input type="checkbox" id="check-all" />
            </span>

          </th>

          <th rowspan="2" class="text-center info">
            <span class="text-primary">タイトル</span>
          </th>

  <?php if ($this->getUserData()['account_authority_level_id'] > 1): ?>

          <th colspan="6" class="text-center info">

  <?php else: ?>

          <th colspan="5" class="text-center info">

  <?php endif; ?>

            <span class="text-primary">商品ランク</span>

          </th>

          <th rowspan="2" class="text-center info">
            <span class="text-primary">店舗価格</span>
          </th>

          <th rowspan="2" class="text-center info">
            <span class="text-primary">アクション</span>
          </th>

          <th rowspan="2" class="text-center info">
            <span class="text-primary">在庫</span>
          </th>
        </tr>

        <tr>

          <th class="text-center info">
            <span class="text-primary">カメラのキタムラ</span>
          </th>

  <?php if ($this->getUserData()['account_authority_level_id'] > 1): ?>

          <th class="text-center info">
            <span class="text-primary">フジヤカメラ</span>
          </th>

  <?php endif; ?>

          <th class="text-center info">
            <span class="text-primary">カメラのナニワ</span>
          </th>

          <th class="text-center info">
            <span class="text-primary">マップカメラ</span>
          </th>

          <th class="text-center info">
            <span class="text-primary">チャンプカメラ</span>
          </th>

          <th class="text-center info">
            <span class="text-primary">ハードオフ</span>
          </th>

        </tr>

      </thead>

      <tbody>

        <?php foreach ($research_new_arrivals as $research_new_arrival): ?>
        <?php   print $this->render(
                  $view_path . '/individual',
                  array(
                    'research_new_arrival' => $research_new_arrival,
                    'view_path' => $view_path,
                  )
                );
        ?>
        <?php endforeach; ?>

      </tbody>
    </table>

  </div>

  <input type="hidden"
         name="_token"
         value="<?php print $this->escape($_token); ?>" />

</form>
