<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

chdir('../../../../');
require('includes/application_top.php');

if (!isset($_SESSION['customer_id'])) {
  tep_redirect(tep_href_link('login.php'));
}

if (defined('MODULE_CONTENT_ACCOUNT_COOKIE_CONSENT_STATUS') && MODULE_CONTENT_ACCOUNT_COOKIE_CONSENT_STATUS == 'False') {
  tep_redirect(tep_href_link('account.php'));
}

require('includes/languages/' . $language . '/modules/content/account/cm_account_cookie_consent.php');

$breadcrumb->add(MODULE_CONTENT_ACCOUNT_COOKIE_CONSENT_NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(MODULE_CONTENT_ACCOUNT_COOKIE_CONSENT_NAVBAR_TITLE_2, tep_href_link('ext/modules/content/account/cookie_consent.php'));

require('includes/template_top.php');
?>

  <h1><?php echo MODULE_CONTENT_ACCOUNT_COOKIE_CONSENT_HEADING_TITLE; ?></h1>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');