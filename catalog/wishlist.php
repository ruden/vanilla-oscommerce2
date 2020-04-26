<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  require("includes/application_top.php");

  require('includes/languages/' . $language . '/wishlist.php');

  $breadcrumb->add(NAVBAR_TITLE, tep_href_link('wishlist.php'));

  require('includes/template_top.php');

  if (!tep_session_is_registered('customer_id')) {
    $messageStack->add('wishlist', sprintf(TEXT_LOGIN_WISHLIST_SAVE, tep_href_link('login.php')));
  }
?>

<h1><?php echo HEADING_TITLE; ?></h1>

<?php
  if ($messageStack->size('wishlist') > 0) {
    echo $messageStack->output('wishlist');
  }

  if ($wishlist->count_list() > 0) {
?>

<div class="contentContainer">
  <h2><?php echo TABLE_HEADING_PRODUCTS; ?></h2>

  <div class="contentText">

<?php
    $any_out_of_stock = 0;
    $products = $wishlist->get_products();
    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        foreach ($products[$i]['attributes'] as $option => $value) {
          echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
          $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix
                                      from products_options popt, products_options_values poval, products_attributes pa
                                      where pa.products_id = '" . (int)$products[$i]['id'] . "'
                                       and pa.options_id = '" . (int)$option . "'
                                       and pa.options_id = popt.products_options_id
                                       and pa.options_values_id = '" . (int)$value . "'
                                       and pa.options_values_id = poval.products_options_values_id
                                       and popt.language_id = '" . (int)$languages_id . "'
                                       and poval.language_id = '" . (int)$languages_id . "'");
          $attributes_values = tep_db_fetch_array($attributes);

          $products[$i][$option]['products_options_name'] = $attributes_values['products_options_name'];
          $products[$i][$option]['options_values_id'] = $value;
          $products[$i][$option]['products_options_values_name'] = $attributes_values['products_options_values_name'];
          $products[$i][$option]['options_values_price'] = $attributes_values['options_values_price'];
          $products[$i][$option]['price_prefix'] = $attributes_values['price_prefix'];
        }
      }
    }
?>

    <table border="0" width="100%" cellspacing="0" cellpadding="0">

<?php

    for ($i=0, $n=sizeof($products); $i<$n; $i++) {
      echo '      <tr>';

      $products_name = '<table border="0" cellspacing="2" cellpadding="2">' .
                       '  <tr>' .
                       '    <td align="center"><a href="' . tep_href_link('product_info.php', 'products_id=' . $products[$i]['id']) . '">' . tep_image('images/' . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a></td>' .
                       '    <td valign="top"><a href="' . tep_href_link('product_info.php', 'products_id=' . $products[$i]['id']) . '"><strong>' . $products[$i]['name'] . '</strong></a>';

      if (STOCK_CHECK == 'true') {
        $stock_check = tep_check_stock($products[$i]['id'], 1);
        if (tep_not_null($stock_check)) {
          $any_out_of_stock = 1;

          $products_name .= $stock_check;
        }
      }

      if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
        foreach ($products[$i]['attributes'] as $option => $value) {
          $products_name .= '<br /><small><i> - ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</i></small>';
        }
      }

      $products_name .= '<br />' . tep_draw_button(IMAGE_BUTTON_IN_CART, 'cart', tep_href_link('wishlist.php', 'products_id=' . $products[$i]['id'] . '&action=add_wishlist')) . '&nbsp;&nbsp;&nbsp;' . TEXT_OR . '<a href="' . tep_href_link('wishlist.php', 'products_id=' . $products[$i]['id'] . '&action=remove_wishlist') . '">' . TEXT_REMOVE . '</a>';

      $products_name .= '    </td>' .
                        '  </tr>' .
                        '</table>';

      echo '        <td valign="top">' . $products_name . '</td>' .
           '        <td align="right" valign="top"><strong>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id'])) . '</strong></td>' .
           '      </tr>';
    }
?>

    </table>

<?php
    if ($any_out_of_stock == 1) {
      if (STOCK_ALLOW_CHECKOUT == 'true') {
?>

    <p class="stockWarning" align="center"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></p>

<?php
      } else {
?>

    <p class="stockWarning" align="center"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></p>

<?php
      }
    }
?>

  </div>

</div>

<?php
  } else {
?>

<div class="contentContainer">
  <div class="contentText">
    <?php echo TEXT_WISHLIST_EMPTY; ?>

    <p align="right"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?></p>
  </div>
</div>

<?php
  }

  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
