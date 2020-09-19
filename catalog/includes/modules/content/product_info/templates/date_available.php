<?php
if (isset($product_info['products_date_available'])) {
  ?>

  <div class="mb-3">
    <?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($product_info['products_date_available'])); ?>
  </div>

  <?php
}
?>


