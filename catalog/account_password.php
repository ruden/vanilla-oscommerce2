<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if (!isset($_SESSION['customer_id'])) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link('login.php'));
}

// needs to be included earlier to set the success message in the messageStack
require('includes/languages/' . $language . '/account_password.php');

if (isset($_POST['action']) && ($_POST['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  $password_current = tep_db_prepare_input($_POST['password_current']);
  $password_new = tep_db_prepare_input($_POST['password_new']);
  $password_confirmation = tep_db_prepare_input($_POST['password_confirmation']);

  $error = false;

  if (strlen($password_new) < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;

    $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR);
  } elseif ($password_new !== $password_confirmation) {
    $error = true;

    $messageStack->add('account_password', ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING);
  }

  if ($error == false) {
    $check_customer_query = tep_db_query("select customers_password from customers where customers_id = '" . (int)$customer_id . "'");
    $check_customer = tep_db_fetch_array($check_customer_query);

    if (tep_validate_password($password_current, $check_customer['customers_password'])) {
      tep_db_query("update customers set customers_password = '" . tep_encrypt_password($password_new) . "' where customers_id = '" . (int)$customer_id . "'");

      tep_db_query("update customers_info set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

      $messageStack->add_session('account', SUCCESS_PASSWORD_UPDATED, 'success');

      tep_redirect(tep_href_link('account.php'));
    } else {
      $error = true;

      $messageStack->add('account_password', ERROR_CURRENT_PASSWORD_NOT_MATCHING);
    }
  }
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('account_password.php'));

require('includes/template_top.php');
require('includes/form_check.js.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('account_password') > 0) {
  echo $messageStack->output('account_password');
}
?>

<?php echo tep_draw_form('account_password', tep_href_link('account_password.php'), 'post', 'onsubmit="return check_form(account_password);"', true) . tep_draw_hidden_field('action', 'process'); ?>

  <div class="col-lg-6 mb-5">
    <div class="text-end text-danger small"><?php echo FORM_REQUIRED_INFORMATION; ?></div>

    <div class="mb-3">
      <label class="form-label" for="password_current"><?php echo ENTRY_PASSWORD_CURRENT . (!empty(ENTRY_PASSWORD_CURRENT_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_PASSWORD_CURRENT_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_password_field('password_current', null, 'id="password_current" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="password_new"><?php echo ENTRY_PASSWORD_NEW . (!empty(ENTRY_PASSWORD_NEW_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_PASSWORD_NEW_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_password_field('password_new', null, 'id="password_new" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="password_confirmation"><?php echo ENTRY_PASSWORD_CONFIRMATION . (!empty(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_password_field('password_confirmation', null, 'id="password_confirmation" class="form-control"'); ?>
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