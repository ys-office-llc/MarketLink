<tr>
  <th class="text-center info">
    <span class="text-primary">ヤフオク</span>
  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php print $this->render('item/title/yahoo/auctions/decoration',
                      array(
                        'item' => $item,
                      )
              ); ?>

        <?php print $this->render('item/title/yahoo/auctions/after_decoration',
                      array(
                        'item' => $item,
                        'size' => $size,
                      )
              ); ?>

      </tbody>
    </table>
  </td>
</tr>
