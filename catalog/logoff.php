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

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('logoff.php'));

unset($_SESSION['customer_id']);
unset($_SESSION['customer_default_address_id']);
unset($_SESSION['customer_first_name']);
unset($_SESSION['customer_country_id']);
unset($_SESSION['customer_zone_id']);

if (isset($_SESSION['sendto'])) {
  unset($_SESSION['sendto']);
}

if (isset($_SESSION['billto'])) {
  unset($_SESSION['billto']);
}

if (isset($_SESSION['shipping'])) {
  unset($_SESSION['shipping']);
}

if (isset($_SESSION['payment'])) {
  unset($_SESSION['payment']);
}

if (isset($_SESSION['comments'])) {
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