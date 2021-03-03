<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if (isset($_SESSION['customer_id']) && isset($_GET['pid'])) {
  if (tep_has_product_attributes($_GET['pid'])) {
    tep_redirect(tep_href_link('product_info.php', 'products_id=' . $_GET['pid']));
  } else {
    $_SESSION['cart']->add_cart($_GET['pid'], $_SESSION['cart']->get_quantity($_GET['pid']) + 1);
  }
}

tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));