<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<?php
$view = array('title' => "Login");
?>
<?= view('_partials/head',$view); ?>

<body>
  <div class="loader"></div>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
        <?= view('_partials/sticky_header'); ?>
        <?= view('_partials/sidebar'); ?>
        
        <div class="main-content">
            <?= view($content, $data); ?>
            <?= view('_partials/layout_setting'); ?>
      </div>
     <?= view('_partials/footer'); ?>
    </div>
  </div>
    <?= view('_partials/footer_js'); ?>
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>