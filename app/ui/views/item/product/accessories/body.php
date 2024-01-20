<tr>
  <th class="text-center info">
    <span class="text-primary">ボディ</span>
  </th>

  <td class="active" style="width: 700px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php print $this->render('item/product/accessories/body/common',
                      array(
                        'item' => $item,
                      )
              ); ?>

        <?php print $this->render('item/product/accessories/body/film',
                      array(
                        'item' => $item,
                      )
              ); ?>

        <?php print $this->render('item/product/accessories/body/digital',
                      array(
                        'item' => $item,
                      )
              ); ?>

      </tbody>
      </tbody>
    </table>
  </td>
</tr>
