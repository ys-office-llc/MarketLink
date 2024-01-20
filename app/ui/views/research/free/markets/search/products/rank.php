<tr>

  <th class="text-center info">

    <span class="text-primary">ランク</span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print $this->render(
            'research/free/markets/search/products/rank/mercari',
            array(
              'research_free_markets_search' => $research_free_markets_search
            ))

        ?>

        <?php

          print $this->render(
            'research/free/markets/search/products/rank/rakuma',
            array(
              'research_free_markets_search' => $research_free_markets_search
          ))

        ?>

        <?php

          print $this->render(
            'research/free/markets/search/products/rank/fril',
            array(
              'research_free_markets_search' => $research_free_markets_search
            ))

        ?>

      </tbody>
    </table>

  </td>

</tr>
