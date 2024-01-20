<div class="table-responsive">

  <table id="dt"
         class="table display table-hover table-bordered">

    <thead>

      <tr>

<?php foreach (array_shift($import) as $record): ?>

        <th class="text-center info">

          <span class="text-primary">

            <?php print($record) ?>

          </span>

        </th>

<?php endforeach; ?>

      </tr>

    </thead>

    <tbody>

<?php foreach ($import as $index_parent => $records): ?>

      <tr>

<?php   foreach ($records as $index_child => $record): ?>

        <td>

          <input type="hidden"
                 name="column[<?php print($index_parent) ?>][<?php print($index_child) ?>]"
                 value="<?php print($this->escape($record)) ?>" />

          <?php print($record) ?>

        </td>

<?php   endforeach; ?>

      </tr>

<?php endforeach; ?>

    </tbody>
  </table>

</div>
