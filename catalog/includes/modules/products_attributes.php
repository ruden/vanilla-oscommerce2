<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

$products_attributes_query = tep_db_query("select count(*) as total from products_options popt, products_attributes patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "'");
$products_attributes = tep_db_fetch_array($products_attributes_query);
if ($products_attributes['total'] > 0) {
  ?>

  <p><?php echo TEXT_PRODUCT_OPTIONS; ?></p>

  <p>
    <?php
    $products_options_name_query = tep_db_query("select distinct popt.products_options_id, popt.products_options_name from products_options popt, products_attributes patrib where patrib.products_id='" . (int)$_GET['products_id'] . "' and patrib.options_id = popt.products_options_id and popt.language_id = '" . (int)$languages_id . "' order by popt.products_options_name");
    while ($products_options_name = tep_db_fetch_array($products_options_name_query)) {
      $products_options_array = array();
      $products_options_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name, pa.options_values_price, pa.price_prefix from products_attributes pa, products_options_values pov where pa.products_id = '" . (int)$_GET['products_id'] . "' and pa.options_id = '" . (int)$products_options_name['products_options_id'] . "' and pa.options_values_id = pov.products_options_values_id and pov.language_id = '" . (int)$languages_id . "'");
      while ($products_options = tep_db_fetch_array($products_options_query)) {
        $products_options_array[] = array('id' => $products_options['products_options_values_id'], 'text' => $products_options['products_options_values_name']);
        if ($products_options['options_values_price'] != '0') {
          $products_options_array[sizeof($products_options_array)-1]['text'] .= ' (' . $products_options['price_prefix'] . $currencies->display_price($products_options['options_values_price'], tep_get_tax_rate($product_info['products_tax_class_id'])) .') ';
        }
      }

      if (is_string($_GET['products_id']) && isset($cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']])) {
        $selected_attribute = $cart->contents[$_GET['products_id']]['attributes'][$products_options_name['products_options_id']];
      } else {
        $selected_attribute = false;
      }
      ?>
      <strong><?php echo $products_options_name['products_options_name'] . ':'; ?></strong><br /><?php echo tep_draw_pull_down_menu('id[' . $products_options_name['products_options_id'] . ']', $products_options_array, $selected_attribute); ?><br />
      <?php
    }
    ?>
  </p>

  <?php
}
?>