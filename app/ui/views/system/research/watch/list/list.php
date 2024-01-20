<?php $this->setPageTitle('title', 'ウォッチリスト連動') ?>
<?php print $this->render('nav', array('' => array())); ?>

<div class="table-responsive">
  <table id="dt"
         class="table display table-bordered table-hover"
         cellspacing="0"
         width="100%">

    <thead>

      <tr>
        <th class="text-center info">
          <span class="text-primary">商品写真</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">タイトル</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">現在価格</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">即決価格</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">入札数</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">残り時間</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">相場チェック</span>
        </th>

      </tr>

    </thead>

    <tbody>

      <?php foreach ($research_watch_lists as $research_watch_list): ?>
      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'research_watch_list' => $research_watch_list,
                  'view_path'           => $view_path,
                )
              );
      ?>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>
