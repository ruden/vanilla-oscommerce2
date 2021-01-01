<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if ($oscTemplate->hasBlocks('header_top')) {
  ?>

  <div class="bg-light">
    <div class="container py-1 d-none d-lg-block">
      <div class="row justify-content-end align-items-center">
        <?php echo $oscTemplate->getBlocks('header_top'); ?>
      </div>
    </div>
  </div>

  <?php
}
?>

  <div class="container d-none d-lg-block">
    <div class="row my-4">
      <?php echo $oscTemplate->getBlocks('header'); ?>
    </div>
  </div>

<?php
if ($oscTemplate->hasBlocks('header_menu')) {
  ?>

  <div class="bg-light">
    <div class="container">
      <?php echo $oscTemplate->getBlocks('header_menu'); ?>
    </div>
  </div>

  <?php
}