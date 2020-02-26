<div class="card mb-2">
  <div class="card-header"><?php echo MODULE_BOXES_BEST_SELLERS_BOX_TITLE; ?></div>

  <div class="card-body">

    <?php
    foreach ($best_sellers_array as $products) {
      ?>

        <a class="card-link" href="<?php echo tep_href_link('product_info.php', 'products_id=' . $products['products_id']); ?>"><?php echo $products['products_name']; ?></a><br>

      <?php
    }
    ?>

  </div>

</div>