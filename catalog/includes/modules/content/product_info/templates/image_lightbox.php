<div class="t-image-lightbox mb-3 text-center">

  <?php
  if (!empty($product_info['products_image']) && is_file('images/products/originals/' . $product_info['products_image'])) {
    ?>

    <a href="<?php echo tep_href_link('images/products/originals/' . $product_info['products_image']); ?>" class="lightbox"><?php echo tep_image('images/products/originals/' . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH * 2, SMALL_IMAGE_HEIGHT * 2); ?></a>

  <?php
  $pi_query = tep_db_query("select image, htmlcontent from products_images where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");

  if (tep_db_num_rows($pi_query) > 0) {
    while ($pi = tep_db_fetch_array($pi_query)) {
      if (!empty($pi['htmlcontent'])) {
      ?>

        <a href="<?php echo $pi['htmlcontent']; ?>" data-type="iframe" class="lightbox"><?php echo tep_image('images/products/thumbs/' . $pi['image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH / 1.5, SMALL_IMAGE_HEIGHT / 1.5); ?></a>

        <?php
      } else {
        ?>

        <a href="<?php echo tep_href_link('images/products/thumbs/' . $pi['image']); ?>" class="lightbox"><?php echo tep_image('images/products/thumbs/' . $pi['image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH / 1.5, SMALL_IMAGE_HEIGHT / 1.5); ?></a>

      <?php
      }
    }
  }
  ?>

    <link rel="stylesheet" href="includes/modules/content/product_info/templates/image_lightbox/css/tobi.min.css">
    <script src="includes/modules/content/product_info/templates/image_lightbox/js/tobi.min.js"></script>
    <script>
      const tobi = new Tobi({
        captions: false,
        zoom: false
      })
    </script>

    <?php
  } else {
    echo tep_image('images/no_picture.gif');
  }
  ?>

</div>
