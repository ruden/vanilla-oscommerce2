<div class="mb-3 text-center">

  <?php
  if (!empty($products_images_array)) {
    ?>

    <div id="carouselIndicators" class="carousel carousel-dark slide">
      <div class="carousel-inner">

        <?php
        foreach ($products_images_array as $item => $products) {
          $active = $item == 0 ? 'active' : '';
          ?>

          <div class="carousel-item <?php echo $active; ?>">

            <?php
            if (!empty($products['htmlcontent'])) {
              ?>

              <iframe src="<?php echo $products['htmlcontent']; ?>" style="width: 100%; height: <?php echo (int)MODULE_CONTENT_PRODUCT_INFO_IMAGES_ORIGINAL_IMAGE_HEIGHT; ?>px;" class="img-fluid"></iframe>

              <?php
            } else {
              ?>

              <a href="<?php echo tep_href_link('images/products/originals/' . $products['image'], '', 'SSL', false, false); ?>" target="_blank"><?php echo tep_image('images/products/originals/' . $products['image'], $products_name, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGES_ORIGINAL_IMAGE_WIDTH, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGES_ORIGINAL_IMAGE_HEIGHT, 'class="img-fluid"'); ?></a>

              <?php
            }
            ?>

          </div>

          <?php
        }
        ?>

      </div>

      <?php
      if (count($products_images_array) > 1) {
        ?>

        <a class="carousel-control-prev h-75" href="#carouselIndicators" role="button" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden"><?php echo PREVNEXT_BUTTON_PREV; ?></span>
        </a>
        <a class="carousel-control-next h-75" href="#carouselIndicators" role="button" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden"><?php echo PREVNEXT_BUTTON_NEXT; ?></span>
        </a>

        <ol class="carousel-indicators position-static">
          <?php
          for ($i = 0, $n = sizeof($products_images_array); $i + 1 <= $n; $i++) {
            $active = $i == 0 ? 'active' : '';
            ?>

            <li data-bs-target="#carouselIndicators" data-bs-slide-to="<?php echo $i; ?>" style="align-items: flex-start;" class="w-100 h-auto d-inline-flex bg-transparent <?php echo $active; ?>">

              <?php echo tep_image('images/products/thumbs/' . $products_images_array[$i]['image'], $products_name, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGES_THUMB_IMAGE_WIDTH, (int)MODULE_CONTENT_PRODUCT_INFO_IMAGES_THUMB_IMAGE_HEIGHT, 'class="img-fluid mx-auto"'); ?>

            </li>

            <?php
          }
          ?>

        </ol>

        <?php
      }
      ?>

    </div>

    <?php
  } else {
    echo tep_image('images/no_picture.gif');
  }
  ?>

</div>
