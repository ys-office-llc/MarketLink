<?php $this->setPageTitle('title', 'フリマ検索一覧') ?>
<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">

  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>相場スクリーニング</li>
  <li>フリマ検索</li>

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

          <th colspan="3" class="text-center info">

            <span class="text-primary">商品ランク</span>

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
            <span class="text-primary">メルカリ</span>
          </th>

          <th class="text-center info">
            <span class="text-primary">ラクマ</span>
          </th>

          <th class="text-center info">
            <span class="text-primary">フリル</span>
          </th>

        </tr>

      </thead>

      <tbody>

        <?php foreach ($research_free_markets_searchs as $research_free_markets_search): ?>
        <?php   print $this->render(
                  $view_path . '/individual',
                  array(
                    'research_free_markets_search' => $research_free_markets_search,
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
