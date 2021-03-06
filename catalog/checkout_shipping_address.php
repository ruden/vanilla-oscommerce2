<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

// if the customer is not logged on, redirect them to the login page
if (!isset($_SESSION['customer_id'])) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link('login.php'));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
  tep_redirect(tep_href_link('shopping_cart.php'));
}

// needs to be included earlier to set the success message in the messageStack
require('includes/languages/' . $language . '/checkout_shipping_address.php');

require('includes/classes/order.php');
$order = new order;

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
if ($order->content_type == 'virtual') {
  if (!isset($_SESSION['shipping'])) tep_session_register('shipping');
  $shipping = false;
  if (!isset($_SESSION['sendto'])) tep_session_register('sendto');
  $sendto = false;
  tep_redirect(tep_href_link('checkout_payment.php'));
}

$error = false;
$process = false;
if (isset($_POST['action']) && ($_POST['action'] == 'submit') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
// process a new shipping address
  if (!empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['street_address'])) {
    $process = true;

    if (ACCOUNT_GENDER == 'true') $gender = tep_db_prepare_input($_POST['gender']);
    if (ACCOUNT_COMPANY == 'true') $company = tep_db_prepare_input($_POST['company']);
    $firstname = tep_db_prepare_input($_POST['firstname']);
    $lastname = tep_db_prepare_input($_POST['lastname']);
    $street_address = tep_db_prepare_input($_POST['street_address']);
    if (ACCOUNT_SUBURB == 'true') $suburb = tep_db_prepare_input($_POST['suburb']);
    $postcode = tep_db_prepare_input($_POST['postcode']);
    $city = tep_db_prepare_input($_POST['city']);
    $country = tep_db_prepare_input($_POST['country']);
    if (ACCOUNT_STATE == 'true') {
      if (isset($_POST['zone_id'])) {
        $zone_id = tep_db_prepare_input($_POST['zone_id']);
      } else {
        $zone_id = false;
      }
      $state = tep_db_prepare_input($_POST['state']);
    }

    if (ACCOUNT_GENDER == 'true') {
      if (($gender != 'm') && ($gender != 'f')) {
        $error = true;

        $messageStack->add('checkout_address', ENTRY_GENDER_ERROR);
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('checkout_address', ENTRY_FIRST_NAME_ERROR);
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('checkout_address', ENTRY_LAST_NAME_ERROR);
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = true;

      $messageStack->add('checkout_address', ENTRY_STREET_ADDRESS_ERROR);
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = true;

      $messageStack->add('checkout_address', ENTRY_POST_CODE_ERROR);
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $error = true;

      $messageStack->add('checkout_address', ENTRY_CITY_ERROR);
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

          $messageStack->add('checkout_address', ENTRY_STATE_ERROR_SELECT);
        }
      } else {
        if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
          $error = true;

          $messageStack->add('checkout_address', ENTRY_STATE_ERROR);
        }
      }
    }

    if ((is_numeric($country) == false) || ($country < 1)) {
      $error = true;

      $messageStack->add('checkout_address', ENTRY_COUNTRY_ERROR);
    }

    if ($error == false) {
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

      if (!isset($_SESSION['sendto'])) tep_session_register('sendto');

      tep_db_perform('address_book', $sql_data_array);

      $sendto = tep_db_insert_id();

      if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);

      tep_redirect(tep_href_link('checkout_shipping.php'));
    }
// process the selected shipping destination
  } elseif (isset($_POST['address'])) {
    $reset_shipping = false;
    if (isset($_SESSION['sendto'])) {
      if ($sendto != $_POST['address']) {
        if (isset($_SESSION['shipping'])) {
          $reset_shipping = true;
        }
      }
    } else {
      tep_session_register('sendto');
    }

    $sendto = $_POST['address'];

    $check_address_query = tep_db_query("select count(*) as total from address_book where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
    $check_address = tep_db_fetch_array($check_address_query);

    if ($check_address['total'] == '1') {
      if ($reset_shipping == true) unset($_SESSION['shipping']);
      tep_redirect(tep_href_link('checkout_shipping.php'));
    } else {
      unset($_SESSION['sendto']);
    }
  } else {
    if (!isset($_SESSION['sendto'])) tep_session_register('sendto');
    $sendto = $customer_default_address_id;

    tep_redirect(tep_href_link('checkout_shipping.php'));
  }
}

