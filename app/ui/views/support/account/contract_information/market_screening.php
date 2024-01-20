<tr>

  <th class="text-center info">

    <span class="text-primary">相場スクリーニング</span>

  </th>

  <td class="text-left active">

    <div class="btn-group"
         data-toggle="buttons">

    <?php if ($account['market_screening'] === 'enable'): ?>

      <label class="btn btn-default active"

    <?php else: ?>

      <label class="btn btn-default"

    <?php endif; ?>

             style="width: 160px">

        <input type="radio"
               name="market_screening"
               value="enable"
               autocomplete="off"

             <?php if ($account['market_screening'] === 'enable'): ?>

                 checked />

             <?php else: ?>

                 />

             <?php endif; ?>

        契約

      </label>

    <?php if ($account['market_screening'] === 'disable'): ?>

      <label class="btn btn-default active"

    <?php else: ?>

      <label class="btn btn-default"

    <?php endif; ?>

             style="width: 160px">

        <input type="radio"
               name="market_screening"
               value="disable"
               autocomplete="off"

             <?php if ($account['market_screening'] === 'disable'): ?>

               checked />

             <?php else: ?>

               />

             <?php endif; ?>

        解約

      </label>

    </div>

  </td>
</tr>
