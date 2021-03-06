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

// if no shipping destination address was selected, use the customers own address as default
if (!isset($_SESSION['sendto'])) {
  tep_session_register('sendto');
  $sendto = $customer_default_address_id;
} else {
// verify the selected shipping address
  if ((is_array($sendto) && empty($sendto)) || is_numeric($sendto)) {
    $check_address_query = tep_db_query("select count(*) as total from address_book where customers_id = '" . (int)$customer_id . "' and address_book_id = '" . (int)$sendto . "'");
    $check_address = tep_db_fetch_array($check_address_query);

    if ($check_address['total'] != '1') {
      $sendto = $customer_default_address_id;
      if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);
    }
  }
}

require('includes/classes/order.php');
$order = new order;

// register a random ID in the session to check throughout the checkout procedure
// against alterations in the shopping cart contents
if (!isset($_SESSION['cartID'])) {
  tep_session_register('cartID');
} elseif (($cartID != $cart->cartID) && isset($_SESSION['shipping'])) {
  unset($_SESSION['shipping']);
}

$cartID = $cart->cartID = $cart->generate_cart_id();

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
if ($order->content_type == 'virtual') {
  if (!isset($_SESSION['shipping'])) tep_session_register('shipping');
  $shipping = false;
  $sendto = false;
  tep_redirect(tep_href_link('checkout_payment.php'));
}

$total_weight = $cart->show_weight();
$total_count = $cart->count_contents();

// load all enabled shipping modules
require('includes/classes/shipping.php');
$shipping_modules = new shipping;

