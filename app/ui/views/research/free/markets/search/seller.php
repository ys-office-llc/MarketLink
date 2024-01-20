<tr>

  <th class="text-center info">

    <span class="text-primary">

      出品者

    </span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/seller/include',
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
            'research/free/markets/search/seller/not_include',
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
            'research/free/markets/search/seller/rating',
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
