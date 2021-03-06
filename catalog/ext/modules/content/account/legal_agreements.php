<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

chdir('../../../../');
require('includes/application_top.php');

if (!isset($_SESSION['customer_id'])) {
  tep_redirect(tep_href_link('login.php'));
}

if (!defined('ACCOUNT_LEGAL_AGREEMENTS') || ACCOUNT_LEGAL_AGREEMENTS == "false") {
  tep_redirect(tep_href_link('account.php'));
}

// needs to be included earlier to set the success message in the messageStack
require('includes/languages/' . $language . '/modules/content/account/cm_account_legal_agreements.php');

$breadcrumb->add(MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_NAVBAR_TITLE_2, tep_href_link('ext/modules/content/account/legal_agreements.php'));

require('includes/template_top.php');
?>

  <h1><?php echo MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_HEADING_TITLE; ?></h1>

  <div class="mb-3 form-check">
    <?php echo tep_draw_checkbox_field('legal_agreements', 'on', true, 'class="form-check-input" id="legal-agreements" disabled') . (!empty(ENTRY_LEGAL_AGREEMENTS_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_LEGAL_AGREEMENTS_TEXT . '</span>' : ''); ?>
    <label class="form-check-label" for="legal-agreements"><?php echo sprintf(ENTRY_LEGAL_AGREEMENTS, tep_href_link('information.php', 'pages_id=3'), tep_href_link('information.php', 'pages_id=2')); ?></label>
    <?php echo(isset($_SESSION['legal_agreements_consents']) ? '(' . $_SESSION['legal_agreements_consents'] . ')' : ''); ?>
  </div>

<?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php'), 'btn-light'); ?>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');