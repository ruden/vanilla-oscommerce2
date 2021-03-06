<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

// needs to be included earlier to set the success message in the messageStack
require('includes/languages/' . $language . '/create_account.php');

$process = false;
if (isset($_POST['action']) && ($_POST['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  $process = true;

  if (ACCOUNT_GENDER == 'true') {
    if (isset($_POST['gender'])) {
      $gender = tep_db_prepare_input($_POST['gender']);
    } else {
      $gender = false;
    }
  }
  $firstname = tep_db_prepare_input($_POST['firstname']);
  $lastname = tep_db_prepare_input($_POST['lastname']);
  if (ACCOUNT_DOB == 'true') $dob = tep_db_prepare_input($_POST['dob']);
  $email_address = tep_db_prepare_input($_POST['email_address']);
  if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
  $street_address = tep_db_prepare_input($_POST['street_address']);
  if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
  $postcode = tep_db_prepare_input($_POST['postcode']);
  $city = tep_db_prepare_input($_POST['city']);
  if (ACCOUNT_STATE == 'true') {
    $state = tep_db_prepare_input($_POST['state']);
    if (isset($_POST['zone_id'])) {
      $zone_id = tep_db_prepare_input($_POST['zone_id']);
    } else {
      $zone_id = false;
    }
  }
  $country = tep_db_prepare_input($_POST['country']);
  $telephone = tep_db_prepare_input($_POST['telephone']);
  $fax = tep_db_prepare_input($_POST['fax']);
  if (isset($_POST['newsletter'])) {
    $newsletter = tep_db_prepare_input($_POST['newsletter']);
  } else {
    $newsletter = false;
  }
  $password = tep_db_prepare_input($_POST['password']);
  $confirmation = tep_db_prepare_input($_POST['confirmation']);

  $error = false;

  if (ACCOUNT_GENDER == 'true') {
    if (($gender != 'm') && ($gender != 'f')) {
      $error = true;

      $messageStack->add('create_account', ENTRY_GENDER_ERROR);
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
  }

  if (ACCOUNT_DOB == 'true') {
    if ((strlen($dob) < ENTRY_DOB_MIN_LENGTH) || (!empty($dob) && (!is_numeric(tep_date_raw($dob)) || !@checkdate(substr(tep_date_raw($dob), 4, 2), substr(tep_date_raw($dob), 6, 2), substr(tep_date_raw($dob), 0, 4))))) {
      $error = true;

      $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
  } elseif (tep_validate_email($email_address) == false) {
    $error = true;

    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  } else {
    $check_email_query = tep_db_query("select count(*) as total from customers where customers_email_address = '" . tep_db_input($email_address) . "'");
    $check_email = tep_db_fetch_array($check_email_query);
    if ($check_email['total'] > 0) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }
  }

  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_CITY_ERROR);
  }

  if (is_numeric($country) == false) {
    $error = true;

    $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
  }

  if (ACCOUNT_STATE == 'true') {
    $zone_id = 0;
    $check_query = tep_db_query("select count(*) as total from zones where zone_country_id = '" . (int)$country . "'");
    $check = tep_db_fetch_array($check_query);
    $entry_state_has_zones = ($check['total'] > 0);
    if ($entry_state_has_zones == true) {
      $zone_query = tep_db_query("select distinct zone_id from zones where zone_country_id = '" . (int)$country . "' and (zone_name = '" . tep_db_input($state) . "' or zone_code = '" . tep_db_input($state) . "')");
      if (tep_db_num_rows($zone_query) == 1) {
        $zone = tep_db_fetch_array($zone_query);
        $zone_id = $zone['zone_id'];
      } else {
        $error = true;

        $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
      }
    } else {
      if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
        $error = true;

        $messageStack->add('create_account', ENTRY_STATE_ERROR);
      }
    }
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
  }

  if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
  } elseif ($password != $confirmation) {
    $error = true;

    $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
  }

  if (defined('ACCOUNT_LEGAL_AGREEMENTS') && ACCOUNT_LEGAL_AGREEMENTS == 'true') {
    $legal_agreements = tep_db_prepare_input($_POST['legal_agreements']);

    if ($legal_agreements != 'on') {
      $error = true;

      $messageStack->add('create_account', ENTRY_LEGAL_AGREEMENTS_ERROR);
    }
  }

  if ($error == false) {
    $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,
                            'customers_telephone' => $telephone,
                            'customers_fax' => $fax,
                            'customers_newsletter' => $newsletter,
                            'customers_password' => tep_encrypt_password($password));

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = tep_date_raw($dob);

    tep_db_perform('customers', $sql_data_array);

    $customer_id = tep_db_insert_id();

    $sql_data_array = array('customers_id' => $customer_id,
                            'entry_firstname' => $firstname,
                            'entry_lastname' => $lastname,
                            'entry_street_address' => $street_address,
                            'entry_postcode' => $postcode,
                            'entry_city' => $city,
                            'entry_country_id' => $country);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
    if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
    if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
    if (ACCOUNT_STATE == 'true') {
      if ($zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $state;
      }
    }

    tep_db_perform('address_book', $sql_data_array);

    $address_id = tep_db_insert_id();

    tep_db_query("update customers set customers_default_address_id = '" . (int)$address_id . "' where customers_id = '" . (int)$customer_id . "'");

    tep_db_query("insert into customers_info (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created) values ('" . (int)$customer_id . "', '0', now())");

    if (SESSION_RECREATE == 'True') {
      tep_session_recreate();
    }

    $customer_first_name = $firstname;
    $customer_default_address_id = $address_id;
    $customer_country_id = $country;
    $customer_zone_id = $zone_id;
    tep_session_register('customer_id');
    tep_session_register('customer_first_name');
    tep_session_register('customer_default_address_id');
    tep_session_register('customer_country_id');
    tep_session_register('customer_zone_id');

    if (defined('ACCOUNT_LEGAL_AGREEMENTS') && ACCOUNT_LEGAL_AGREEMENTS == 'true') {
      $legal_agreements_consents = date("Y-m-d H:i:s");
      tep_session_register('legal_agreements_consents');
    }

    // reset session token
    $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());

    // restore cart contents
    $cart->restore_contents();

    $wishlist->restore_lists();

    // build the message content
    $name = $firstname . ' ' . $lastname;

    if (ACCOUNT_GENDER == 'true') {
      if ($gender == 'm') {
        $email_text = sprintf(EMAIL_GREET_MR, $lastname);
      } else {
        $email_text = sprintf(EMAIL_GREET_MS, $lastname);
      }
    } else {
      $email_text = sprintf(EMAIL_GREET_NONE, $firstname);
    }

    $email_text .= EMAIL_WELCOME . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_WARNING;
    tep_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

    tep_redirect(tep_href_link('create_account_success.php'));
  }
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('create_account.php'));

