<?php for ($index = 1; $index <= 3; $index++): ?>

<?php if (strlen($bids[sprintf("img_image%d", $index)]) > 0): ?>

  <a href="#<?php printf("img_image%d", $index) ?>" data-toggle="modal">
    <img src="<?php printf("%s",
                      $bids[sprintf("img_image%d", $index)]) ?>"
         width="100"
         height="100"
    />
  </a>

  <div class="modal fade" id="<?php printf("img_image%d", $index) ?>">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <button class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">閉じる</span>
          </button>
          <h4 class="modal-title">
            <?php printf("img_image%d", $index) ?>
          </h4>
          <img src="<?php printf("%s",
                      $bids[sprintf("img_image%d", $index)]) ?>"
               class="img-responsive">
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>
<?php endfor; ?>
