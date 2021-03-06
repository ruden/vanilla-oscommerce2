<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if (!isset($_SESSION['customer_id']) && (ALLOW_GUEST_TO_TELL_A_FRIEND == 'false')) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link('login.php'));
}

$valid_product = false;
if (isset($_GET['products_id'])) {
  $product_info_query = tep_db_query("select pd.products_name from products p, products_description pd where p.products_status = '1' and p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (tep_db_num_rows($product_info_query)) {
    $valid_product = true;

    $product_info = tep_db_fetch_array($product_info_query);
  }
}

if ($valid_product == false) {
  tep_redirect(tep_href_link('product_info.php', 'products_id=' . (int)$_GET['products_id']));
}

require('includes/languages/' . $language . '/tell_a_friend.php');

if (isset($_GET['action']) && ($_GET['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  $error = false;

  $to_email_address = tep_db_prepare_input($_POST['to_email_address']);
  $to_name = tep_db_prepare_input($_POST['to_name']);
  $from_email_address = tep_db_prepare_input($_POST['from_email_address']);
  $from_name = tep_db_prepare_input($_POST['from_name']);
  $message = tep_db_prepare_input($_POST['message']);

  if (empty($from_name)) {
    $error = true;

    $messageStack->add('friend', ERROR_FROM_NAME);
  }

  if (!tep_validate_email($from_email_address)) {
    $error = true;

    $messageStack->add('friend', ERROR_FROM_ADDRESS);
  }

  if (empty($to_name)) {
    $error = true;

    $messageStack->add('friend', ERROR_TO_NAME);
  }

  if (!tep_validate_email($to_email_address)) {
    $error = true;

    $messageStack->add('friend', ERROR_TO_ADDRESS);
  }

  $actionRecorder = new actionRecorder('ar_tell_a_friend', (isset($_SESSION['customer_id']) ? $customer_id : null), $from_name);
  if (!$actionRecorder->canPerform()) {
    $error = true;

    $actionRecorder->record(false);

    $messageStack->add('friend', sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_TELL_A_FRIEND_EMAIL_MINUTES') ? (int)MODULE_ACTION_RECORDER_TELL_A_FRIEND_EMAIL_MINUTES : 15)));
  }

  if ($error == false) {
    $email_subject = sprintf(TEXT_EMAIL_SUBJECT, $from_name, STORE_NAME);
    $email_body = sprintf(TEXT_EMAIL_INTRO, $to_name, $from_name, $product_info['products_name'], STORE_NAME) . "\n\n";

    if (!empty($message)) {
      $email_body .= $message . "\n\n";
    }

    $email_body .= sprintf(TEXT_EMAIL_LINK, tep_href_link('product_info.php', 'products_id=' . (int)$_GET['products_id'], 'SSL', false)) . "\n\n" .
      sprintf(TEXT_EMAIL_SIGNATURE, STORE_NAME . "\n" . HTTP_SERVER . DIR_WS_CATALOG . "\n");

    tep_mail($to_name, $to_email_address, $email_subject, $email_body, $from_name, $from_email_address);

    $actionRecorder->record();

    $messageStack->add_session('header', sprintf(TEXT_EMAIL_SUCCESSFUL_SENT, $product_info['products_name'], tep_output_string_protected($to_name)), 'success');

    tep_redirect(tep_href_link('product_info.php', 'products_id=' . (int)$_GET['products_id']));
  }
} elseif (isset($_SESSION['customer_id'])) {
  $account_query = tep_db_query("select customers_firstname, customers_lastname, customers_email_address from customers where customers_id = '" . (int)$customer_id . "'");
  $account = tep_db_fetch_array($account_query);

  $from_name = $account['customers_firstname'] . ' ' . $account['customers_lastname'];
  $from_email_address = $account['customers_email_address'];
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('tell_a_friend.php', 'products_id=' . (int)$_GET['products_id']));

require('includes/template_top.php');
?>

  <h1><?php echo sprintf(HEADING_TITLE, $product_info['products_name']); ?></h1>

<?php
if ($messageStack->size('friend') > 0) {
  echo $messageStack->output('friend');
}
?>

<?php echo tep_draw_form('email_friend', tep_href_link('tell_a_friend.php', 'action=process&products_id=' . (int)$_GET['products_id']), 'post', '', true); ?>

  <div class="col-lg-6 mb-5">
    <div class="text-end text-danger small"><?php echo FORM_REQUIRED_INFORMATION; ?></div>

    <div class="mb-3">
      <label class="form-label" for="from_name"><?php echo FORM_FIELD_CUSTOMER_NAME; ?></label>
      <?php echo tep_draw_input_field('from_name', null, 'id="from_name" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="from_email_address"><?php echo FORM_FIELD_CUSTOMER_EMAIL; ?></label>
      <?php echo tep_draw_input_field('from_email_address', null, 'id="from_email_address" class="form-control"'); ?>
    </div>

    <h2><?php echo FORM_TITLE_FRIEND_DETAILS; ?></h2>

    <div class="mb-3">
      <label class="form-label" for="to_name"><?php echo ENTRY_FIRST_NAME . '<span class="text-danger ms-1">' . ENTRY_FIRST_NAME_TEXT . '</span>'; ?></label>
      <?php echo tep_draw_input_field('to_name', null, 'id="to_name" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="to_email_address"><?php echo FORM_FIELD_FRIEND_EMAIL . '<span class="text-danger ms-1">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>'; ?></label>
      <?php echo tep_draw_input_field('to_email_address', null, 'id="to_email_address" class="form-control"'); ?>
    </div>

    <h2><?php echo FORM_TITLE_FRIEND_MESSAGE; ?></h2>

    <div class="mb-3">
      <?php echo tep_draw_textarea_field('message', null, 'class="form-control" rows="5"'); ?>
    </div>
    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php'), 'btn-light'); ?>
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');