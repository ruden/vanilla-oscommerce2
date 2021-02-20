<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/password_forgotten.php');

$password_reset_initiated = false;

if (isset($_GET['action']) && ($_GET['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  $email_address = tep_db_prepare_input($_POST['email_address']);

  $check_customer_query = tep_db_query("select customers_firstname, customers_lastname, customers_id from customers where customers_email_address = '" . tep_db_input($email_address) . "'");
  if (tep_db_num_rows($check_customer_query)) {
    $check_customer = tep_db_fetch_array($check_customer_query);

    $actionRecorder = new actionRecorder('ar_reset_password', $check_customer['customers_id'], $email_address);

    if ($actionRecorder->canPerform()) {
      $actionRecorder->record();

      $reset_key = tep_create_random_value(40);

      tep_db_query("update customers_info set password_reset_key = '" . tep_db_input($reset_key) . "', password_reset_date = now() where customers_info_id = '" . (int)$check_customer['customers_id'] . "'");

      $reset_key_url = tep_href_link('password_reset.php', 'account=' . urlencode($email_address) . '&key=' . $reset_key, 'SSL', false);

      if (strpos($reset_key_url, '&amp;') !== false) {
        $reset_key_url = str_replace('&amp;', '&', $reset_key_url);
      }

      tep_mail($check_customer['customers_firstname'] . ' ' . $check_customer['customers_lastname'], $email_address, EMAIL_PASSWORD_RESET_SUBJECT, sprintf(EMAIL_PASSWORD_RESET_BODY, $reset_key_url), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      $password_reset_initiated = true;
    } else {
      $actionRecorder->record(false);

      $messageStack->add('password_forgotten', sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_RESET_PASSWORD_MINUTES') ? (int)MODULE_ACTION_RECORDER_RESET_PASSWORD_MINUTES : 5)));
    }
  } else {
    $messageStack->add('password_forgotten', TEXT_NO_EMAIL_ADDRESS_FOUND);
  }
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('login.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('password_forgotten.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('password_forgotten') > 0) {
  echo $messageStack->output('password_forgotten');
}

if ($password_reset_initiated == true) {
  ?>

  <p><?php echo TEXT_PASSWORD_RESET_INITIATED; ?></p>

  <?php
} else {
  ?>

  <?php echo tep_draw_form('password_forgotten', tep_href_link('password_forgotten.php', 'action=process'), 'post', '', true); ?>

  <p><?php echo TEXT_MAIN; ?></p>

  <div class="col-lg-6 mb-5">

    <div class="mb-3">
      <label class="form-label" for="email-address"><?php echo ENTRY_EMAIL_ADDRESS; ?></label>
      <?php echo tep_draw_input_field('email_address', null, 'id="email-address" class="form-control"', 'email'); ?>
    </div>
    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('login.php'), 'btn-light'); ?>
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

  <?php
}

require('includes/template_bottom.php');
require('includes/application_bottom.php');