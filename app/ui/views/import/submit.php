<div class="btn-group-vertical center-block">

<?php if (isset($import) and count($import) > 0): ?>

  <input class="btn btn-primary"
         type="submit"
         name="create"
         value="読み込んだデータを反映する" />

<?php else: ?>

  <input class="btn btn-primary"
         type="submit"
         name="import_research_yahoo_auctions_search"
         value="ヤフオク検索条件取り込み" />

  <input class="btn btn-primary"
         type="submit"
         name="import_research_analysis"
         value="マーケット検索条件取り込み" />

  <input class="btn btn-primary"
         type="submit"
         name="import_research_new_arrival"
         value="ストア新着通知取り込み" />

<?php endif; ?>

</div>
