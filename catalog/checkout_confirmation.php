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
  $navigation->set_snapshot(array('mode' => 'SSL', 'page' => 'checkout_payment.php'));
  tep_redirect(tep_href_link('login.php'));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($cart->count_contents() < 1) {
  tep_redirect(tep_href_link('shopping_cart.php'));
}

// avoid hack attempts during the checkout procedure by checking the internal cartID
if (isset($cart->cartID) && isset($_SESSION['cartID'])) {
  if ($cart->cartID != $cartID) {
    tep_redirect(tep_href_link('checkout_shipping.php'));
  }
}

// if no shipping method has been selected, redirect the customer to the shipping method selection page
if (!isset($_SESSION['shipping'])) {
  tep_redirect(tep_href_link('checkout_shipping.php'));
}

if (!isset($_SESSION['payment'])) tep_session_register('payment');
if (isset($_POST['payment'])) $payment = $_POST['payment'];

if (!isset($_SESSION['comments'])) tep_session_register('comments');
if (isset($_POST['comments']) && !empty($_POST['comments'])) {
  $comments = tep_db_prepare_input($_POST['comments']);
}

// load the selected payment module
require('includes/classes/payment.php');
$payment_modules = new payment($payment);

require('includes/classes/order.php');
$order = new order;

$payment_modules->update_status();

if (($payment_modules->selected_module != $payment) || (is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment)) || (is_object($$payment) && ($$payment->enabled == false))) {
  tep_redirect(tep_href_link('checkout_payment.php', 'error_message=' . urlencode(ERROR_NO_PAYMENT_MODULE_SELECTED)));
}

if (is_array($payment_modules->modules)) {
  $payment_modules->pre_confirmation_check();
}

// load the selected shipping module
require('includes/classes/shipping.php');
$shipping_modules = new shipping($shipping);

require('includes/classes/order_total.php');
$order_total_modules = new order_total;
$order_total_modules->process();

// Stock Check
$any_out_of_stock = false;
if (STOCK_CHECK == 'true') {
  for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
    if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
      $any_out_of_stock = true;
    }
  }
  // Out of Stock
  if ((STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true)) {
    tep_redirect(tep_href_link('shopping_cart.php'));
  }
}

require('includes/languages/' . $language . '/checkout_confirmation.php');

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('checkout_shipping.php'));
$breadcrumb->add(NAVBAR_TITLE_2);

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('checkout_confirmation') > 0) {
  echo $messageStack->output('checkout_confirmation');
}

if (isset($$payment->form_action_url)) {
  $form_action_url = $$payment->form_action_url;
} else {
  $form_action_url = tep_href_link('checkout_process.php');
}

