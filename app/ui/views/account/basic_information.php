<tr>

  <th class="text-center info">

    <span class="text-primary">基本情報</span>

  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/basic_information/user_name',
              array(
                'width'   => 600,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/basic_information/user_name_ja',
              array(
                'width'   => 600,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          print(
            $this->render(
              'account/basic_information/password',
              array(
                'width'   => 600,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          if (false) { 

            print(
              $this->render(
                'account/basic_information/agent_user_name',
                array(
                  'width'   => 600,
                  'account' => $account,
                )
              )
            );

          }

        ?>

        <?php

          if (true) { 

            print(
              $this->render(
                'account/basic_information/agent_password',
                array(
                  'width'   => 600,
                  'account' => $account,
                )
              )
            );

          }

        ?>

    </table>

  </td>

</tr>
