<tr>

  <th class="text-center info">

    <span class="text-primary">稼働モード</span>

  </th>

  <td class="text-left active">

    <div class="btn-group"
         data-toggle="buttons">

    <?php if ($account['operation_mode'] === 'demonstration'): ?>

      <label class="btn btn-default active"

    <?php else: ?>

      <label class="btn btn-default"

    <?php endif; ?>

             style="width: 160px">

        <input type="radio"
               name="operation_mode"
               value="demonstration"
               autocomplete="off"

             <?php if ($account['operation_mode'] === 'demonstration'): ?>

                 checked />

             <?php else: ?>

                 />

             <?php endif; ?>

        デモ

      </label>

    <?php if ($account['operation_mode'] === 'commercial'): ?>

      <label class="btn btn-default active"

    <?php else: ?>

      <label class="btn btn-default"

    <?php endif; ?>

             style="width: 160px">

        <input type="radio"
               name="operation_mode"
               value="commercial"
               autocomplete="off"

             <?php if ($account['operation_mode'] === 'commercial'): ?>

               checked />

             <?php else: ?>

               />

             <?php endif; ?>

        商用

      </label>

    </div>

  </td>
</tr>
