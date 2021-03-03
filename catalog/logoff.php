<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/logoff.php');

$breadcrumb->add(NAVBAR_TITLE);

unset($_SESSION['customer_id']);
unset($_SESSION['customer_default_address_id']);
unset($_SESSION['customer_first_name']);
unset($_SESSION['customer_country_id']);
unset($_SESSION['customer_zone_id']);

if (tep_session_is_registered('sendto')) {
  unset($_SESSION['sendto']);
}

if (tep_session_is_registered('billto')) {
  unset($_SESSION['billto']);
}

if (tep_session_is_registered('shipping')) {
  unset($_SESSION['shipping']);
}

if (tep_session_is_registered('payment')) {
  unset($_SESSION['payment']);
}

if (tep_session_is_registered('comments')) {
  unset($_SESSION['comments']);
}

$cart->reset();

$wishlist->reset();

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="mb-5">
    <p><?php echo TEXT_MAIN; ?></p>
  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');