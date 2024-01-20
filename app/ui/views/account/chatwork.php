<tr>

  <th class="text-center info">

    <span class="text-primary">ChatWork</span>

  </th>

  <td class="active" style="width: <?php print($width) ?>px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php

          print(
            $this->render(
              'account/chatwork/api_admin_token',
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
              'account/chatwork/api_tokens',
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
              'account/chatwork/members_admin_ids',
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
              'account/chatwork/members_member_ids',
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
              'account/chatwork/members_readonly_ids',
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
              'account/chatwork/members_readonly_names',
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
              'account/chatwork/work_place_members',
              array(
                'width'   => 600,
                'account' => $account,
              )
            )
          )

        ?>

        <?php

          if (true) {

            print(
              $this->render(
                'account/chatwork/work_place_room1_id',
                array(
                  'width'   => 600,
                  'account' => $account,
                )
              )
            );

          }

        ?>

        <?php

          print(
            $this->render(
              'account/chatwork/misc',
              array(
                'width'   => 600,
                'account' => $account,
              )
            )
          )

        ?>

      </tbody>
    </table>

  </td>

</tr>
