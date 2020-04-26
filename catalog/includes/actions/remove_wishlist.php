<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if (isset($_GET['products_id'])) {
  $wishlist->remove($_GET['products_id']);
}

tep_redirect(tep_href_link('wishlist.php', tep_get_all_get_params(array('action', 'products_id'))));