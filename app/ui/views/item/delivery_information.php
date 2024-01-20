<?php if ($payment or $shipment): ?>

<tr>

  <th class="text-center info">

    <span class="text-primary">

      配送情報

    </span>

  </th>

  <td class="active" style="width: 800px">

    <table class="table table-bordered table-condensed">
      <tbody>

        <?php print $this->render('item/delivery_information/ems',
                      array(
                        'item' => $item,
                      )
              ); ?>

      </tbody>
    </table>
  </td>
</tr>

<?php endif; ?>
