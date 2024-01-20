<tr>

  <th class="text-center info">

    <span class="text-primary">実験機能</span>

  </th>

  <td class="text-left active">

    <div class="btn-group"
         data-toggle="buttons">

    <?php if ($account['use_experimental_function'] === 'enable'): ?>

      <label class="btn btn-default active"

    <?php else: ?>

      <label class="btn btn-default"

    <?php endif; ?>

             style="width: 160px">

        <input type="radio"
               name="use_experimental_function"
               value="enable"
               autocomplete="off"

             <?php if ($account['use_experimental_function'] === 'enable'): ?>

                 checked />

             <?php else: ?>

                 />

             <?php endif; ?>

        有効

      </label>

    <?php if ($account['use_experimental_function'] === 'disable'): ?>

      <label class="btn btn-default active"

    <?php else: ?>

      <label class="btn btn-default"

    <?php endif; ?>

             style="width: 160px">

        <input type="radio"
               name="use_experimental_function"
               value="disable"
               autocomplete="off"

             <?php if ($account['use_experimental_function'] === 'disable'): ?>

               checked />

             <?php else: ?>

               />

             <?php endif; ?>

        無効

      </label>

    </div>

  </td>
</tr>
