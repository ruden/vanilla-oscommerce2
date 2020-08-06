<div class="t-image-lightbox mb-3 text-center">

  <?php
  if (!empty($product_info['products_image']) && is_file('images/' . $product_info['products_image'])) {
    ?>

    <a href="<?php echo tep_href_link('images/' . $product_info['products_image']); ?>" class="lightbox"><?php echo tep_image('images/' . $product_info['products_image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH * 2, SMALL_IMAGE_HEIGHT * 2); ?></a>

  <?php
  $pi_query = tep_db_query("select image, htmlcontent from products_images where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");

  if (tep_db_num_rows($pi_query) > 0) {
    while ($pi = tep_db_fetch_array($pi_query)) {
      if (!empty($pi['htmlcontent'])) {
      ?>

        <a href="<?php echo $pi['htmlcontent']; ?>" data-type="iframe" class="lightbox"><?php echo tep_image('images/' . $pi['image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH / 1.5, SMALL_IMAGE_HEIGHT / 1.5); ?></a>

        <?php
      } else {
        ?>

        <a href="<?php echo tep_href_link('images/' . $pi['image']); ?>" class="lightbox"><?php echo tep_image('images/' . $pi['image'], addslashes($product_info['products_name']), SMALL_IMAGE_WIDTH / 1.5, SMALL_IMAGE_HEIGHT / 1.5); ?></a>

      <?php
      }
    }
  }
  ?>

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
