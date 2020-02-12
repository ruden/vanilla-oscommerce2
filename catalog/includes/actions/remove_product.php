<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if (isset($_GET['products_id'])) {
  $_SESSION['cart']->remove($_GET['products_id']);
}

tep_redirect(tep_href_link($goto, tep_get_all_get_params($parameters)));