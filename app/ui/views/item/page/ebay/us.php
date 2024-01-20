<?php if (strlen($item['ebay_us_page']) > 0): ?>
  <div class="btn-group-vertical center-block">

    <button class="btn btn-primary"
            data-toggle="modal"
            data-target="#preview_ebay_us">
      eBayプレビュー
    </button>

    <button class="btn btn-primary"
            data-toggle="modal"
            data-target="#html_ebay_us">
      eBay HTML
    </button>
  </div>

  <div class="modal fade" id="preview_ebay_us">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">閉じる</span>
          </button>
          <h4 class="modal-title">
            <?php $item['ebay_us_product_name'] ?>
          </h4>
        </div>

        <div class="modal-body">

          <textarea id="ebay_us_textarea" name="ebay_us_textarea" rows="5" cols="120"><?php print $item['ebay_us_page']  ?></textarea>

          <div id="ebay_us_htmlarea"></div>

        </div>

        <div class="modal-footer">
           <p><?php $item['ebay_us_product_name'] ?></p>
          <button class="btn btn-primary btn-sm"
                  data-dismiss="modal">
            閉じる
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="html_ebay_us">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">閉じる</span>
          </button>
          <h4 class="modal-title">
            <?php $item['ebay_us_product_name'] ?>
          </h4>
        </div>

        <div class="modal-body">
          <pre><?php print $this->escape($item['ebay_us_page']) ?></pre>
        </div>

        <div class="modal-footer">
           <p><?php $item['ebay_us_product_name'] ?></p>
          <button class="btn btn-primary btn-sm"
                  data-dismiss="modal">
            閉じる
          </button>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>
