<tr>

  <th class="text-center info">

    <span class="text-primary">商品管理</span>

  </th>

  <td class="text-left active">

    <div class="btn-group"
         data-toggle="buttons">

    <?php if ($account['merchandise_management'] === 'enable'): ?>

      <label class="btn btn-default active"

    <?php else: ?>

      <label class="btn btn-default"

    <?php endif; ?>

             style="width: 160px">

        <input type="radio"
               name="merchandise_management"
               value="enable"
               autocomplete="off"

             <?php if ($account['merchandise_management'] === 'enable'): ?>

                 checked />

             <?php else: ?>

                 />

             <?php endif; ?>

        契約

      </label>

    <?php if ($account['merchandise_management'] === 'disable'): ?>

      <label class="btn btn-default active"

    <?php else: ?>

      <label class="btn btn-default"

    <?php endif; ?>

             style="width: 160px">

        <input type="radio"
               name="merchandise_management"
               value="disable"
               autocomplete="off"

             <?php if ($account['merchandise_management'] === 'disable'): ?>

               checked />

             <?php else: ?>

               />

             <?php endif; ?>

        解約

      </label>

    </div>

  </td>
</tr>
