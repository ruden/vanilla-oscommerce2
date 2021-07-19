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

// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!isset($_SESSION['shipping'])) {
  tep_redirect(tep_href_link('checkout_shipping.php'));
}

// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($cart->cartID) && isset($_SESSION['cartID'])) {
  if ($cart->cartID != $cartID) {
    tep_redirect(tep_href_link('checkout_shipping.php'));
  }
}

// Stock Check
if ((STOCK_CHECK == 'true') && (STOCK_ALLOW_CHECKOUT != 'true')) {
  $products = $cart->get_products();
  for ($i = 0, $n = sizeof($products); $i < $n; $i++) {
    if (tep_check_stock($products[$i]['id'], $products[$i]['quantity'])) {
      tep_redirect(tep_href_link('shopping_cart.php'));
      break;
    }
  }
}

// if no billing destination address was selected, use the customers own address as default
if (!isset($_SESSION['billto'])) {
  tep_session_register('billto');
  $billto = $customer_default_address_id;
} else {
// verify the selected billing address
  if ((is_array($billto) && empty($billto)) || is_numeric($billto)) {
    $check_address_query = tep_db_query("select count(*) as total from address_book where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$billto . "'");
    $check_address = tep_db_fetch_array($check_address_query);

    if ($check_address['total'] != '1') {
      $billto = $customer_default_address_id;
      if (isset($_SESSION['payment'])) unset($_SESSION['payment']);
    }
  }
}

require('includes/classes/order.php');
$order = new order;

if (!isset($_SESSION['comments'])) tep_session_register('comments');
if (isset($_POST['comments']) && !empty($_POST['comments'])) {
  $comments = tep_db_prepare_input($_POST['comments']);
}

$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();

// load all enabled payment modules
require('includes/classes/payment.php');
$payment_modules = new payment;

require('includes/languages/' . $language . '/checkout_payment.php');

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('checkout_shipping.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('checkout_payment.php'));

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
      if (document.checkout_payment.payment[0]) {
        document.checkout_payment.payment[buttonSelect].checked = true;
      } else {
        document.checkout_payment.payment.checked = true;
      }
    }

    function rowOverEffect(object) {
      if (object.className == 'moduleRow bg-white') object.className = 'moduleRowOver bg-light';
    }

    function rowOutEffect(object) {
      if (object.className == 'moduleRowOver bg-light') object.className = 'moduleRow bg-white';
    }
  </script>
<?php echo $payment_modules->javascript_validation(); ?>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="col-lg-6 mx-auto">
    <div class="progress mb-3" style="height: 1px;">
      <div class="progress-bar" style="width: 66%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="row mb-3">
      <div class="col text-center text-primary"><?php echo CHECKOUT_BAR_DELIVERY; ?> &#8594</div>
      <div class="col text-center text-primary"><?php echo CHECKOUT_BAR_PAYMENT; ?> &#8594</div>
      <div class="col text-center text-muted"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></div>
    </div>
  </div>

<?php echo tep_draw_form('checkout_payment', tep_href_link('checkout_confirmation.php'), 'post', 'onsubmit="return check_form();"', true); ?>

  <div class="mb-5">

    <?php
    if (isset($_GET['payment_error']) && is_object(${$_GET['payment_error']}) && ($error = ${$_GET['payment_error']}->get_error())) {
      ?>

      <div class="alert alert-danger d-flex align-items-start" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
        </svg>
        <div>
          <strong class="alert-heading"><?php echo tep_output_string_protected($error['title']); ?></strong>
          <p class="mb-0"><?php echo tep_output_string_protected($error['error']); ?></p>
        </div>
      </div>

      <?php
    }
    ?>

    <h2><?php echo TABLE_HEADING_BILLING_ADDRESS; ?></h2>

    <div class="mb-3">
      <div class="float-end">
        <div class="fw-bold"><?php echo TITLE_BILLING_ADDRESS; ?></div>

        <p><?php echo tep_address_label($customer_id, $billto, true, ' ', '<br />'); ?></p>
      </div>

      <p><?php echo TEXT_SELECTED_BILLING_DESTINATION; ?></p>

      <?php echo tep_draw_button(IMAGE_BUTTON_CHANGE_ADDRESS, 'home', tep_href_link('checkout_payment_address.php')); ?>
    </div>

    <div class="clearfix"></div>

    <h2><?php echo TABLE_HEADING_PAYMENT_METHOD; ?></h2>

    <?php
    $selection = $payment_modules->selection();

    if (sizeof($selection) > 1) {
      ?>

      <div class="mb-3">
        <div class="float-end fw-bold">
          <?php echo TITLE_PLEASE_SELECT; ?>
        </div>

        <p><?php echo TEXT_SELECT_PAYMENT_METHOD; ?></p>
      </div>

      <?php
    } else {
      ?>

      <p><?php echo TEXT_ENTER_PAYMENT_INFORMATION; ?></p>

      <?php
    }
    ?>

    <div class="mb-3">

      <?php
      $radio_buttons = 0;
      for ($i = 0, $n = sizeof($selection); $i < $n; $i++) {
        ?>

        <table class="table table-borderless table-sm mb-0">
          <tbody>

          <?php
          if (($selection[$i]['id'] == $payment) || ($n == 1)) {
            echo '      <tr id="defaultSelected" class="moduleRowSelected bg-light" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
          } else {
            echo '      <tr class="moduleRow bg-white" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
          }
          ?>

          <td class="fw-bold"><?php echo $selection[$i]['module']; ?></td>
          <td class="text-end form-check">

            <?php
            if (sizeof($selection) > 1) {
              echo tep_draw_radio_field('payment', $selection[$i]['id'], ($selection[$i]['id'] == $payment), 'class="form-check-input float-none"');
            } else {
              echo tep_draw_hidden_field('payment', $selection[$i]['id']);
            }
            ?>

          </td>
          </tr>

          <?php
          if (isset($selection[$i]['error'])) {
            ?>

            <tr>
              <td colspan="2"><?php echo $selection[$i]['error']; ?></td>
            </tr>

            <?php
          } elseif (isset($selection[$i]['fields']) && is_array($selection[$i]['fields'])) {
            ?>

            <tr>
              <td colspan="2">
                <table>
                  <tbody>

                  <?php
                  for ($j = 0, $n2 = sizeof($selection[$i]['fields']); $j < $n2; $j++) {
                    ?>

                    <tr>
                      <td><?php echo $selection[$i]['fields'][$j]['title']; ?></td>
                      <td><?php echo $selection[$i]['fields'][$j]['field']; ?></td>
                    </tr>

                    <?php
                  }
                  ?>

                  </tbody>
                </table>
              </td>
            </tr>

            <?php
          }
          ?>

          </tbody>
        </table>

        <?php
        $radio_buttons++;
      }
      ?>

    </div>

    <h2><?php echo TABLE_HEADING_COMMENTS; ?></h2>

    <div class="mb-3 col-lg-6">
      <?php echo tep_draw_textarea_field('comments', $comments, 'class="form-control" rows="3"'); ?>
    </div>
    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('checkout_shipping.php'), 'btn-light'); ?>
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');