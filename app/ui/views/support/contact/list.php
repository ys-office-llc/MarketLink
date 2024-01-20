<?php $this->setPageTitle('title', '出品ページ一覧') ?>
<?php print $this->render('nav', array('' => array())); ?>
<?php print $this->render($view_path . '/bar', array('view_path' => $view_path)); ?>

<div class="table-responsive">
  <table id="dt"
         class="table display table-bordered table-hover"
         cellspacing="0"
         width="100%">

    <thead>
      <tr>
        <th class="text-center info"><span class="text-primary">タイトル</span></th>
      </tr>
    </thead>

    <tbody>

      <?php foreach ($contacts as $contact): ?>
      <?php   print $this->render(
                $view_path . '/individual',
                array(
                  'contact' => $contact,
                  'view_path'   => $view_path,
                )
              );
      ?>
      <?php endforeach; ?>

    </tbody>
  </table>

</div>
