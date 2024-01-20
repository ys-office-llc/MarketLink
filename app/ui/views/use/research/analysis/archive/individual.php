<tr>

  <td class="text-center active">

    <input type="checkbox"
           name="id[<?php print $use_research_analysis_archive['id'] ?>]"
           class="bar"
           value="" />

  </td>

  <td class="text-center active">

    <a href="<?php print $base_url; ?>/<?php print $view_path ?>/get/<?php print $use_research_analysis_archive['research_analysis_id'].'/'.$ym ?>"
       target="_blank">

    <?php
      print
        $this->escape(
          date(
            'm-d',
            strtotime(
              $use_research_analysis_archive['created_at']
            )
          )
        ); ?>

    </a>

  </td>

  <td class="text-center active">

    <img src="<?php print $use_research_analysis_archive['ebay_us_img_uri_sold_highest'] ?>"
         alt="<?php print $use_research_analysis_archive['name'] ?>"
         width="122"
         height="75" />

  </td>

  <td class="text-center active">

    <a href="<?php print $base_url; ?>/research/analysis/get/<?php print $use_research_analysis_archive['research_analysis_id'] ?>"
       target="_blank">

      <?php print $this->escape($use_research_analysis_archive['name']); ?>

    </a>

  </td>

  <!-- eBay US [BEGIN] -->

  <td class="text-center active">

    <a href="<?php print
               $use_research_analysis_archive[
                 'ebay_us_uri_sold_lowest']
             ?>"
       target="_blank">
      <?php print
        $use_research_analysis_archive['ebay_us_min_price']
      ?>
    </a>

  </td>

  <td class="text-center active">

    <a href="<?php print
               $use_research_analysis_archive[
                 'ebay_us_uri_sold_highest']
             ?>"
       target="_blank">
      <?php print $use_research_analysis_archive['ebay_us_max_price'] ?>
    </a>

  </td>

  <td class="text-center active">

    <?php print $use_research_analysis_archive['ebay_us_avg_price'] ?>

  </td>

  <td class="text-center active">

    <a href="<?php print
               $use_research_analysis_archive[
                 'ebay_us_uri_active']
             ?>"
       target="_blank">
    <?php print $use_research_analysis_archive['ebay_us_numof_active'] ?>
    </a>

  </td>

  <td class="text-center active">

    <a href="<?php print
               $use_research_analysis_archive[
                 'ebay_us_uri_sold_end']
             ?>"
       target="_blank">
    <?php print $use_research_analysis_archive['ebay_us_numof_sold_m1'] ?>
    </a>

  </td>

  <td class="text-center active">

    <?php print $use_research_analysis_archive['ebay_us_index'] ?>

  </td>

  <!-- eBay US [END] -->

  <!-- Yahoo Auctions [BEGIN] -->

  <td class="text-center active">

    <a href="<?php print
               $use_research_analysis_archive[
                 'yahoo_auctions_uri_sold_lowest']
             ?>"
       target="_blank">
    <?php
      print number_format(
        $use_research_analysis_archive['yahoo_auctions_min_price']
      ) ?>
    </a>

  </td>

  <td class="text-center active">

    <a href="<?php print
               $use_research_analysis_archive[
                 'yahoo_auctions_uri_sold_highest']
             ?>"
       target="_blank">
    <?php
      print number_format(
        $use_research_analysis_archive['yahoo_auctions_max_price']
      ) ?>
    </a>

  </td>

  <td class="text-center active">

    <?php
      print number_format(
        $use_research_analysis_archive['yahoo_auctions_avg_price']
      ) ?>

  </td>

  <td class="text-center active">

    <a href="<?php print
               $use_research_analysis_archive[
                 'yahoo_auctions_uri_selling']
             ?>"
       target="_blank">
    <?php print $use_research_analysis_archive['yahoo_auctions_numof_selling'] ?>
    </a>

  </td>

  <td class="text-center active">

    <a href="<?php print
               $use_research_analysis_archive[
                 'yahoo_auctions_uri_sold_end']
             ?>"
       target="_blank">
    <?php print $use_research_analysis_archive['yahoo_auctions_numof_sold_m1'] ?>
    </a>

  </td>

  <td class="text-center active">

    <?php print $use_research_analysis_archive['yahoo_auctions_index'] ?>

  </td>

  <!-- Yahoo Auctions [END] -->

  <!-- Amazon Japan [BEGIN] -->

  <td class="text-center active">

    <a href="https://www.amazon.co.jp/gp/offer-listing/<?php print $use_research_analysis_archive['amazon_jp_asin'] ?>/ref=dp_olp_used?ie=UTF8&condition=used"
       target="_blank">

    <?php
      print number_format(
        $use_research_analysis_archive['amazon_jp_lowest_offer_listing_price']
      ) ?>

    </a>

  </td>

  <td class="text-center active">

    <a href="http://mnrate.com/item/aid/<?php print $use_research_analysis_archive['amazon_jp_asin'] ?>/"
       target="_blank">

    <?php
      print number_format(
        $use_research_analysis_archive['amazon_jp_rankings']
      ) ?>

    </a>

  </td>

  <!-- Amazon Japan [END] -->

</tr>
