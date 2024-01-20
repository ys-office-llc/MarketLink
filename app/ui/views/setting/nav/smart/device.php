<style>

p {
  text-indent: 1em;
}

</style>

<p>商品管理</p>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/my/pattern/list">マイパターン一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/my/pattern/get">マイパターン作成</a></li>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/maker/list">メーカー一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/maker/get">メーカー作成</a></li>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/category/list">カテゴリー一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/category/get">カテゴリー作成</a></li>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/grade/list">グレード一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/grade/get">グレード作成</a></li>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/description/list">カスタム一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/description/get">カスタム作成</a></li>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/description/cosmetics/list">外観一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/description/cosmetics/get">外観作成</a></li>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/description/optics/list">光学系一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/description/optics/get">光学系作成</a></li>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/description/functions/list">機能一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/description/functions/get">機能作成</a></li>

<li class="divider"></li>

<li><a href="<?php print $base_url; ?>/setting/item/accessories/list">付属品一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/accessories/get">付属品作成</a></li>

<li class="divider"></li>

<p>出品ページ</p>
<li><a href="<?php print $base_url; ?>/setting/item/template/yahoo/auctions/list">ヤフオク一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/template/yahoo/auctions/get">ヤフオク作成</a></li>

<li><a href="<?php print $base_url; ?>/setting/item/template/ebay/us/list">eBay一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/template/ebay/us/get">eBay作成</a></li>

<li><a href="<?php print $base_url; ?>/setting/item/template/amazon/jp/list">Amazon.co.jp一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/template/amazon/jp/get">Amazon.co.jp作成</a></li>

  <?php if ($this->getUserData()['account_auto_exhibition_id']  > 0): ?>

<li class="divider"></li>

<p>出品条件</p>
<li><a href="<?php print $base_url; ?>/setting/item/condition/yahoo/auctions/list">ヤフオク一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/condition/yahoo/auctions/get">ヤフオク作成</a></li>

<li><a href="<?php print $base_url; ?>/setting/item/condition/ebay/us/list">eBay一覧</a></li>
<li><a href="<?php print $base_url; ?>/setting/item/condition/ebay/us/get">eBay作成</a></li>

  <?php endif; ?>
