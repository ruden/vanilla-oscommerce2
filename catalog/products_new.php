<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/products_new.php');

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('products_new.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="contentContainer">
    <div class="contentText">

      <?php
      $products_new_array = array();

      $products_new_query_raw = "select p.*, pd.*, m.*, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price FROM products p left join manufacturers m on (p.manufacturers_id = m.manufacturers_id) left join products_description pd on p.products_id = pd.products_id left join specials s on p.products_id = s.products_id WHERE p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added DESC, pd.products_name";
      $products_new_split = new splitPageResults($products_new_query_raw, MAX_DISPLAY_PRODUCTS_NEW);

      if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '1') || (PREV_NEXT_BAR_LOCATION == '3'))) {
        ?>

        <div class="row align-items-center">
          <div class="col-md d-none d-md-block">
            <?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?>
          </div>
          <div class="col-md">
            <?php echo $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
          </div>
        </div>

        <?php
      }
      ?>

      <?php
      if ($products_new_split->number_of_rows > 0) {
        ?>

        <table border="0" width="100%" cellspacing="2" cellpadding="2">

          <?php
          $products_new_query = tep_db_query($products_new_split->sql_query);
          while ($products_new = tep_db_fetch_array($products_new_query)) {
            if (!empty($products_new['specials_new_products_price'])) {
              $products_price = '<del>' . $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id'])) . '</del> <span class="productSpecialPrice">' . $currencies->display_price($products_new['specials_new_products_price'], tep_get_tax_rate($products_new['products_tax_class_id'])) . '</span>';
            } else {
              $products_price = $currencies->display_price($products_new['products_price'], tep_get_tax_rate($products_new['products_tax_class_id']));
            } ?>
            <tr>
              <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" align="center" class="main"><?php echo '<a href="' . tep_href_link('product_info.php', 'products_id=' . $products_new['products_id']) . '">' . tep_image('images/' . $products_new['products_image'], $products_new['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a>'; ?></td>
              <td valign="top" class="main"><?php echo '<a href="' . tep_href_link('product_info.php', 'products_id=' . $products_new['products_id']) . '"><strong><u>' . $products_new['products_name'] . '</u></strong></a><br />' . TEXT_DATE_ADDED . ' ' . tep_date_long($products_new['products_date_added']) . '<br />' . TEXT_MANUFACTURER . ' ' . $products_new['manufacturers_name'] . '<br /><br />' . TEXT_PRICE . ' ' . $products_price; ?></td>
              <td align="right" valign="middle" class="smallText"><?php echo tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', tep_href_link('products_new.php', tep_get_all_get_params(array('action')) . 'action=buy_now&products_id=' . $products_new['products_id'])); ?></td>
            </tr>
            <?php
          } ?>

        </table>

        <?php
      } else {
        ?>

        <div>
          <?php echo TEXT_NO_NEW_PRODUCTS; ?>
        </div>

        <?php
      }

      if (($products_new_split->number_of_rows > 0) && ((PREV_NEXT_BAR_LOCATION == '2') || (PREV_NEXT_BAR_LOCATION == '3'))) {
        ?>

        <div class="row align-items-center">
          <div class="col-md d-none d-md-block">
            <?php echo $products_new_split->display_count(TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW); ?>
          </div>
          <div class="col-md">
            <?php echo $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
          </div>
        </div>

        <?php
      }
      ?>

    </div>
  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');
