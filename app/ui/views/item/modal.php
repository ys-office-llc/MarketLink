<?php for ($index = 1; $index <= 12; $index++): ?>

<?php   if (strlen($item[sprintf("thumbnail_image_%02d", $index)]) > 0): ?>

  <a href="#<?php printf("image_%02d", $index) ?>" data-toggle="modal">
    <img src="<?php printf("%s/%s",
                      $url,
                      $item[sprintf("thumbnail_image_%02d", $index)]) ?>">
  </a>

  <div class="modal fade" id="<?php printf("image_%02d", $index) ?>">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-body">
          <button class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">閉じる</span>
          </button>
          <h4 class="modal-title">
            <?php printf("image_%02d", $index) ?>
          </h4>
          <img src="<?php printf("%s/%s",
                      $url,
                      $item[sprintf("image_%02d", $index)]) ?>"
               class="img-responsive">
        </div>
      </div>
    </div>
  </div>

 <input type="hidden"
        name="<?php printf("image_%02d", $index) ?>"
        value="<?php print $item[sprintf("image_%02d", $index)] ?>" />
 <input type="hidden"
        name="<?php printf("thumbnail_image_%02d", $index) ?>"
        value="<?php print $item[sprintf("thumbnail_image_%02d", $index)] ?>" />

<?php   endif; ?>

<?php endfor; ?>