// if no shipping destination address was selected, use their own address as default
if (!isset($_SESSION['sendto'])) {
  $sendto = $customer_default_address_id;
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('checkout_shipping.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('checkout_shipping_address.php'));

$addresses_count = tep_count_customer_address_book_entries();

require('includes/template_top.php');
?>

  <script>
    var selected;

    function selectRowEffect(object, buttonSelect) {
      if (!selected) {
        if (document.getElementById) {
          selected = document.getElementById('defaultSelected');
        } else {
          selected = document.all['defaultSelected'];
        }
      }

      if (selected) selected.className = 'moduleRow bg-white';
      object.className = 'moduleRowSelected bg-light';
      selected = object;

// one button is not an array
      if (document.checkout_address.address[0]) {
        document.checkout_address.address[buttonSelect].checked = true;
      } else {
        document.checkout_address.address.checked = true;
      }
    }

    function rowOverEffect(object) {
      if (object.className == 'moduleRow bg-white') object.className = 'moduleRowOver bg-light';
    }

    function rowOutEffect(object) {
      if (object.className == 'moduleRowOver bg-light') object.className = 'moduleRow bg-white';
    }

    function check_form_optional(form_name) {
      var form = form_name;

      var firstname = form.elements['firstname'].value;
      var lastname = form.elements['lastname'].value;
      var street_address = form.elements['street_address'].value;

      if (firstname == '' && lastname == '' && street_address == '') {
        return true;
      } else {
        return check_form(form_name);
      }
    }
  </script>
<?php require('includes/form_check.js.php'); ?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('checkout_address') > 0) {
  echo $messageStack->output('checkout_address');
}
?>

<?php echo tep_draw_form('checkout_address', tep_href_link('checkout_shipping_address.php'), 'post', 'onsubmit="return check_form_optional(checkout_address);"', true); ?>

  <div class="mb-5">

    <?php
    if ($process == false) {
      ?>

      <h2><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></h2>

      <div class="mb-3">
        <div class="float-end ms-3">
          <div class="fw-bold"><?php echo TITLE_SHIPPING_ADDRESS; ?></div>

          <p><?php echo tep_address_label($customer_id, $sendto, true, ' ', '<br />'); ?></p>
        </div>

        <p><?php echo TEXT_SELECTED_SHIPPING_DESTINATION; ?></p>
      </div>


      <div class="clearfix"></div>

      <?php
      if ($addresses_count > 1) {
        ?>

        <h2><?php echo TABLE_HEADING_ADDRESS_BOOK_ENTRIES; ?></h2>

        <div class="mb-3">
          <div class="float-end fw-bold">
            <?php echo TITLE_PLEASE_SELECT; ?>
          </div>

          <p><?php echo TEXT_SELECT_OTHER_SHIPPING_DESTINATION; ?></p>
        </div>

        <div class="mb-3">
          <table class="table table-borderless table-sm">
            <tbody>

            <?php
            $radio_buttons = 0;

            $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from address_book where customers_id = '" . (int)$customer_id . "'");
            while ($addresses = tep_db_fetch_array($addresses_query)) {
              $format_id = tep_get_address_format_id($addresses['country_id']);

              if ($addresses['address_book_id'] == $sendto) {
                echo '      <tr id="defaultSelected" class="moduleRowSelected bg-light" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
              } else {
                echo '      <tr class="moduleRow bg-white" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
              }
              ?>

              <td class="fw-bold"><?php echo $addresses['firstname'] . ' ' . $addresses['lastname']; ?></td>
              <td class="text-end form-check"><?php echo tep_draw_radio_field('address', $addresses['address_book_id'], ($addresses['address_book_id'] == $sendto), 'class="form-check-input float-none"'); ?></td>
              </tr>
              <tr>
                <td colspan="2">
                  <div class="ms-3"><?php echo tep_address_format($format_id, $addresses, true, ' ', ', '); ?></div>
                </td>
              </tr>

              <?php
              $radio_buttons++;
            }
            ?>

            </tbody>
          </table>
        </div>

        <div class="btn-toolbar justify-content-between mb-3">
          <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link(($process == true ? 'checkout_shipping_address.php' : 'checkout_shipping.php')), 'btn-light'); ?>
          <?php echo tep_draw_hidden_field('action', 'submit') . tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
        </div>

        <?php
      }
    }

    if ($addresses_count < MAX_ADDRESS_BOOK_ENTRIES) {
      ?>

      <h2><?php echo TABLE_HEADING_NEW_SHIPPING_ADDRESS; ?></h2>

      <p><?php echo TEXT_CREATE_NEW_SHIPPING_ADDRESS; ?></p>

      <div class="col-lg-6">
        <div class="text-end text-danger small"><?php echo FORM_REQUIRED_INFORMATION; ?></div>

        <div class="mb-3">
          <?php require('includes/modules/address_book_details.php'); ?>
        </div>
      </div>

      <?php
    }
    ?>

    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link(($process == true ? 'checkout_shipping_address.php' : 'checkout_shipping.php')), 'btn-light'); ?>
      <?php echo tep_draw_hidden_field('action', 'submit') . tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');