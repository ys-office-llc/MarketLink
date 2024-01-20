<tr>

  <td class="text-center active">

    <input type="checkbox"
           name="id[<?php print $research_free_markets_watch['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <td class="text-center active">

    <a href="<?php
         print(
           $base_url.
           '/research/free/markets/search/get/'.
           $research_free_markets_watch['research_free_markets_search_id']
         )
       ?>"
       target="_blank">

  <?php if ($research_free_markets_watch['market'] === 'mercari'): ?>

    メルカリ

  <?php elseif ($research_free_markets_watch['market'] === 'rakuma'): ?>

    ラクマ

  <?php elseif ($research_free_markets_watch['market'] === 'fril'): ?>

    フリル

  <?php endif; ?>

    </a>

  </td>

  <td class="text-center active">

    <img src="<?php print($research_free_markets_watch['img_uri']) ?>"
         width="100" 
         height="100"
    >

  </td>

  <td class="text-center active">

    <a href="<?php print $research_free_markets_watch['link'] ?>"
       target="_blank">
      <?php print $research_free_markets_watch['name'] ?>
    </a>

    <br />

    出品者：<a href="<?php print($research_free_markets_watch['seller_uri']) ?>" target="_blank"><?php print($research_free_markets_watch['seller']) ?></a>
    （評価　良い：<?php print($research_free_markets_watch['rating_good']) ?>／普通：<?php print($research_free_markets_watch['rating_normal']) ?>／悪い：<?php print($research_free_markets_watch['rating_bad']) ?>）</a>

  </td>

  <td class="text-center active">

    <?php print $research_free_markets_watch['rank'] ?>

  </td>

  <td class="text-center active">
    <?php print number_format($research_free_markets_watch['price']) ?>
  </td>

  <td class="text-center active">

    <?php print $research_free_markets_watch['stock'] ?>

  </td>

  <td class="text-center active">

    <?php print $research_free_markets_watch['update_at'] ?>

  </td>

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

      <div id="collapseMarket<?php print $index ?>"
           class="panel-collapse collapse">

        <div class="panel-body">

    <div class="btn-group-vertical center-block">

      <a href="http://mnrate.com/search?i=All&kwd=<?php print $this->mbDelete($research_free_markets_watch['name']) ?>"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">モノレート
      </a>

      <a href="http://auctions.search.yahoo.co.jp/search?ei=UTF-8&p=<?php print $this->mbDelete($research_free_markets_watch['name']) ?>&auccat=0&istatus=2&price_type=currentprice&slider=0&tab_ex=commerce&s1=end&o1=a"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">ヤフオク現在
      </a>

      <a href="http://closedsearch.auctions.yahoo.co.jp/closedsearch?ei=UTF-8&va=<?php print $this->mbDelete($research_free_markets_watch['name']) ?>&auccat=0&price_type=currentprice&s1=end&o1=d&slider=0&tab_ex=commerce&istatus=2"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">ヤフオク過去
      </a>

      <a href="http://www.ebay.com/sch/Cameras-Photo-/625/i.html?_mPrRngCbx=1&_samilow=&_samihi=&_sadis=15&_stpos=&_sop=15&_dmd=1&_ipg=200&_nkw=<?php print $this->mbDelete($research_free_markets_watch['name']) ?>&rt=nc&LH_BIN=1&LH_ItemCondition=4"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">eBay Active
      </a>

      <a href="http://www.ebay.com/sch/i.html?_nkw=<?php print $this->mbDelete($research_free_markets_watch['name']) ?>&_in_kw=1&_sacat=625&LH_Sold=1&_mPrRngCbx=1&s&_samilow=&_samihi=&_sadis=15&_stpos=&_sargn=-1%%26saslc%%3D1&_fsradio2=%%26LH_LocatedIn%%3D1&_salic=104&LH_SubLocation=1&_sop=13&_dmd=1&_ipg=200&LH_Complete=1&LH_BIN=1&LH_ItemCondition=4"
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

    <div class="btn-group-vertical center-block">

      <input class="btn btn-primary"
             type="submit"
             name="delete_by_id[<?php print $research_free_markets_watch['id'] ?>]"
             value="対象削除" />

    </div>

  </td>

</tr>