echo tep_draw_form('checkout_confirmation', $form_action_url, 'post');
?>

  <div class="mb-5">

    <div class="row">

      <?php
      if ($sendto != false) {
        ?>

        <div class="col-md">
          <h2><?php echo HEADING_SHIPPING_INFORMATION; ?></h2>

          <div class="mb-3">

            <?php echo '<span class="fw-bold">' . HEADING_DELIVERY_ADDRESS . '</span> <a href="' . tep_href_link('checkout_shipping_address.php') . '"><span>(' . TEXT_EDIT . ')</span></a>'; ?>

            <p><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?></p>

            <?php
            if ($order->info['shipping_method']) {
              ?>

              <?php echo '<span class="fw-bold">' . HEADING_SHIPPING_METHOD . '</span> <a href="' . tep_href_link('checkout_shipping.php') . '"><span>(' . TEXT_EDIT . ')</span></a>'; ?>

              <p><?php echo $order->info['shipping_method']; ?></p>

              <?php
            }
            ?>

          </div>
        </div>

        <?php
      }
      ?>

      <div class="col-md">
        <h2><?php echo HEADING_BILLING_INFORMATION; ?></h2>

        <div class="mb-3">
          <?php echo '<strong>' . HEADING_BILLING_ADDRESS . '</strong> <a href="' . tep_href_link('checkout_payment_address.php') . '"><span>(' . TEXT_EDIT . ')</span></a>'; ?>

          <p><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?></p>

          <?php echo '<strong>' . HEADING_PAYMENT_METHOD . '</strong> <a href="' . tep_href_link('checkout_payment.php') . '"><span>(' . TEXT_EDIT . ')</span></a>'; ?>

          <p><?php echo $order->info['payment_method']; ?></p>
        </div>
      </div>

    </div>

    <table class="table align-top">
      <thead>
      <tr>

        <?php
        if (sizeof($order->info['tax_groups']) > 1) {
          ?>

          <th colspan="2"><?php echo HEADING_PRODUCTS . '<a href="' . tep_href_link('shopping_cart.php') . '"><span class="fw-normal ms-1">(' . TEXT_EDIT . ')</span></a>'; ?></th>
          <th class="text-end"><strong><?php echo HEADING_TAX; ?></strong></th>
          <th class="text-end"><strong><?php echo HEADING_TOTAL; ?></strong></th>

          <?php
        } else {
          ?>

          <th colspan="3"><?php echo HEADING_PRODUCTS . '<a href="' . tep_href_link('shopping_cart.php') . '"><span class="fw-normal ms-1">(' . TEXT_EDIT . ')</span></a>'; ?></th>

          <?php
        }
        ?>

      </tr>
      </thead>

      <?php
      for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
        echo '          <tr>' . "\n" .
             '            <td align="right" valign="top" width="30">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
             '            <td valign="top">' . $order->products[$i]['name'];

        if (STOCK_CHECK == 'true') {
          echo tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty']);
        }

        if ((isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0)) {
          for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++) {
            echo '<br /><span class="small"> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</span>';
          }
        }

        echo '</td>' . "\n";

        if (sizeof($order->info['tax_groups']) > 1) {
          echo '            <td class="text-end">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n";
        }

        echo '            <td class="text-end">' . $currencies->display_price($order->products[$i]['final_price'], $order->products[$i]['tax'], $order->products[$i]['qty']) . '</td>' . "\n" .
             '          </tr>' . "\n";
      }
      ?>

    </table>

    <table class="table table-borderless table-sm">

      <?php
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        echo $order_total_modules->output();
      }
      ?>

    </table>

    <?php
    if (is_array($payment_modules->modules)) {
      if ($confirmation = $payment_modules->confirmation()) {
        ?>

        <div class="row">
          <h2><?php echo HEADING_PAYMENT_INFORMATION; ?></h2>

          <div class="mb-3">
            <table>
              <tr>
                <td colspan="4" class="col-lg-6"><?php echo $confirmation['title']; ?></td>
              </tr>

              <?php
              if (isset($confirmation['fields'])) {
                for ($i = 0, $n = sizeof($confirmation['fields']); $i < $n; $i++) {
                  ?>

                  <tr>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main"><?php echo $confirmation['fields'][$i]['title']; ?></td>
                    <td><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                    <td class="main"><?php echo $confirmation['fields'][$i]['field']; ?></td>
                  </tr>

                  <?php
                }
              }
              ?>

            </table>
          </div>
        </div>

        <?php
      }
    }
    ?>

    <?php
    if (!empty($order->info['comments'])) {
      ?>

      <h2><?php echo HEADING_ORDER_COMMENTS . ' <a href="' . tep_href_link('checkout_payment.php') . '"><span class="h6">(' . TEXT_EDIT . ')</span></a>'; ?></h2>

      <div class="mb-3">
        <?php echo nl2br(tep_output_string_protected($order->info['comments'])) . tep_draw_hidden_field('comments', $order->info['comments']); ?>
      </div>

      <?php
    }
    ?>

    <div class="text-end">

      <?php
      if (is_array($payment_modules->modules)) {
        echo $payment_modules->process_button();
      }

      echo tep_draw_button(sprintf(IMAGE_BUTTON_PAY_TOTAL_NOW, $currencies->format($order->info['total'], true, $order->info['currency'], $order->info['currency_value'])), null, null, 'btn-primary', array('params' => 'data-button="payNow"'));
      ?>

    </div>

  </div>

  <script>
    document.forms['checkout_confirmation'].addEventListener('submit', function () {
      const buttonPayNow = document.querySelector('button[data-button="payNow"]');

      buttonPayNow.innerHTML = '<?php echo addslashes(IMAGE_BUTTON_PAY_TOTAL_PROCESSING); ?>';
      buttonPayNow.disabled = true;
    });
  </script>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');