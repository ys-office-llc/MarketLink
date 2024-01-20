<tr>

  <th class="text-center info">

    <span class="text-primary">

      タイトル

    </span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

    <?php

      if (true) {

        print(
          $this->render(
            'research/free/markets/search/products/title/include_everything',
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
            'research/free/markets/search/products/title/include_either',
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
            'research/free/markets/search/products/title/not_include',
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
