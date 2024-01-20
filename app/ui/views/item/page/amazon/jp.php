<?php if ($item['amazon_jp_state_id'] > 0  and
          strlen($item['amazon_jp_page']) > 0): ?>

  <div class="btn-group-vertical center-block">

    <button class="btn btn-primary"
            data-toggle="modal"
            data-target="#preview_amazon_jp">

      Amazon.co.jpプレビュー

    </button>

    <button class="btn btn-primary"
            data-toggle="modal"
            data-target="#html_amazon_jp">

      Amazon.co.jp HTML

    </button>
  </div>

  <div class="modal fade" id="preview_amazon_jp">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">閉じる</span>
          </button>
          <h4 class="modal-title">
            <?php $item['amazon_jp_product_name'] ?>
          </h4>
        </div>

        <div class="modal-body">
          <?php print $item['amazon_jp_page'] ?>
        </div>

        <div class="modal-footer">
           <p><?php $item['amazon_jp_product_name'] ?></p>
          <button class="btn btn-primary btn-sm"
                  data-dismiss="modal">
            閉じる
          </button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="html_amazon_jp">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <button class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">閉じる</span>
          </button>
          <h4 class="modal-title">
            <?php $item['amazon_jp_product_name'] ?>
          </h4>
        </div>

        <div class="modal-body">
          <pre><?php print $this->escape($item['amazon_jp_page']) ?></pre>
        </div>

        <div class="modal-footer">
           <p><?php $item['amazon_jp_product_name'] ?></p>
          <button class="btn btn-primary btn-sm"
                  data-dismiss="modal">
            閉じる
          </button>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>
