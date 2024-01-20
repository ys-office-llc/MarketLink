<tr>

  <td class="text-center active">

    <input type="checkbox"
           name="id[<?php print $bids['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <td class="text-center active">

    <img src="<?php print $bids['img_image1'] ?>"  width="100" height="80">

  <td class="text-center active">

    <a href="<?php print $bids['auction_item_url'] ?>"
       target="_blank">
      <?php print $bids['title'] ?>
    </a>

    <br />

    出品者：<a href="http://sellinglist.auctions.yahoo.co.jp/user/<?php print $bids['seller_id'] ?>" target="_blank"><?php print $bids['seller_id'] ?></a>
    （評価：<?php print $bids['rating_point'] ?> 良い：<?php print $bids['rating_total_good_rating'] ?> 悪い：<a href="http://auctions.yahoo.co.jp/jp/show/rating?userID=<?php print $bids['seller_id'] ?>&filter=-1#comment_list" target="_blank"><?php print $bids['rating_total_bad_rating'] ?>）</a>

    <br />

<?php if (strlen($bids['option_store_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_store_icon_url'] ?>">

<?php endif; ?>

<?php if (strlen($bids['option_check_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_check_icon_url'] ?>">

<?php endif; ?>

<?php  if (strlen($bids['option_new_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_new_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_escrow_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_escrow_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_featured_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_featured_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_free_shipping_icon_url']) > 0): ?>
    <img src="<?php print $bids['option_free_shipping_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_wrapping_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_wrapping_icon_url'] ?>">

<?php   endif; ?>

<?php  if (strlen($bids['option_buynow_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_buynow_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_easy_payment_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_easy_payment_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_gift_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_gift_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_bundle_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_bundle_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_item_status_new_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_item_status_new_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_y_bank_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_y_bank_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_english_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_english_icon_url'] ?>">

<?php  endif; ?>

<?php  if (strlen($bids['option_star_club_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_star_club_icon_url'] ?>">

<?php   endif; ?>

<?php   if (strlen($bids['option_charity_icon_url']) > 0): ?>

    <img src="<?php print $bids['option_charity_icon_url'] ?>">

<?php   endif; ?>

  </td>

  <td class="text-center active">

    <?php print '\\'.number_format($bids['initprice']) ?>

  </td>

  <td class="text-center active">

    <?php print '\\'.number_format($bids['price']) ?>

    <br>

    <?php if (intval($bids['taxin_price'])): ?>

    （税込み：<?php print '\\'.number_format($bids['taxin_price']) ?>）

    <?php endif; ?>

  </td>

  <td class="text-center active">

    <?php print '\\'.number_format($bids['bids_price']) ?>

  </td>

  <td class="text-center active">

    <?php print '\\'.number_format($bids['bidorbuy']) ?>

    <br>

    <?php if (intval($bids['taxin_bidorbuy'])): ?>

    （税込み：<?php print '\\'.number_format($bids['taxin_bidorbuy']) ?>）

    <?php endif; ?>

  </td>

  <td class="text-center active">
    <?php print $bids['bids'] ?>
  </td>

  <td class="text-center active"><?php print $this->remainingTime($bids['end_time']) ?></td>

  <td class="text-center active">

  <div class="panel-group" id="accordion">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h4 class="panel-title">
          <a data-toggle="collapse"
             data-parent="#accordion"
             href="#collapseMarket<?php print $index ?>">
            確認
          </a>
        </h4>
      </div>

      <div id="collapseMarket<?php print $index ?>" class="panel-collapse collapse">
        <div class="panel-body">

    <div class="btn-group-vertical center-block">

      <a href="http://mnrate.com/search?i=All&kwd=<?php print $this->mbDelete($bids['title']) ?>"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">モノレート
      </a>

      <a href="http://auctions.search.yahoo.co.jp/search?ei=UTF-8&p=<?php print $this->mbDelete($bids['title']) ?>&auccat=0&istatus=2&price_type=currentprice&slider=0&tab_ex=commerce&s1=end&o1=a"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">ヤフオク現在
      </a>

      <a href="http://closedsearch.auctions.yahoo.co.jp/closedsearch?ei=UTF-8&va=<?php print $this->mbDelete($bids['title']) ?>&auccat=0&price_type=currentprice&s1=end&o1=d&slider=0&tab_ex=commerce&istatus=2"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">ヤフオク過去
      </a>

      <a href="http://www.ebay.com/sch/Cameras-Photo-/625/i.html?_mPrRngCbx=1&_samilow=&_samihi=&_sadis=15&_stpos=&_sop=15&_dmd=1&_ipg=200&_nkw=<?php print $this->mbDelete($bids['title']) ?>&rt=nc&LH_BIN=1&LH_ItemCondition=4"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">eBay Active
      </a>

      <a href="http://www.ebay.com/sch/i.html?_nkw=<?php print $this->mbDelete($bids['title']) ?>&_in_kw=1&_sacat=625&LH_Sold=1&_mPrRngCbx=1&s&_samilow=&_samihi=&_sadis=15&_stpos=&_sargn=-1%%26saslc%%3D1&_fsradio2=%%26LH_LocatedIn%%3D1&_salic=104&LH_SubLocation=1&_sop=13&_dmd=1&_ipg=200&LH_Complete=1&LH_BIN=1&LH_ItemCondition=4"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">eBay Sold
      </a>

    </div>

        </div>
      </div>
    </div>
  </div>


  </td>

  <td class="text-center active">

      <input class="btn btn-primary"
             type="submit"
             name="delete_by_id[<?php print $bids['id'] ?>]"
             value="対象削除" />

    </div>

  </td>

</tr>
