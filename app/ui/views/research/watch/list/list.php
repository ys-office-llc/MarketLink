<?php $this->setPageTitle('title', 'ヤフオクウォッチ') ?>
<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">
  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>相場スクリーニング</li>
  <li class="active">

    <a href="<?php print $base_url; ?>/research/watch/list/list">

      ヤフオクウォッチ

    </a>

  </li>
</ol>

<form action="<?php print $base_url; ?>/research/watch/list/post"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

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
          <span class="text-primary">商品写真</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">タイトル</span>
        </th>

        <th colspan="2" class="text-center info">
          <span class="text-primary">価格</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">入札数</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">残り時間</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">相場</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary"></span>
        </th>

      </tr>

      <tr>

        <th colspan="1" class="text-center info">
          <span class="text-primary">現在</span>
        </th>

        <th rowspan="1" class="text-center info">
          <span class="text-primary">即決</span>
        </th>

      </tr>

    </thead>

    <tbody>

      <?php foreach ($research_watch_lists as $index => $research_watch_list): ?>

      <?php   if ((int)$research_watch_list['delete_request'] === 0): ?>

      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'index' => $index,
                  'research_watch_list' => $research_watch_list,
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
