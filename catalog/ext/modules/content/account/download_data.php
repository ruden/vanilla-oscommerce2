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

if (defined('MODULE_CONTENT_ACCOUNT_DOWNLOAD_DATA_STATUS') && MODULE_CONTENT_ACCOUNT_DOWNLOAD_DATA_STATUS == 'False') {
  tep_redirect(tep_href_link('account.php'));
}

require('includes/languages/' . $language . '/modules/content/account/cm_account_download_data.php');

if (isset($_POST['action'], $_POST['formid']) && $_POST['action'] == 'process' && $_POST['formid'] == $sessiontoken) {
  $customer_query = tep_db_query("select customers_password from customers where customers_id = '" . (int)$customer_id . "'");
  $customer = tep_db_fetch_array($customer_query);

  if (!tep_validate_password(tep_db_prepare_input($_POST['password']), $customer['customers_password'])) {
    $messageStack->add('download_data', MODULE_CONTENT_ACCOUNT_DOWNLOAD_DATA_TEXT_PASSWORD_ERROR);
  } else {
    $csv_string = '';
    $first = true;

    $address_book_query = tep_db_query("select address_book_id as address_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state from address_book where customers_id = '" . (int)$customer_id . "'");
    while ($address_book = tep_db_fetch_array($address_book_query)) {
      if ($first === true) {
        $first = false;
        $csv_string .= implode(',', array_keys($address_book)) . PHP_EOL;
      }

      $csv_string .= implode(',', $address_book) . PHP_EOL;
    }

    $csv_string .= PHP_EOL;
    $first = true;

    $customers_query = tep_db_query("select customers_gender as gender, customers_firstname as firstname, customers_lastname as lastname, customers_dob as dob, customers_email_address as email_address, customers_telephone as telephone, customers_fax as fax from customers where customers_id = '" . (int)$customer_id . "'");
    while ($customers = tep_db_fetch_array($customers_query)) {
      if ($first === true) {
        $first = false;
        $csv_string .= implode(',', array_keys($customers)) . PHP_EOL;
      }

      $csv_string .= implode(',', $customers) . PHP_EOL;
    }

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=personal_data_' . date('m-d-Y') . '.csv');

    echo $csv_string;
    die();
  }
}

$breadcrumb->add(MODULE_CONTENT_ACCOUNT_DOWNLOAD_DATA_NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(MODULE_CONTENT_ACCOUNT_DOWNLOAD_DATA_NAVBAR_TITLE_2, tep_href_link('ext/modules/content/account/download_data.php'));

require('includes/template_top.php');
?>

  <h1><?php echo MODULE_CONTENT_ACCOUNT_DOWNLOAD_DATA_HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('download_data') > 0) {
  echo $messageStack->output('download_data');
}
?>

<?php echo tep_draw_form('download_data', tep_href_link('ext/modules/content/account/download_data.php'), 'post', '', true) . tep_draw_hidden_field('action', 'process'); ?>

  <p><?php echo MODULE_CONTENT_ACCOUNT_DOWNLOAD_DATA_TEXT_INFORMATION; ?></p>

  <div class="col-lg-6 mb-5">

    <div class="mb-3">
      <label class="form-label" for="password"><?php echo ENTRY_PASSWORD; ?></label>
      <?php echo tep_draw_password_field('password', null, 'id="password" class="form-control" required'); ?>
    </div>
    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php'), 'btn-light'); ?>
      <?php echo tep_draw_button(MODULE_CONTENT_ACCOUNT_DOWNLOAD_DATA_BUTTON_DOWNLOAD, 'triangle-1-e'); ?>
    </div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');