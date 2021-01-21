<div class="mb-3">

  <?php echo tep_draw_stars($rating_data['rating']); ?>

  <?php
  if (!empty($rating_data['count'])) {

    ?>

    <a href="<?php echo tep_href_link('product_info.php', tep_get_all_get_params(['rsort'])); ?>#reviews"><?php echo $rating_data['count']; ?><span class="ms-1"><?php echo IMAGE_BUTTON_REVIEWS; ?></span></a>

    <?php
  } else {
    ?>

    <span class="text-primary"><?php echo $rating_data['count']; ?><span class="ms-1"><?php echo IMAGE_BUTTON_REVIEWS; ?></span></span>

    <?php
  }
  ?>
  |
  <a href="<?php echo tep_href_link('product_reviews_write.php', tep_get_all_get_params()); ?>"><?php echo IMAGE_BUTTON_WRITE_REVIEW; ?></a>

</div>
