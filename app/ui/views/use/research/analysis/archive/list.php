<?php $this->setPageTitle('title', 'マーケットウォッチ') ?>
<?php print $this->render('nav', array('' => array())); ?>

<ol class="breadcrumb">

  <li>

    <a href="<?php print($this->potal().$base_url) ?>">

      ポータル

    </a>
  </li>

  <li>相場スクリーニング</li>

  <li class="active">

    <a href="<?php print($base_url) ?>/use/research/analysis/archive/list">

      マーケットウォッチ

    </a>

  </li>
</ol>


<form action="<?php print($base_url.'/'.$view_path.'/post') ?>"
      method="post"
      enctype="multipart/form-data">

  <div class="btn-group-vertical center-block">

    <input class="btn btn-primary confirm"
           type="submit"
           name="display_pdf"

    <?php if ((int)$this->getUserData()['account_contract_id'] > 2): ?>

           value="選択した商品をPDF形式でダウンロード（ダウンロード後はF5キーを押してください）"

    <?php else: ?>

           disabled="disabled"
           value="選択した商品をPDF形式でダウンロード（プレミアプラン以上）"

    <?php endif ?>

    />

  </div>

<div class="table-responsive">

  <table id="dt"
         class="table display table-bordered table-hover"
         cellspacing="0"
         width="100%">

    <thead>

      <tr>

        <th rowspan="3" class="text-center info">
          <span class="text-primary">
            <input type="checkbox" id="check-all" />
          </span>
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

      </tr>

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
                  'view_path' => $view_path,
                  'ym' => $ym,
                )
              );
      ?>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>

<input type="hidden" name="_token" value="<?php print $this->escape($_token); ?>" />

</form>
