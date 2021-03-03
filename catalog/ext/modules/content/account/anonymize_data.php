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

if (defined('MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_STATUS') && MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_STATUS == 'False') {
  tep_redirect(tep_href_link('account.php'));
}

require('includes/languages/' . $language . '/modules/content/account/cm_account_anonymize_data.php');

$error = false;

if (isset($_GET['account'], $_GET['key']) && (!empty($_GET['account']) || !empty($_GET['key']))) {
  $email_address = tep_db_prepare_input($_GET['account']);
  $password_key = tep_db_prepare_input($_GET['key']);

  if ((strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) || (tep_validate_email($email_address) == false)) {
    $error = true;
  } elseif (strlen($password_key) != 40) {
    $error = true;
  } else {
    $check_customer_query = tep_db_query("select ci.* from customers c, customers_info ci where c.customers_email_address = '" . tep_db_input($email_address) . "' and c.customers_id = ci.customers_info_id");
    if (tep_db_num_rows($check_customer_query)) {
      $check_customer = tep_db_fetch_array($check_customer_query);

      if (empty($check_customer['password_reset_key']) || $check_customer['password_reset_key'] !== $password_key) {
        $error = true;
      }
    } else {
      $error = true;
    }
  }

  if ($error === false) {
    $customer = tep_db_input('customer' . $customer_id);

    tep_db_query("update reviews set customers_name = '" . $customer . "' where customers_id = '" . (int)$customer_id . "'");
    tep_db_query("update address_book set entry_company = '" . $customer . "', entry_firstname = '" . $customer . "', entry_lastname = '" . $customer . "', entry_street_address = '" . $customer . "', entry_suburb = '" . $customer . "' where customers_id = '" . (int)$customer_id . "'");
    tep_db_query("update customers set customers_firstname = '" . $customer . "', customers_lastname = '" . $customer . "', customers_email_address = '" . tep_db_input($customer . '@' . parse_url(HTTP_SERVER, PHP_URL_HOST)) . "', customers_telephone = '+0000000000' where customers_id = '" . (int)$customer_id . "'");

    $orders_query = tep_db_query("select orders_id from orders where customers_id = '" . (int)$customer_id . "'");
    while ($orders = tep_db_fetch_array($orders_query)) {
      tep_db_query("update orders set customers_name = '" . $customer . "', customers_company = '" . $customer . "', customers_street_address = '" . $customer . "', customers_telephone = '+0000000000', customers_email_address = '" . tep_db_input($customer . '@' . parse_url(HTTP_SERVER, PHP_URL_HOST)) . "', delivery_name = '" . $customer . "', delivery_street_address = '" . $customer . "', billing_name = '" . $customer . "', billing_street_address = '" . $customer . "' where orders_id = '" . (int)$orders['orders_id'] . "'");
    }

    tep_session_destroy();
    tep_session_recreate();

    tep_redirect(tep_href_link('login.php', 'info_message=' . urlencode(MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_TEXT_SUCCESS_ACCOUNT_DELETE)));
  } else {
    $messageStack->add_session('anonymize_data', MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_TEXT_NO_ANONYMIZE_LINK_FOUND);

    tep_redirect(tep_href_link('ext/modules/content/account/anonymize_data.php'));
  }
}

$anonymize_data_initiated = false;

if (isset($_POST['action'], $_POST['formid']) && $_POST['action'] == 'process' && $_POST['formid'] == $sessiontoken) {
  if (isset($_POST['anonymize']) && $_POST['anonymize'] === 'on') {
    $customer = tep_db_fetch_array(tep_db_query("select * from customers where customers_id = '" . (int)$customer_id . "'"));

    $reset_key = tep_create_random_value(40);

    tep_db_query("update customers_info set password_reset_key = '" . tep_db_input($reset_key) . "', password_reset_date = now() where customers_info_id = '" . (int)$customer['customers_id'] . "'");

    $reset_key_url = tep_href_link('ext/modules/content/account/anonymize_data.php', 'account=' . urlencode($customer['customers_email_address']) . '&key=' . $reset_key, 'SSL', false);

    if (strpos($reset_key_url, '&amp;') !== false) {
      $reset_key_url = str_replace('&amp;', '&', $reset_key_url);
    }

    tep_mail($customer['customers_firstname'] . ' ' . $customer['customers_lastname'], $customer['customers_email_address'], MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_EMAIL_SUBJECT, sprintf(MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_EMAIL_BODY, $customer['customers_firstname'] . ' ' . $customer['customers_lastname'], 'customer' . $customer['customers_id'] . '@' . parse_url(HTTP_SERVER, PHP_URL_HOST), $reset_key_url), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

    $anonymize_data_initiated = true;
  }
}

$breadcrumb->add(MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_NAVBAR_TITLE_2, tep_href_link('ext/modules/content/account/anonymize_data.php'));

require('includes/template_top.php');
?>

  <h1><?php echo MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('anonymize_data') > 0) {
  echo $messageStack->output('anonymize_data');
}

if ($anonymize_data_initiated === true) {
  ?>

  <p class="mb-5"><?php echo MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_TEXT_INITIATED; ?></p>

  <?php
} else {
  ?>

  <?php echo tep_draw_form('anonymize_data', tep_href_link('ext/modules/content/account/anonymize_data.php'), 'post', '', true) . tep_draw_hidden_field('action', 'process'); ?>

  <p><?php echo MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_TEXT_INFORMATION; ?></p>

  <div class="col-lg-6 mb-5">
    <div class="form-check mb-3">
      <?php echo tep_draw_checkbox_field('anonymize', 'on', false, 'class="form-check-input" id="anonymize" required'); ?>
      <label class="form-check-label" for="anonymize">
        <?php echo MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_TEXT_CONSENT; ?>
      </label>
    </div>

    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php'), 'btn-light'); ?>
      <?php echo tep_draw_button(MODULE_CONTENT_ACCOUNT_ANONYMIZE_DATA_ANONYMIZE, 'triangle-1-e'); ?>
    </div>
  </div>

  </form>

  <?php
}
require('includes/template_bottom.php');
require('includes/application_bottom.php');