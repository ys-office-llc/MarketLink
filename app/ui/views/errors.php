<div class="alert alert-danger">
<ul>
  <?php foreach ($errors as $error): ?>
  <li><?php print $this->escape($error); ?></li>
  <?php endforeach; ?>
</ul>
</div>
