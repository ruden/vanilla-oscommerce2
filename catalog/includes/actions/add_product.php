<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
  $attributes = isset($_POST['id']) ? $_POST['id'] : '';
  $_SESSION['cart']->add_cart($_POST['products_id'], $_SESSION['cart']->get_quantity(tep_get_uprid($_POST['products_id'], $attributes)) + 1, $attributes);
}

tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));