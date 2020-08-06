<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

for ($i = 0, $n = sizeof($_POST['products_id']); $i < $n; $i++) {
  if (isset($_POST['cart_delete']) && in_array($_POST['products_id'][$i], (is_array($_POST['cart_delete']) ? $_POST['cart_delete'] : array()))) {
    $_SESSION['cart']->remove($_POST['products_id'][$i]);
  } else {
    $attributes = (isset($_POST['id'][$_POST['products_id'][$i]])) ? $_POST['id'][$_POST['products_id'][$i]] : '';
    $_SESSION['cart']->add_cart($_POST['products_id'][$i], $_POST['cart_quantity'][$i], $attributes, false);
  }
}

tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));