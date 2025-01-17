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

  if (!isset($_SESSION['payment']) || (($payment != 'sage_pay_direct') && ($payment != 'sage_pay_server')) || (($payment == 'sage_pay_server') && !isset($_SESSION['sage_pay_server_nexturl']))) {
    tep_redirect(tep_href_link('checkout_payment.php'));
  }

// load the selected payment module
  require('includes/classes/payment.php');
  $payment_modules = new payment($payment);

  require('includes/classes/order.php');
  $order = new order;

  $payment_modules->update_status();

  if ( ( is_array($payment_modules->modules) && (sizeof($payment_modules->modules) > 1) && !is_object($$payment) ) || (is_object($$payment) && ($$payment->enabled == false)) ) {
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
    for ($i=0, $n=sizeof($order->products); $i<$n; $i++) {
      if (tep_check_stock($order->products[$i]['id'], $order->products[$i]['qty'])) {
        $any_out_of_stock = true;
      }
    }
    // Out of Stock
    if ( (STOCK_ALLOW_CHECKOUT != 'true') && ($any_out_of_stock == true) ) {
      tep_redirect(tep_href_link('shopping_cart.php'));
    }
  }

  require('includes/languages/' . $language . '/checkout_confirmation.php');

  $breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('checkout_shipping.php'));
  $breadcrumb->add(NAVBAR_TITLE_2);

  if ($payment == 'sage_pay_direct') {
    $iframe_url = tep_href_link('ext/modules/payment/sage_pay/direct_3dauth.php');
  } else {
    $iframe_url = $sage_pay_server_nexturl;
  }

  if ( !file_exists(DIR_FS_CATALOG . 'includes/template_top.php') ) {
    tep_redirect($iframe_url);
  }

  include('includes/functions/template_top.php');
?>

    <iframe src="<?php echo $iframe_url; ?>" width="100%" height="600" frameborder="0">
      <p>Your browser does not support iframes.</p>
    </iframe>

<?php
  include('includes/functions/template_bottom.php');
  require('includes/application_bottom.php');
?>
