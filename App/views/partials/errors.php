<?php if (isset($errors)): ?>
  <?php foreach ($errors as $error): ?>
    <div class="message bg-red-100 my-3 p-3 rounded">
      <?= $error ?>
    </div>
  <?php endforeach ?>
<?php endif ?>