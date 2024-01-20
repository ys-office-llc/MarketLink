<?php $this->setPageTitle('title', 'マーケットウォッチ') ?>
<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">
  <li><a href="<?php print $this->potal().$base_url; ?>">ポータル</a></li>
  <li>自動リサーチ</li>

  <li class="active">

    <a href="<?php print $base_url.'/'.$view_path.'/list' ?>"
       target="_self">

      マーケットウォッチ

    </a>

  </li>
</ol>

<ul class="nav nav-tabs">

<?php if ($period === '1m'): ?>

  <li class="active">

<?php else: ?>

  <li>

<?php endif; ?>

    <a href="<?php print $base_url.'/'.$view_path.'/get/'.$id.'/'.$ym ?>">

      1ヶ月

    </a>
  </li>

<ul class="nav nav-tabs">

<?php if ($period === '3m'): ?>

  <li class="active">

<?php else: ?>

  <li>

<?php endif; ?>

  <a href="<?php print $base_url.'/'.$view_path.'/get/'.$id.'/'.$y3m ?>">

    3ヶ月

  </a>

</li>


<ul class="nav nav-tabs">

<?php if ($period === '1y'): ?>

  <li class="active">

<?php else: ?>

  <li>

<?php endif; ?>

  <a href="<?php print $base_url.'/'.$view_path.'/get/'.$id.'/'.$y ?>">

    1年

  </a>

</li>

</ul>

<script id="archives" src="/js/bid_price_limit_calculation.js"

  data-graph-data   = '<?php print $graph_data ?>'
  data-graph-period = '1ヶ月'
  data-graph-title  = '<?php print $use_research_analysis_archives[0]['name'] ?>'

></script>

<canvas id="index" width="800" height="150"></canvas>
<canvas id="numof" width="800" height="150"></canvas>
<canvas id="price" width="800" height="200"></canvas>

<div class="table-responsive">

  <table id="dt"
         class="table display table-bordered"
         cellspacing="0"
         width="100%">

    <thead>

      <tr>

        <th rowspan="3" class="text-center info">
          <span class="text-primary"></span>
        </th>

        <th rowspan="3" class="text-center info">
          <span class="text-primary">日付</span>
        </th>

        <th rowspan="3" class="text-center info">
          <span class="text-primary"></span>
        </th>

        <th rowspan="3" class="text-center info">
          <span class="text-primary">商品名</span>
        </th>

        <th colspan="6" class="text-center info">
          <span class="text-primary">eBay</span>
        </th>

        <th colspan="6" class="text-center info">
          <span class="text-primary">ヤフオク</span>
        </th>

        <th colspan="2" class="text-center info">
          <span class="text-primary">Amazon Japan</span>
        </th>

      </tr>

      <tr>

        <th colspan="3" class="text-center info">
          <span class="text-primary">価格（ドル）</span>
        </th>

        <th colspan="2" class="text-center info">
          <span class="text-primary">個数</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">指標</span>
        </th>

        <th colspan="3" class="text-center info">
          <span class="text-primary">価格（円）</span>
        </th>

        <th colspan="2" class="text-center info">
          <span class="text-primary">個数</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">指標</span>
        </th>

        <th colspan="1" class="text-center info">
          <span class="text-primary">価格（円）</span>
        </th>

        <th rowspan="2" class="text-center info">
          <span class="text-primary">ランク</span>
        </th>

      <tr>

        <th class="text-center info">
          <span class="text-primary">最低</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">最高</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">平均</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">現在</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">1ヶ月</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">最低</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">最高</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">平均</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">現在</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">1ヶ月</span>
        </th>

        <th class="text-center info">
          <span class="text-primary">最低</span>
        </th>

      </tr>

    </thead>

    <tbody>

      <?php foreach ($use_research_analysis_archives as $use_research_analysis_archive): ?>
      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'use_research_analysis_archive' => $use_research_analysis_archive,
                  'ym' => $ym,
                  'view_path' => $view_path,
                )
              );
      ?>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>
