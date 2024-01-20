<tr>

  <td class="text-center active">

    <input type="checkbox"
           name="id[<?php print $research_stores['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <td class="text-center active">

    <a href="<?php $base_url ?>/research/new/arrival/get/<?php print $research_stores['research_new_arrival_id'] ?>"
       target="_blank">

  <?php if ($research_stores['store'] === 'kitamura'): ?>

    カメラのキタムラ

  <?php elseif ($research_stores['store'] === 'fujiya_camera'): ?>

    フジヤカメラ

  <?php elseif ($research_stores['store'] === 'map_camera'): ?>

    Map Camera

  <?php elseif ($research_stores['store'] === 'champ_camera'): ?>

    チャンプカメラ

  <?php elseif ($research_stores['store'] === 'camera_no_naniwa'): ?>

    カメラのナニワ

  <?php elseif ($research_stores['store'] === 'hardoff'): ?>

    ハードオフ

  <?php endif; ?>

    </a>

  </td>

  <td class="text-center active">

    <a href="<?php print $research_stores['link'] ?>"
       target="_blank">
      <?php print $research_stores['name'] ?>
    </a>

  </td>

  <td class="text-center active">

    <?php print $research_stores['rank'] ?>

  </td>

  <td class="text-center active">
    <?php print number_format($research_stores['price']) ?>
  </td>

  <td class="text-center active">

    <?php print $research_stores['remarks'] ?>

  </td>

  <td class="text-center active">

    <?php print $research_stores['accessories'] ?>

  </td>

  <td class="text-center active">

    <?php print $research_stores['stock'] ?>

  </td>

  <td class="text-center active">

    <?php print $research_stores['update_at'] ?>

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

      <a href="http://mnrate.com/search?i=All&kwd=<?php print $this->mbDelete($research_stores['name']) ?>"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">モノレート
      </a>

      <a href="http://auctions.search.yahoo.co.jp/search?ei=UTF-8&p=<?php print $this->mbDelete($research_stores['name']) ?>&auccat=0&istatus=2&price_type=currentprice&slider=0&tab_ex=commerce&s1=end&o1=a"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">ヤフオク現在
      </a>

      <a href="http://closedsearch.auctions.yahoo.co.jp/closedsearch?ei=UTF-8&va=<?php print $this->mbDelete($research_stores['name']) ?>&auccat=0&price_type=currentprice&s1=end&o1=d&slider=0&tab_ex=commerce&istatus=2"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">ヤフオク過去
      </a>

      <a href="http://www.ebay.com/sch/Cameras-Photo-/625/i.html?_mPrRngCbx=1&_samilow=&_samihi=&_sadis=15&_stpos=&_sop=15&_dmd=1&_ipg=200&_nkw=<?php print $this->mbDelete($research_stores['name']) ?>&rt=nc&LH_BIN=1&LH_ItemCondition=4"
       class="btn btn-primary"
       target="_blank"
       style="width: 120px;">eBay Active
      </a>

      <a href="http://www.ebay.com/sch/i.html?_nkw=<?php print $this->mbDelete($research_stores['name']) ?>&_in_kw=1&_sacat=625&LH_Sold=1&_mPrRngCbx=1&s&_samilow=&_samihi=&_sadis=15&_stpos=&_sargn=-1%%26saslc%%3D1&_fsradio2=%%26LH_LocatedIn%%3D1&_salic=104&LH_SubLocation=1&_sop=13&_dmd=1&_ipg=200&LH_Complete=1&LH_BIN=1&LH_ItemCondition=4"
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

      <a href="<?php $base_url ?>/research/yahoo/auctions/search/get/<?php print $research_stores['store'] ?>/<?php print $research_stores['id'] ?>"
         class="btn btn-primary"
         target="_blank">

        ヤフオク検索登録

      </a>
      <a href="<?php $base_url ?>/research/analysis/get/<?php print $research_stores['store'] ?>/<?php print $research_stores['id'] ?>"
         class="btn btn-primary"
         target="_blank">

        マーケット検索登録

      </a>

      <a href="<?php $base_url ?>/research/new/arrival/get/<?php print $research_stores['store'] ?>/<?php print $research_stores['id'] ?>"
         class="btn btn-primary"
         target="_blank">

        ストア新着通知登録

      </a>

      <input class="btn btn-primary"
             type="submit"
             name="delete_by_id[<?php print $research_stores['id'] ?>]"
             value="対象削除" />

    </div>

  </td>

</tr>
