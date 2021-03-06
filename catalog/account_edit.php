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
require('includes/languages/' . $language . '/account_edit.php');

if (isset($_POST['action']) && ($_POST['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
  $firstname = tep_db_prepare_input($_POST['firstname']);
  $lastname = tep_db_prepare_input($_POST['lastname']);
  if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
  $email_address = tep_db_prepare_input($_POST['email_address']);
  $telephone = tep_db_prepare_input($_POST['telephone']);
  $fax = tep_db_prepare_input($_POST['fax']);

  $error = false;

  if (ACCOUNT_GENDER == 'true') {
    if (($gender != 'm') && ($gender != 'f')) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_GENDER_ERROR);
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;

    $messageStack->add('account_edit', ENTRY_FIRST_NAME_ERROR);
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;

    $messageStack->add('account_edit', ENTRY_LAST_NAME_ERROR);
  }

  if (ACCOUNT_DOB == 'true') {
    if ((strlen($dob) < ENTRY_DOB_MIN_LENGTH) || (!empty($dob) && (!is_numeric(tep_date_raw($dob)) || !@checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))))) {
      $error = true;

      $messageStack->add('account_edit', ENTRY_DATE_OF_BIRTH_ERROR);
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;

    $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR);
  }

  if (!tep_validate_email($email_address)) {
    $error = true;

    $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  }

  $check_email_query = tep_db_query("select count(*) as total from customers where customers_email_address = '" . tep_db_input($email_address) . "' and customers_id != '" . (int)$customer_id . "'");
  $check_email = tep_db_fetch_array($check_email_query);
  if ($check_email['total'] > 0) {
    $error = true;

    $messageStack->add('account_edit', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;

    $messageStack->add('account_edit', ENTRY_TELEPHONE_NUMBER_ERROR);
  }

  if ($error == false) {
    $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,
                            'customers_telephone' => $telephone,
                            'customers_fax' => $fax);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

    tep_db_perform('customers', $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "'");

    tep_db_query("update customers_info set customers_info_date_account_last_modified = now() where customers_info_id = '" . (int)$customer_id . "'");

    $sql_data_array = array('entry_firstname' => $firstname,
                            'entry_lastname' => $lastname);

    tep_db_perform('address_book', $sql_data_array, 'update', "customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$customer_default_address_id . "'");

// reset the session variables
    $customer_first_name = $firstname;

    $messageStack->add_session('account', SUCCESS_ACCOUNT_UPDATED, 'success');

    tep_redirect(tep_href_link('account.php'));
  }
}

$account_query = tep_db_query("select customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_telephone, customers_fax from customers where customers_id = '" . (int)$customer_id . "'");
$account = tep_db_fetch_array($account_query);

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('account_edit.php'));

require('includes/template_top.php');
require('includes/form_check.js.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('account_edit') > 0) {
  echo $messageStack->output('account_edit');
}
?>

<?php echo tep_draw_form('account_edit', tep_href_link('account_edit.php'), 'post', 'onsubmit="return check_form(account_edit);"', true) . tep_draw_hidden_field('action', 'process'); ?>

  <div class="col-lg-6 mb-5">
    <div class="text-end text-danger small"><?php echo FORM_REQUIRED_INFORMATION; ?></div>

    <?php
    if (ACCOUNT_GENDER == 'true') {
      if (isset($gender)) {
        $male = ($gender == 'm' ? true : false);
      } else {
        $male = ($account['customers_gender'] == 'm' ? true : false);
      }
      $female = !$male;
      ?>

      <div class="mb-3">
        <label class="form-check-label me-2" for="gender"><?php echo ENTRY_GENDER . (!empty(ENTRY_GENDER_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_GENDER_TEXT . '</span>' : ''); ?></label>
        <div class="form-check-inline">
          <label class="form-check-label">
            <?php echo tep_draw_radio_field('gender', 'm', $male, 'class="form-check-input"') . ' ' . MALE; ?>
          </label>
        </div>
        <div class="form-check-inline">
          <label class="form-check-label">
            <?php echo tep_draw_radio_field('gender', 'f', $female, 'class="form-check-input"') . ' ' . FEMALE; ?>
          </label>
        </div>
      </div>

      <?php
    }
    ?>

    <div class="mb-3">
      <label class="form-label" for="firstname"><?php echo ENTRY_FIRST_NAME . (!empty(ENTRY_FIRST_NAME_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_FIRST_NAME_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('firstname', $account['customers_firstname'], 'id="firstname" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="lastname"><?php echo ENTRY_LAST_NAME . (!empty(ENTRY_LAST_NAME_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_LAST_NAME_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('lastname', $account['customers_lastname'], 'id="lastname" class="form-control"'); ?>
    </div>

    <?php
    if (ACCOUNT_DOB == 'true') {
      ?>

      <div class="mb-3">
        <label class="form-label" for="dob"><?php echo ENTRY_DATE_OF_BIRTH . (!empty(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>' : ''); ?></label>
        <?php echo tep_draw_input_field('dob', tep_date_short($account['customers_dob']), 'id="dob" class="form-control" placeholder="' . htmlspecialchars(DOB_FORMAT_STRING) . '"'); ?>
      </div>

      <?php
    }
    ?>


    <div class="mb-3">
      <label class="form-label" for="email-address"><?php echo ENTRY_EMAIL_ADDRESS . (!empty(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('email_address', $account['customers_email_address'], 'id="email-address" class="form-control"', 'email'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER . (!empty(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('telephone', $account['customers_telephone'], 'id="telephone" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="fax"><?php echo ENTRY_FAX_NUMBER . (!empty(ENTRY_FAX_NUMBER_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_FAX_NUMBER_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('fax', $account['customers_fax'], 'id="fax" class="form-control"'); ?>
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