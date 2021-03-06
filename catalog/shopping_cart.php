<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require("includes/application_top.php");

if ($cart->count_contents() > 0) {
  include('includes/classes/payment.php');
  $payment_modules = new payment;
}

require('includes/languages/' . $language . '/shopping_cart.php');

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('shopping_cart.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="mb-5">

    <?php
    if ($cart->count_contents() > 0) {
      ?>

      <?php echo tep_draw_form('cart_quantity', tep_href_link('shopping_cart.php', 'action=update_product')); ?>

      <?php
      $any_out_of_stock = 0;
      $products = $cart->get_products();
      for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
// Push all attributes information in an array
        if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
          foreach ($products[$i]['attributes'] as $option => $value) {
            echo tep_draw_hidden_field('id[' . $products[$i]['id'] . '][' . $option . ']', $value);
            $attributes = tep_db_query("select popt.products_options_name, poval.products_options_values_name, pa.options_values_price, pa.price_prefix from products_options popt, products_options_values poval, products_attributes pa where pa.products_id = '" . (int)$products[$i]['id'] . "' and pa.options_id = '" . (int)$option . "'and pa.options_id = popt.products_options_id and pa.options_values_id = '" . (int)$value . "' and pa.options_values_id = poval.products_options_values_id and popt.language_id = '" . (int)$languages_id . "' and poval.language_id = '" . (int)$languages_id . "'");
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

      <table class="table align-top">
        <thead>
        <tr>
          <th colspan="3"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
        </tr>
        </thead>
        <tbody>

        <?php

        for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
          echo '      <tr>';

          $products_name = '<table>  
                           <tr> 
                             <td><a href="' . tep_href_link('product_info.php', 'products_id=' . $products[$i]['id']) . '"><strong>' . $products[$i]['name'] . '</strong></a>';

          if (STOCK_CHECK == 'true') {
            $stock_check = tep_check_stock($products[$i]['id'], $products[$i]['quantity']);
            if (!empty($stock_check)) {
              $any_out_of_stock = 1;

              $products_name .= $stock_check;
            }
          }

          if (isset($products[$i]['attributes']) && is_array($products[$i]['attributes'])) {
            foreach ($products[$i]['attributes'] as $option => $value) {
              $products_name .= '<br /><span class="small">- ' . $products[$i][$option]['products_options_name'] . ' ' . $products[$i][$option]['products_options_values_name'] . '</span>';
            }
          }

          $products_name .= '<div class="input-group my-2">
                             <div class="col-3">' . tep_draw_input_field('cart_quantity[]', $products[$i]['quantity'], 'class="form-control"') . tep_draw_hidden_field('products_id[]', $products[$i]['id']) . '</div>
                             <div class="input-group-append">' . tep_draw_button(IMAGE_BUTTON_UPDATE, 'refresh', null, 'btn-primary') . ' ' . TEXT_OR . '<a href="' . tep_href_link('shopping_cart.php', 'products_id=' . $products[$i]['id'] . '&action=remove_product') . '">' . TEXT_REMOVE . '</a></div>
                           </div>';

          $products_name .= '    </td>  
                             </tr>  
                           </table>';

          echo '        <td class="text-center"><a href="' . tep_href_link('product_info.php', 'products_id=' . $products[$i]['id']) . '">' . tep_image('images/products/thumbs/' . $products[$i]['image'], $products[$i]['name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'class="img-fluid"') . '</a></td>
                      <td>' . $products_name . '</td>
                      <td class="text-end"><strong>' . $currencies->display_price($products[$i]['final_price'], tep_get_tax_rate($products[$i]['tax_class_id']), $products[$i]['quantity']) . '</strong></td>
                    </tr>';
        }
        ?>

        </tbody>
      </table>

      <p class="fw-bold text-end"><?php echo SUB_TITLE_SUB_TOTAL; ?><?php echo $currencies->format($cart->show_total()); ?></p>

      <?php
      if ($any_out_of_stock == 1) {
        if (STOCK_ALLOW_CHECKOUT == 'true') {
          ?>

          <p class="text-danger text-center"><?php echo OUT_OF_STOCK_CAN_CHECKOUT; ?></p>

          <?php
        } else {
          ?>

          <p class="text-danger text-center"><?php echo OUT_OF_STOCK_CANT_CHECKOUT; ?></p>

          <?php
        }
      }
      ?>

      <div class="text-end"><?php echo tep_draw_button(IMAGE_BUTTON_CHECKOUT, 'triangle-1-e', tep_href_link('checkout_shipping.php'), 'btn-primary'); ?></div>

      <?php echo $OSCOM_Hooks->call('shopping_cart', 'displayAlternativeCheckoutButtons'); ?>

      </form>

      <?php
    } else {
      ?>

      <p><?php echo TEXT_CART_EMPTY; ?></p>

      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php'), 'btn-primary'); ?>

      <?php
    }
    ?>

  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');