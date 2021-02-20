<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/password_reset.php');

$error = false;

if (!isset($_GET['account']) || !isset($_GET['key'])) {
  $error = true;

  $messageStack->add_session('password_forgotten', TEXT_NO_RESET_LINK_FOUND);
}

if ($error == false) {
  $email_address = tep_db_prepare_input($_GET['account']);
  $password_key = tep_db_prepare_input($_GET['key']);

  if ((strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) || (tep_validate_email($email_address) == false)) {
    $error = true;

    $messageStack->add_session('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
  } elseif (strlen($password_key) != 40) {
    $error = true;

    $messageStack->add_session('password_forgotten', TEXT_NO_RESET_LINK_FOUND);
  } else {
    $check_customer_query = tep_db_query("select c.customers_id, c.customers_email_address, ci.password_reset_key, ci.password_reset_date from customers c, customers_info ci where c.customers_email_address = '" . tep_db_input($email_address) . "' and c.customers_id = ci.customers_info_id");
    if (tep_db_num_rows($check_customer_query)) {
      $check_customer = tep_db_fetch_array($check_customer_query);

      if (empty($check_customer['password_reset_key']) || ($check_customer['password_reset_key'] !== $password_key) || (strtotime($check_customer['password_reset_date'] . ' +1 day') <= time())) {
        $error = true;

        $messageStack->add_session('password_forgotten', TEXT_NO_RESET_LINK_FOUND);
      }
    } else {
      $error = true;

      $messageStack->add_session('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
    }
  }
}

if ($error == true) {
  tep_redirect(tep_href_link('password_forgotten.php'));
}

if (isset($_GET['action']) && ($_GET['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  $password_new = tep_db_prepare_input($_POST['password']);
  $password_confirmation = tep_db_prepare_input($_POST['confirmation']);

  if (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;

    $messageStack->add('password_reset', ENTRY_PASSWORD_NEW_ERROR);
  } elseif ($password_new !== $password_confirmation) {
    $error = true;

    $messageStack->add('password_reset', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
  }

  if ($error == false) {
    tep_db_query("update customers set customers_password = '" . tep_encrypt_password($password_new) . "' where customers_id = '" . (int)$check_customer['customers_id'] . "'");

    tep_db_query("update customers_info set customers_info_date_account_last_modified = now(), password_reset_key = null, password_reset_date = null where customers_info_id = '" . (int)$check_customer['customers_id'] . "'");

    $messageStack->add_session('login', SUCCESS_PASSWORD_RESET, 'success');

    tep_redirect(tep_href_link('login.php'));
  }
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('login.php'));
$breadcrumb->add(NAVBAR_TITLE_2);

require('includes/template_top.php');
require('includes/form_check.js.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('password_reset') > 0) {
  echo $messageStack->output('password_reset');
}
?>

<?php echo tep_draw_form('password_reset', tep_href_link('password_reset.php', 'account=' . $email_address . '&key=' . $password_key . '&action=process'), 'post', 'onsubmit="return check_form(password_reset);"', true); ?>

  <p><?php echo TEXT_MAIN; ?></p>

  <div class="col-lg-6 mb-5">

    <div class="mb-3">
      <label class="form-label" for="password"><?php echo ENTRY_PASSWORD; ?></label>
      <?php echo tep_draw_password_field('password', null, 'id="password" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="confirmation"><?php echo ENTRY_PASSWORD_CONFIRMATION; ?></label>
      <?php echo tep_draw_password_field('confirmation', null, 'id="confirmation" class="form-control"'); ?>
    </div>
    <div class="text-end">
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');