<select name="<?php print $name ?>" class="form-control">

<?php if (isset($sort) and $sort === 'asort'): ?>

<?php   asort($values) ?>

<?php else: ?>

<?php   ksort($values) ?>

<?php endif; ?>

<?php foreach ($values as $key => $value): ?>

  <?php if ((int)$selected === (int)$key): ?>

  <option value="<?php print $key ?>"
          selected>
    <?php print $value['name'] ?>
  </option>

  <?php else: ?>

  <option value="<?php print $key ?>">
    <?php print $value['name'] ?>
  </option>

  <?php endif; ?>

<?php endforeach; ?>

</select>
