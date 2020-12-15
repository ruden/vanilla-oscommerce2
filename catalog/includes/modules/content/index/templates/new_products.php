<div class="my-5">
  <h2><?php echo sprintf(MODULE_CONTENT_INDEX_NEW_PRODUCTS_BOX_TITLE, strftime('%B')); ?></h2>

  <div class="row">

    <?php
    foreach ($new_products_array as $products) {
      ?>

      <div class="col-6 col-lg">

        <div class="text-center">
          <a href="<?php echo tep_href_link('product_info.php', 'products_id=' . $products['products_id']); ?>"><?php echo tep_image('images/products/thumbs/' . $products['products_image'], $products['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"'); ?></a>

          <div class="m-2">
            <a href="<?php echo tep_href_link('product_info.php', 'products_id=' . $products['products_id']); ?>"><?php echo $products['products_name']; ?></a>
          </div>

          <div class="mb-2">
            <?php echo $products['products_price']; ?>
          </div>
        </div>

      </div>

      <?php
    }
    ?>

  </div>
</div>