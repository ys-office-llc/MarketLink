<?php if (strlen($item['yahoo_auctions_page']) > 0 or
          strlen($item['ebay_us_page']) > 0 or
          strlen($item['amazon_jp_page']) > 0): ?>

  <div class="panel-group" id="accordion">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title text-center">
          <a data-toggle="collapse"
             data-parent="#accordion"
             href="#collapsePreview">
            プレビュー
          </a>
        </h4>
      </div>
  
      <div id="collapsePreview" class="panel-collapse collapse">
        <div class="panel-body">
          <?php print $this->render('item/page/yahoo/auctions',
                        array('item' => $item)); ?>
          <?php print $this->render('item/page/ebay/us',
                        array('item' => $item)); ?>
          <?php print $this->render('item/page/amazon/jp',
                        array('item' => $item)); ?>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>
