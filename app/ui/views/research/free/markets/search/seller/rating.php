<tr>

  <th class="text-center info">

    <span class="text-primary">

      評価

    </span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/seller/rating/good',
            array(
              'width' => 600,
              'research_free_markets_search' => $research_free_markets_search,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/seller/rating/normal',
            array(
              'width' => 600,
              'research_free_markets_search' => $research_free_markets_search,
            )
          )
        );

      }

    ?>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/seller/rating/bad',
            array(
              'width' => 600,
              'research_free_markets_search' => $research_free_markets_search,
            )
          )
        );

      }

    ?>

      </tbody>
    </table>
  </td>
</tr>
