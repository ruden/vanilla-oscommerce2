<div class="mb-3">
  <p><?php echo MODULE_CONTENT_CHECKOUT_SUCCESS_PRODUCT_NOTIFICATIONS_TEXT_NOTIFY_PRODUCTS; ?></p>

  <?php
  foreach ($products_displayed as $products) {
    ?>

    <div class="form-check">
      <?php echo tep_draw_checkbox_field('notify[]', $products['products_id'], false, 'class="form-check-input" id="notify_' . $products['products_id'] . '"'); ?>
      <label class="form-check-label" for="notify_<?php echo $products['products_id']; ?>">
        <?php echo $products['products_name']; ?>
      </label>
    </div>


    <?php
  }
  ?>

</div>
