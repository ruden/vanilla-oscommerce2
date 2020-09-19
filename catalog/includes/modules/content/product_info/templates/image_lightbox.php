<div class="mb-3 text-center">

  <?php
  if (!empty($products_images_array)) {
    foreach ($products_images_array as $k => $products) {
      if ($k == 0) {
        ?>

        <a href="<?php echo tep_href_link('images/products/originals/' . $products['image']); ?>" class="lightbox"><?php echo tep_image('images/products/originals/' . $products['image'], $products_name, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGE_LIGHTBOX_ORIGINAL_IMAGE_WIDTH, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGE_LIGHTBOX_ORIGINAL_IMAGE_HEIGHT); ?></a>

        <?php
      } else {
        if (!empty($products['htmlcontent'])) {
          ?>

          <a href="<?php echo $products['htmlcontent']; ?>" data-type="iframe" class="lightbox"><?php echo tep_image('images/products/thumbs/' . $products['image'], $products_name, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGE_LIGHTBOX_THUMB_IMAGE_WIDTH, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGE_LIGHTBOX_THUMB_IMAGE_HEIGHT); ?></a>

          <?php
        } else {
          ?>

          <a href="<?php echo tep_href_link('images/products/originals/' . $products['image']); ?>" class="lightbox"><?php echo tep_image('images/products/thumbs/' . $products['image'], $products_name, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGE_LIGHTBOX_THUMB_IMAGE_WIDTH, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGE_LIGHTBOX_THUMB_IMAGE_HEIGHT); ?></a>

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
