<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/
?>

<div class="mb-5">

  <div class="row">

    <?php
    if ($listing_split->number_of_rows > 0) {
      $listing_query = tep_db_query($listing_split->sql_query);

      while ($listing = tep_db_fetch_array($listing_query)) {
        ?>

        <div class="col-6 col-lg-3">

          <div class="text-center mb-5">
            <a href="<?php echo tep_href_link('product_info.php', 'products_id=' . $listing['products_id']); ?>"><?php echo tep_image('images/products/thumbs/' . $listing['products_image'], $listing['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"'); ?></a>

            <div class="m-2">
              <a href="<?php echo tep_href_link('product_info.php', 'products_id=' . $listing['products_id']); ?>"><?php echo $listing['products_name']; ?></a>
            </div>

            <div class="mb-2">

              <?php
              if (empty($listing['specials_new_products_price'])) {
                echo $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id']));
              } else {
                ?>

                <del class="text-muted"><?php echo $currencies->display_price($listing['products_price'], tep_get_tax_rate($listing['products_tax_class_id'])); ?></del>
                <span class="text-danger"><?php echo $currencies->display_price($listing['specials_new_products_price'], tep_get_tax_rate($listing['products_tax_class_id'])); ?></span>

                <?php
              }
              ?>

            </div>

            <div class="mb-2">
              <?php echo tep_draw_button(IMAGE_BUTTON_BUY_NOW, 'cart', tep_href_link($PHP_SELF, tep_get_all_get_params(array('action', 'mid', 'pfrom', 'pto', 'attrib')) . 'action=buy_now&products_id=' . $listing['products_id'])); ?>
            </div>
          </div>

        </div>

        <?php
      }
    } else {
      ?>

      <p><?php echo TEXT_NO_PRODUCTS; ?></p>

      <?php
    }

    if ($listing_split->number_of_rows > 0) {
      ?>

      <div class="row align-items-center my-3">
        <div class="col-md d-none d-md-block">
          <?php echo $listing_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?>
        </div>
        <div class="col-md">
          <?php echo $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
        </div>
      </div>

      <?php
    }
    ?>

  </div>
</div>
