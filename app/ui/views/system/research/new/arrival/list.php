<?php $this->setPageTitle('title', 'ヤフオク検索条件一覧') ?>
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
          <span class="text-primary">検索キーワード</span>
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
