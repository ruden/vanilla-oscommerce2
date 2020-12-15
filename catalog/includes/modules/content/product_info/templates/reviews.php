<div class="mb-3">

  <h2 id="reviews"><?php echo MODULE_CONTENT_PRODUCT_INFO_REVIEWS_TITLE; ?></h2>

  <div class="text-center">
    <div class="fw-bold mb-3 ">
      <?php echo tep_draw_stars($rating_data['rating']); ?>
      <span class="px-1">(<?php echo $rating_data['count']; ?>)</span>
      <br>
      <?php echo sprintf(TEXT_OF_5_STARS, $rating_data['rating']); ?>
    </div>

    <div class="mb-3">
      <?php echo tep_draw_button(IMAGE_BUTTON_WRITE_REVIEW, 'comment', tep_href_link('product_reviews_write.php', tep_get_all_get_params()), 'btn-primary'); ?>
    </div>
  </div>

  <?php
  foreach ($reviews_array as $reviews) {
    ?>

    <div class="mb-5" id="reviews-<?php echo $reviews['reviews_id']; ?>">

      <div class="mb-2">
        <time datetime="<?php echo date(DateTime::ATOM, strtotime($reviews['date_added'])); ?>" title="<?php echo tep_datetime_short($reviews['date_added']); ?>">
          <small><?php echo tep_date_short($reviews['date_added']); ?></small></time>
        <span class="fw-bold"><?php echo TEXT_BY; ?><?php echo tep_output_string_protected($reviews['customers_name']); ?></span>
        <span class="float-end"><?php echo sprintf(TEXT_OF_5_STARS, $reviews['reviews_rating']) . ' ' . tep_draw_stars($reviews['reviews_rating']); ?></span>
      </div>

      <p><?php echo nl2br(tep_output_string_protected($reviews['reviews_text'])); ?></p>

    </div>

    <?php
  }
  ?>

</div>
