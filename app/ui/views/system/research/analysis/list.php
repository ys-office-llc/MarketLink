<?php $this->setPageTitle('title', 'システム共通商品分析一覧') ?>
<?php print $this->render('nav', array('' => array())); ?>
<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<div class="table-responsive">
  <table id="dt"
         class="table display table-bordered table-hover"
         cellspacing="0"
         width="100%">

    <thead>

      <tr>

        <th class="text-center info">
          <span class="text-primary">商品名</span>
        </th>

      </tr>

    </thead>

    <tbody>

      <?php foreach ($system_research_analysiss as $system_research_analysis): ?>
      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'system_research_analysis' => $system_research_analysis,
                  'view_path' => $view_path,
                )
              );
      ?>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>