require('includes/template_top.php');
require('includes/form_check.js.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('create_account') > 0) {
  echo $messageStack->output('create_account');
}
?>

  <p><?php echo sprintf(TEXT_ORIGIN_LOGIN, tep_href_link('login.php', tep_get_all_get_params())); ?></p>

<?php echo tep_draw_form('create_account', tep_href_link('create_account.php'), 'post', 'onsubmit="return check_form(create_account);"', true) . tep_draw_hidden_field('action', 'process'); ?>

  <div class="col-lg-6 mb-5">
    <div class="float-end mt-2 text-danger small"><?php echo FORM_REQUIRED_INFORMATION; ?></div>

    <h2><?php echo CATEGORY_PERSONAL; ?></h2>

    <?php
    if (ACCOUNT_GENDER == 'true') {
      ?>

      <div class="mb-3">
        <label class="form-check-label me-2" for="gender"><?php echo ENTRY_GENDER . (!empty(ENTRY_GENDER_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_GENDER_TEXT . '</span>' : ''); ?></label>
        <div class="form-check-inline">
          <label class="form-check-label">
            <?php echo tep_draw_radio_field('gender', 'm', false, 'class="form-check-input"') . ' ' . MALE; ?>
          </label>
        </div>
        <div class="form-check-inline">
          <label class="form-check-label">
            <?php echo tep_draw_radio_field('gender', 'f', false, 'class="form-check-input"') . ' ' . FEMALE; ?>
          </label>
        </div>
      </div>

      <?php
    }
    ?>

    <div class="mb-3">
      <label class="form-label" for="firstname"><?php echo ENTRY_FIRST_NAME . (!empty(ENTRY_FIRST_NAME_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_FIRST_NAME_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('firstname', null, 'id="firstname" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="lastname"><?php echo ENTRY_LAST_NAME . (!empty(ENTRY_LAST_NAME_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_LAST_NAME_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('lastname', null, 'id="lastname" class="form-control"'); ?>
    </div>

    <?php
    if (ACCOUNT_DOB == 'true') {
      ?>

      <div class="mb-3">
        <label class="form-label" for="dob"><?php echo ENTRY_DATE_OF_BIRTH . (!empty(ENTRY_DATE_OF_BIRTH_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_DATE_OF_BIRTH_TEXT . '</span>' : ''); ?></label>
        <?php echo tep_draw_input_field('dob', null, 'id="dob" class="form-control" placeholder="' . htmlspecialchars(DOB_FORMAT_STRING) . '"'); ?>
      </div>

      <?php
    }
    ?>

    <div class="mb-3">
      <label class="form-label" for="email-address"><?php echo ENTRY_EMAIL_ADDRESS . (!empty(ENTRY_EMAIL_ADDRESS_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_EMAIL_ADDRESS_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('email_address', null, 'id="email-address" class="form-control"', 'email'); ?>
    </div>

    <?php
    if (ACCOUNT_COMPANY == 'true') {
      ?>

      <h2><?php echo CATEGORY_COMPANY; ?></h2>

      <div class="mb-3">
        <label class="form-label" for="company"><?php echo ENTRY_COMPANY . (!empty(ENTRY_COMPANY_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_COMPANY_TEXT . '</span>' : ''); ?></label>
        <?php echo tep_draw_input_field('company', null, 'id="company" class="form-control"'); ?>
      </div>

      <?php
    }
    ?>

    <h2><?php echo CATEGORY_ADDRESS; ?></h2>

    <div class="mb-3">
      <label class="form-label" for="street-address"><?php echo ENTRY_STREET_ADDRESS . (!empty(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_STREET_ADDRESS_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('street_address', null, 'id="street-address" class="form-control"'); ?>
    </div>

    <?php
    if (ACCOUNT_SUBURB == 'true') {
      ?>

      <div class="mb-3">
        <label class="form-label" for="suburb"><?php echo ENTRY_SUBURB . (!empty(ENTRY_SUBURB_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_SUBURB_TEXT . '</span>' : ''); ?></label>
        <?php echo tep_draw_input_field('suburb', null, 'id="suburb" class="form-control"'); ?>
      </div>

      <?php
    }
    ?>

    <div class="mb-3">
      <label class="form-label" for="postcode"><?php echo ENTRY_POST_CODE . (!empty(ENTRY_POST_CODE_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_POST_CODE_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('postcode', null, 'id="postcode" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="city"><?php echo ENTRY_CITY . (!empty(ENTRY_CITY_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_CITY_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('city', null, 'id="city" class="form-control"'); ?>
    </div>

    <?php
    if (ACCOUNT_STATE == 'true') {
      ?>

      <div class="mb-3">
        <label class="form-label" for="state"><?php echo ENTRY_STATE . (!empty(ENTRY_STATE_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_STATE_TEXT . '</span>' : ''); ?></label>

        <?php
        if ($process == true) {
          if ($entry_state_has_zones == true) {
            $zones_array = array();
            $zones_query = tep_db_query("select zone_name from zones where zone_country_id = '" . (int)$country . "' order by zone_name");
            while ($zones_values = tep_db_fetch_array($zones_query)) {
              $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
            }
            echo tep_draw_pull_down_menu('state', $zones_array, null, 'id="state" class="form-select"');
          } else {
            echo tep_draw_input_field('state', null, 'id="state" class="form-control"');
          }
        } else {
          echo tep_draw_input_field('state', null, 'id="state" class="form-control"');
        }
        ?>

      </div>

      <?php
    }
    ?>


    <div class="mb-3">
      <label class="form-label" for="country"><?php echo ENTRY_COUNTRY . (!empty(ENTRY_COUNTRY_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_COUNTRY_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_get_country_list('country', STORE_COUNTRY, 'id="country" class="form-select"'); ?>
    </div>

    <h2><?php echo CATEGORY_CONTACT; ?></h2>

    <div class="mb-3">
      <label class="form-label" for="telephone"><?php echo ENTRY_TELEPHONE_NUMBER . (!empty(ENTRY_TELEPHONE_NUMBER_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_TELEPHONE_NUMBER_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('telephone', null, 'id="telephone" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="fax"><?php echo ENTRY_FAX_NUMBER . (!empty(ENTRY_FAX_NUMBER_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_FAX_NUMBER_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_input_field('fax', null, 'id="fax" class="form-control"'); ?>
    </div>
    <div class="mb-3 form-check">
      <?php echo tep_draw_checkbox_field('newsletter', '1', false, 'class="form-check-input" id="newsletter"') . (!empty(ENTRY_NEWSLETTER_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_NEWSLETTER_TEXT . '</span>' : ''); ?>
      <label class="form-check-label" for="newsletter"><?php echo ENTRY_NEWSLETTER; ?></label>
    </div>

    <h2><?php echo CATEGORY_PASSWORD; ?></h2>

    <div class="mb-3">
      <label class="form-label" for="password"><?php echo ENTRY_PASSWORD . (!empty(ENTRY_PASSWORD_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_PASSWORD_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_password_field('password', null, 'id="password" class="form-control"'); ?>
    </div>
    <div class="mb-3">
      <label class="form-label" for="confirmation"><?php echo ENTRY_PASSWORD_CONFIRMATION . (!empty(ENTRY_PASSWORD_CONFIRMATION_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_PASSWORD_CONFIRMATION_TEXT . '</span>' : ''); ?></label>
      <?php echo tep_draw_password_field('confirmation', null, 'id="confirmation" class="form-control"'); ?>
    </div>

    <?php
    if (defined('ACCOUNT_LEGAL_AGREEMENTS') && ACCOUNT_LEGAL_AGREEMENTS == 'true') {
      ?>

      <div class="mb-3 form-check">
        <?php echo tep_draw_checkbox_field('legal_agreements', 'on', false, 'class="form-check-input" id="legal-agreements"') . (!empty(ENTRY_LEGAL_AGREEMENTS_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_LEGAL_AGREEMENTS_TEXT . '</span>' : ''); ?>
        <label class="form-check-label" for="legal-agreements"><?php echo sprintf(ENTRY_LEGAL_AGREEMENTS, tep_href_link('information.php', 'pages_id=3'), tep_href_link('information.php', 'pages_id=2')); ?></label>
      </div>

      <?php
    }
    ?>

    <div class="text-end">
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'person', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');