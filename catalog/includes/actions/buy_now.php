<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if (isset($_GET['products_id'])) {
  if (tep_has_product_attributes($_GET['products_id'])) {
    tep_redirect(tep_href_link('product_info.php', 'products_id=' . $_GET['products_id']));
  } else {
    $_SESSION['cart']->add_cart($_GET['products_id'], $cart->get_quantity($_GET['products_id']) + 1);
  }
}

tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));