if (defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true')) {
  $pass = false;

  switch (MODULE_ORDER_TOTAL_SHIPPING_DESTINATION) {
    case 'national':
      if ($order->delivery['country_id'] == STORE_COUNTRY) {
        $pass = true;
      }
      break;
    case 'international':
      if ($order->delivery['country_id'] != STORE_COUNTRY) {
        $pass = true;
      }
      break;
    case 'both':
      $pass = true;
      break;
  }

  $free_shipping = false;
  if (($pass == true) && ($order->info['total'] >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) {
    $free_shipping = true;

    include('includes/languages/' . $language . '/modules/order_total/ot_shipping.php');
  }
} else {
  $free_shipping = false;
}

// process the selected shipping method
if (isset($_POST['action']) && ($_POST['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  if (!isset($_SESSION['comments'])) tep_session_register('comments');
  if (!empty($_POST['comments'])) {
    $comments = tep_db_prepare_input($_POST['comments']);
  }

  if (!isset($_SESSION['shipping'])) tep_session_register('shipping');

  if ((tep_count_shipping_modules() > 0) || ($free_shipping == true)) {
    if ((isset($_POST['shipping'])) && (strpos($_POST['shipping'], '_'))) {
      $shipping = $_POST['shipping'];

      list($module, $method) = explode('_', $shipping);
      if (is_object($$module) || ($shipping == 'free_free')) {
        if ($shipping == 'free_free') {
          $quote[0]['methods'][0]['title'] = FREE_SHIPPING_TITLE;
          $quote[0]['methods'][0]['cost'] = '0';
        } else {
          $quote = $shipping_modules->quote($method, $module);
        }
        if (isset($quote['error'])) {
          unset($_SESSION['shipping']);
        } else {
          if ((isset($quote[0]['methods'][0]['title'])) && (isset($quote[0]['methods'][0]['cost']))) {
            $shipping = array('id' => $shipping,
                              'title' => (($free_shipping == true) ? $quote[0]['methods'][0]['title'] : $quote[0]['module'] . ' (' . $quote[0]['methods'][0]['title'] . ')'),
                              'cost' => $quote[0]['methods'][0]['cost']);

            tep_redirect(tep_href_link('checkout_payment.php'));
          }
        }
      } else {
        unset($_SESSION['shipping']);
      }
    }
  } else {
    if (defined('SHIPPING_ALLOW_UNDEFINED_ZONES') && (SHIPPING_ALLOW_UNDEFINED_ZONES == 'False')) {
      unset($_SESSION['shipping']);
    } else {
      $shipping = false;

      tep_redirect(tep_href_link('checkout_payment.php'));
    }
  }
}

// get all available shipping quotes
$quotes = $shipping_modules->quote();

// if no shipping method has been selected, automatically select the first method.
// if the modules status was changed when none were available, to save on implementing
// a javascript force-selection method, also automatically select the first shipping
// method if more than one module is now enabled
if (!isset($_SESSION['shipping']) || (isset($_SESSION['shipping']) && ($shipping == false) && (tep_count_shipping_modules() > 1))) $shipping = $shipping_modules->get_first();

require('includes/languages/' . $language . '/checkout_shipping.php');

if (defined('SHIPPING_ALLOW_UNDEFINED_ZONES') && (SHIPPING_ALLOW_UNDEFINED_ZONES == 'False') && !isset($_SESSION['shipping']) && ($shipping == false)) {
  $messageStack->add_session('checkout_address', ERROR_NO_SHIPPING_AVAILABLE_TO_SHIPPING_ADDRESS);

  tep_redirect(tep_href_link('checkout_shipping_address.php'));
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('checkout_shipping.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('checkout_shipping.php'));

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
      if (document.checkout_address.shipping[0]) {
        document.checkout_address.shipping[buttonSelect].checked = true;
      } else {
        document.checkout_address.shipping.checked = true;
      }
    }

    function rowOverEffect(object) {
      if (object.className == 'moduleRow bg-white') object.className = 'moduleRowOver bg-light';
    }

    function rowOutEffect(object) {
      if (object.className == 'moduleRowOver bg-light') object.className = 'moduleRow bg-white';
    }
  </script>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="col-lg-6 mx-auto">
    <div class="progress mb-3" style="height: 1px;">
      <div class="progress-bar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
    </div>

    <div class="row mb-3">
      <div class="col text-center text-primary"><?php echo CHECKOUT_BAR_DELIVERY; ?> &#8594</div>
      <div class="col text-center text-muted"><?php echo CHECKOUT_BAR_PAYMENT; ?> &#8594</div>
      <div class="col text-center text-muted"><?php echo CHECKOUT_BAR_CONFIRMATION; ?></div>
    </div>
  </div>

<?php echo tep_draw_form('checkout_address', tep_href_link('checkout_shipping.php'), 'post', '', true) . tep_draw_hidden_field('action', 'process'); ?>

  <div class="mb-5">
    <h2><?php echo TABLE_HEADING_SHIPPING_ADDRESS; ?></h2>

    <div class="mb-3">
      <div class="float-end ms-3">
        <div class="fw-bold"><?php echo TITLE_SHIPPING_ADDRESS; ?></div>

        <p><?php echo tep_address_label($customer_id, $sendto, true, ' ', '<br />'); ?></p>
      </div>

      <p><?php echo TEXT_CHOOSE_SHIPPING_DESTINATION; ?></p>

      <?php echo tep_draw_button(IMAGE_BUTTON_CHANGE_ADDRESS, 'home', tep_href_link('checkout_shipping_address.php')); ?>
    </div>

    <div class="clearfix"></div>

    <?php
    if (tep_count_shipping_modules() > 0) {
      ?>

      <h2><?php echo TABLE_HEADING_SHIPPING_METHOD; ?></h2>

      <?php
      if (sizeof($quotes) > 1 && sizeof($quotes[0]) > 1) {
        ?>

        <div class="mb-3">
          <div class="float-end fw-bold">
            <?php echo TITLE_PLEASE_SELECT; ?>
          </div>

          <p><?php echo TEXT_CHOOSE_SHIPPING_METHOD; ?></p>
        </div>

        <?php
      } elseif ($free_shipping == false) {
        ?>

        <p><?php echo TEXT_ENTER_SHIPPING_INFORMATION; ?></p>

        <?php
      }
      ?>

      <div class="mb-3">
        <table class="table table-borderless table-sm">
          <tbody>

          <?php
          if ($free_shipping == true) {
            ?>

            <tr>
              <td>
                <span class="fw-bold me-1"><?php echo FREE_SHIPPING_TITLE; ?></span><?php echo $quotes[$i]['icon']; ?>
              </td>
            </tr>
            <tr id="defaultSelected" class="moduleRowSelected bg-light" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, 0)">
              <td class="ms-3"><?php echo sprintf(FREE_SHIPPING_DESCRIPTION, $currencies->format(MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER)) . tep_draw_hidden_field('shipping', 'free_free'); ?></td>
            </tr>

            <?php
          } else {
            $radio_buttons = 0;
            for ($i = 0, $n = sizeof($quotes); $i < $n; $i++) {
              ?>

              <tr>
                <td colspan="3">
                  <span class="fw-bold"><?php echo $quotes[$i]['module']; ?></span>&nbsp;<?php if (isset($quotes[$i]['icon']) && !empty($quotes[$i]['icon'])) {
                    echo $quotes[$i]['icon'];
                  } ?></td>
              </tr>

              <?php
              if (isset($quotes[$i]['error'])) {
                ?>

                <tr>
                  <td colspan="3"><?php echo $quotes[$i]['error']; ?></td>
                </tr>

                <?php
              } else {
                for ($j = 0, $n2 = sizeof($quotes[$i]['methods']); $j < $n2; $j++) {
// set the radio button to be checked if it is the method chosen
                  $checked = (($quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'] == $shipping['id']) ? true : false);

                  if (($checked == true) || ($n == 1 && $n2 == 1)) {
                    echo '      <tr id="defaultSelected" class="moduleRowSelected bg-light" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                  } else {
                    echo '      <tr class="moduleRow bg-white" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="selectRowEffect(this, ' . $radio_buttons . ')">' . "\n";
                  }
                  ?>

                  <td class="w-75 ps-3"><?php echo $quotes[$i]['methods'][$j]['title']; ?></td>

                  <?php
                  if (($n > 1) || ($n2 > 1)) {
                    ?>

                    <td><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))); ?></td>
                    <td class="text-end form-check"><?php echo tep_draw_radio_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id'], $checked, 'class="form-check-input float-none"'); ?></td>

                    <?php
                  } else {
                    ?>

                    <td class="text-end" colspan="2"><?php echo $currencies->format(tep_add_tax($quotes[$i]['methods'][$j]['cost'], (isset($quotes[$i]['tax']) ? $quotes[$i]['tax'] : 0))) . tep_draw_hidden_field('shipping', $quotes[$i]['id'] . '_' . $quotes[$i]['methods'][$j]['id']); ?></td>

                    <?php
                  }
                  ?>

                  </tr>

                  <?php
                  $radio_buttons++;
                }
              }
            }
          }
          ?>

          </tbody>
        </table>
      </div>

      <?php
    }
    ?>

    <h2><?php echo TABLE_HEADING_COMMENTS; ?></h2>

    <div class="mb-3 col-lg-6">
      <?php echo tep_draw_textarea_field('comments', null, 'class="form-control" rows="3"'); ?>
    </div>

    <div class="text-end"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?></div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');