<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/
?>

<div class="container">

  <?php
  if ($oscTemplate->hasBlocks('footer_top')) {
    ?>

    <?php echo $oscTemplate->getBlocks('footer_top'); ?>

    <?php
  }
  ?>

</div>

<div class="bg-light mt-5 py-5">
  <div class="container">

    <?php
    if ($oscTemplate->hasBlocks('footer')) {
      ?>

      <div class="row mb-3 pb-3 border-bottom">
        <?php echo $oscTemplate->getBlocks('footer'); ?>
      </div>

      <?php
    }
    ?>

    <?php
    if ($oscTemplate->hasBlocks('footer_bottom')) {
      ?>

      <?php echo $oscTemplate->getBlocks('footer_bottom'); ?>

      <?php
    }
    ?>

  </div>
</div>
