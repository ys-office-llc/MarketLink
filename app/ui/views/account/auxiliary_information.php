<tr>

  <th class="text-center info">

    <span class="text-primary">補助情報</span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/auxiliary_information/proxy_ipv4addr_pair_static',
              array(
                'width'   => 600,
                'account' => $account,
              )
            )
          )

        ?>

    </table>

  </td>

</tr>
