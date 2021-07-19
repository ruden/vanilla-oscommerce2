<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

chdir('../../../../../');
require('includes/application_top.php');

if (!isset($_SESSION['customer_id'])) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link('login.php'));
}

if (defined('MODULE_PAYMENT_INSTALLED') && !empty(MODULE_PAYMENT_INSTALLED) && in_array('sage_pay_direct.php', explode(';', MODULE_PAYMENT_INSTALLED))) {
  if (!class_exists('sage_pay_direct')) {
    include('includes/languages/' . $language . '/modules/payment/sage_pay_direct.php');
    include('includes/modules/payment/sage_pay_direct.php');
  }

  $sage_pay_direct = new sage_pay_direct();

  if (!$sage_pay_direct->enabled) {
    tep_redirect(tep_href_link('account.php'));
  }
} else {
  tep_redirect(tep_href_link('account.php'));
}

require('includes/languages/' . $language . '/modules/content/account/cm_account_sage_pay_cards.php');
require('includes/modules/content/account/cm_account_sage_pay_cards.php');
$sage_pay_cards = new cm_account_sage_pay_cards();

if (!$sage_pay_cards->isEnabled()) {
  tep_redirect(tep_href_link('account.php'));
}

if (isset($_GET['action'])) {
  if (($_GET['action'] == 'delete') && isset($_GET['id']) && is_numeric($_GET['id']) && isset($_GET['formid']) && ($_GET['formid'] == md5($sessiontoken))) {
    $token_query = tep_db_query("select id, sagepay_token from customers_sagepay_tokens where id = '" . (int)$_GET['id'] . "' and customers_id = '" . (int)$customer_id . "'");

    if (tep_db_num_rows($token_query)) {
      $token = tep_db_fetch_array($token_query);

      $sage_pay_direct->deleteCard($token['sagepay_token'], $token['id']);

      $messageStack->add_session('cards', MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_SUCCESS_DELETED, 'success');
    }
  }

  tep_redirect(tep_href_link('ext/modules/content/account/sage_pay/cards.php'));
}

$breadcrumb->add(MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_NAVBAR_TITLE_2, tep_href_link('ext/modules/content/account/sage_pay/cards.php'));

require('includes/template_top.php');
?>

  <h1><?php echo MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('cards') > 0) {
  echo $messageStack->output('cards');
}
?>

  <div class="mb-5">
    <?php echo MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_TEXT_DESCRIPTION; ?>

    <h2><?php echo MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_SAVED_CARDS_TITLE; ?></h2>

    <div class="mb-3">

      <?php
      $tokens_query = tep_db_query("select id, card_type, number_filtered, expiry_date from customers_sagepay_tokens where customers_id = '" . (int)$customer_id . "' order by date_added");

      if (tep_db_num_rows($tokens_query) > 0) {
        while ($tokens = tep_db_fetch_array($tokens_query)) {
          ?>

          <div>
            <span class="float-end"><?php echo tep_draw_button(SMALL_IMAGE_BUTTON_DELETE, 'trash', tep_href_link('ext/modules/content/account/sage_pay/cards.php', 'action=delete&id=' . (int)$tokens['id'] . '&formid=' . md5($sessiontoken))); ?></span>
            <p>
              <strong><?php echo tep_output_string_protected($tokens['card_type']); ?></strong>&nbsp;&nbsp;****<?php echo tep_output_string_protected($tokens['number_filtered']) . '&nbsp;&nbsp;' . tep_output_string_protected(substr($tokens['expiry_date'], 0, 2) . '/' . substr($tokens['expiry_date'], 2)); ?>
            </p>
          </div>

          <?php
        }
      } else {
        ?>

        <div class="alert alert-warning pb-0">
          <?php echo MODULE_CONTENT_ACCOUNT_SAGE_PAY_CARDS_TEXT_NO_CARDS; ?>
        </div>

        <?php
      }
      ?>

    </div>

    <div class="text-end">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php')); ?>
    </div>
  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');
