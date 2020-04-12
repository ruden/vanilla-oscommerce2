<?php
while ($reviews = tep_db_fetch_array($reviews_query)) {
  ?>

  <div>
    <span style="float: right;"><?php echo tep_date_long($reviews['date_added']); ?></span>
    <h2><?php echo '<a href="' . tep_href_link('product_reviews_info.php', 'products_id=' . $reviews['products_id'] . '&reviews_id=' . $reviews['reviews_id']) . '">' . $reviews['products_name'] . '</a> <span class="smallText">' . sprintf(TEXT_REVIEW_BY, tep_output_string_protected($reviews['customers_name'])) . '</span>'; ?></h2>
  </div>

  <?php
}
?>