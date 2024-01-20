<tr>
  <th class="text-center info">
    <span class="text-primary">eBay</span>
  </th>

  <td class="active">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php print $this->render('item/title/ebay/com/decoration',
                      array(
                        'item' => $item,
                      )
              ); ?>

        <?php print $this->render('item/title/ebay/com/after_decoration',
                      array(
                        'item' => $item,
                        'size' => $size,
                      )
              ); ?>

      </tbody>
    </table>
  </td>
</tr>
