<tr>
  <th class="text-center info">
    <span class="text-primary">付属品</span>
  </th>

  <td class="active" style="width: 700px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php print $this->render('item/product/accessories/common',
                      array(
                        'item' => $item,
                      )
              ); ?>

        <?php print $this->render('item/product/accessories/lens',
                      array(
                        'item' => $item,
                      )
              ); ?>

        <?php print $this->render('item/product/accessories/body',
                      array(
                        'item' => $item,
                      )
              ); ?>

      </tbody>
    </table>
  </td>
</tr>
