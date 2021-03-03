<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

if (!class_exists('OSCOM_Braintree')) {
  include(DIR_FS_CATALOG . 'includes/apps/braintree/OSCOM_Braintree.php');
}

class braintree_hook_admin_orders_action {
  public $server = 1;

  public function braintree_hook_admin_orders_action() {
    global $OSCOM_Braintree;

    if (!isset($OSCOM_Braintree) || !is_object($OSCOM_Braintree) || (get_class($OSCOM_Braintree) != 'OSCOM_Braintree')) {
      $OSCOM_Braintree = new OSCOM_Braintree();
    }

    $this->_app = $OSCOM_Braintree;

    $this->_app->loadLanguageFile('hooks/admin/orders/action.php');
  }

  public function execute() {
    if (isset($_GET['tabaction'])) {
      $btstatus_query = tep_db_query("select comments from orders_status_history where orders_id = '" . (int)$_GET['oID'] . "' and orders_status_id = '" . (int)OSCOM_APP_PAYPAL_BRAINTREE_TRANSACTIONS_ORDER_STATUS_ID . "' and comments like 'Transaction ID:%' order by date_added limit 1");
      if (tep_db_num_rows($btstatus_query)) {
        $btstatus = tep_db_fetch_array($btstatus_query);

        $bt = array();

        foreach (explode("\n", $btstatus['comments']) as $s) {
          if (!empty($s) && (strpos($s, ':') !== false)) {
            $entry = explode(':', $s, 2);

            $bt[trim($entry[0])] = trim($entry[1]);
          }
        }

        if (isset($bt['Transaction ID'])) {
          $o_query = tep_db_query("select o.orders_id, o.payment_method, o.currency, o.currency_value, ot.value as total from orders o, orders_total ot where o.orders_id = '" . (int)$_GET['oID'] . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total'");
          $o = tep_db_fetch_array($o_query);

          if ((isset($bt['Server']) && ($bt['Server'] !== 'production')) || (strpos($o['payment_method'], 'Sandbox') !== false)) {
            $this->server = 0;
          }

          switch ($_GET['tabaction']) {
            case 'getTransactionDetails':
              $this->getTransactionDetails($bt, $o);
              break;

            case 'doCapture':
              $this->doCapture($bt, $o);
              break;

            case 'doVoid':
              $this->doVoid($bt, $o);
              break;

            case 'refundTransaction':
              $this->refundTransaction($bt, $o);
              break;
          }

          tep_redirect(tep_href_link('orders.php', 'page=' . $_GET['page'] . '&oID=' . $_GET['oID'] . '&action=edit#section_status_history_content'));
        }
      }
    }
  }

  public function getTransactionDetails($comments, $order) {
    global $messageStack;

    $result = null;

    $this->_app->setupCredentials($this->server === 1 ? 'live' : 'sandbox');

    $error = false;

    try {
      $response = Braintree\Transaction::find($comments['Transaction ID']);
    } catch (Exception $e) {
      $error = true;
    }

    if (($error === false) && is_object($response) && (get_class($response) == 'Braintree\\Transaction') && isset($response->id) && ($response->id == $comments['Transaction ID'])) {
      $result = 'Transaction ID: ' . tep_db_prepare_input($response->id) . "\n";

      if (($response->paymentInstrumentType == 'credit_card') && isset($comments['3D Secure'])) {
        if (isset($response->threeDSecureInfo) && is_object($response->threeDSecureInfo)) {
          $result .= '3D Secure: ' . tep_db_prepare_input($response->threeDSecureInfo->status . ' (Liability Shifted: ' . ($response->threeDSecureInfo->liabilityShifted === true ? 'true' : 'false') . ')') . "\n";
        } else {
          $result .= '3D Secure: ** MISSING **' . "\n";
        }
      }

      $result .= 'Payment Status: ' . tep_db_prepare_input($response->status) . "\n" .
                 'Payment Type: ' . tep_db_prepare_input($response->paymentInstrumentType) . "\n";

      if ($this->server === 0) {
        $result .= 'Server: sandbox' . "\n";
      }

      $result .= 'Status History:';

      foreach ($response->statusHistory as $sh) {
        $sh->timestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));

        $result .= "\n" . tep_db_prepare_input('[' . $sh->timestamp->format('Y-m-d H:i:s T') . '] ' . $sh->status . ' ' . $sh->amount . ' ' . $response->currencyIsoCode);
      }
    }

    if (!empty($result)) {
      $sql_data_array = array(
        'orders_id' => (int)$order['orders_id'],
        'orders_status_id' => OSCOM_APP_PAYPAL_BRAINTREE_TRANSACTIONS_ORDER_STATUS_ID,
        'date_added' => 'now()',
        'customer_notified' => '0',
        'comments' => $result
      );

      tep_db_perform('orders_status_history', $sql_data_array);

      $messageStack->add_session($this->_app->getDef('ms_success_getTransactionDetails'), 'success');
    } else {
      $messageStack->add_session($this->_app->getDef('ms_error_getTransactionDetails'), 'error');
    }
  }

  public function doCapture($comments, $order) {
    global $messageStack;

    $capture_value = $this->_app->formatCurrencyRaw($order['total'], $order['currency'], $order['currency_value']);

    if ($this->_app->formatCurrencyRaw($_POST['btCaptureAmount'], $order['currency'], 1) < $capture_value) {
      $capture_value = $this->_app->formatCurrencyRaw($_POST['btCaptureAmount'], $order['currency'], 1);
    }

    $this->_app->setupCredentials($this->server === 1 ? 'live' : 'sandbox');

    $error = false;

    try {
      $response = Braintree\Transaction::submitForSettlement($comments['Transaction ID'], $capture_value);
    } catch (Exception $e) {
      $error = true;
    }

    if (($error === false) && is_object($response) && (get_class($response) == 'Braintree\\Result\\Successful') && ($response->success === true) && (get_class($response->transaction) == 'Braintree\\Transaction') && isset($response->transaction->id) && ($response->transaction->id == $comments['Transaction ID'])) {
      $result = 'Braintree App: Capture (' . $capture_value . ')' . "\n" .
                'Transaction ID: ' . tep_db_prepare_input($response->transaction->id) . "\n" .
                'Payment Status: ' . tep_db_prepare_input($response->transaction->status) . "\n" .
                'Status History:';

      foreach ($response->transaction->statusHistory as $sh) {
        $sh->timestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));

        $result .= "\n" . tep_db_prepare_input('[' . $sh->timestamp->format('Y-m-d H:i:s T') . '] ' . $sh->status . ' ' . $sh->amount . ' ' . $response->transaction->currencyIsoCode);
      }

      $sql_data_array = array(
        'orders_id' => (int)$order['orders_id'],
        'orders_status_id' => OSCOM_APP_PAYPAL_BRAINTREE_TRANSACTIONS_ORDER_STATUS_ID,
        'date_added' => 'now()',
        'customer_notified' => '0',
        'comments' => $result
      );

      tep_db_perform('orders_status_history', $sql_data_array);

      // immediately settle sandbox transactions
      if (strpos($order['payment_method'], 'Sandbox') !== false) {
        $error = false;

        try {
          $response = Braintree\Test_Transaction::settle($comments['Transaction ID']);
        } catch (Exception $e) {
          $error = true;
        }

        if (($error === false) && is_object($response) && (get_class($response) == 'Braintree\\Transaction') && isset($response->id) && ($response->id == $comments['Transaction ID'])) {
          $result = 'Braintree App: Settled (' . tep_db_prepare_input($response->amount) . ')' . "\n" .
                    'Transaction ID: ' . tep_db_prepare_input($response->id) . "\n" .
                    'Payment Status: ' . tep_db_prepare_input($response->status) . "\n" .
                    'Status History:';

          foreach ($response->statusHistory as $sh) {
            $sh->timestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));

            $result .= "\n" . tep_db_prepare_input('[' . $sh->timestamp->format('Y-m-d H:i:s T') . '] ' . $sh->status . ' ' . $sh->amount . ' ' . $response->currencyIsoCode);
          }

          $sql_data_array = array(
            'orders_id' => (int)$order['orders_id'],
            'orders_status_id' => OSCOM_APP_PAYPAL_BRAINTREE_TRANSACTIONS_ORDER_STATUS_ID,
            'date_added' => 'now()',
            'customer_notified' => '0',
            'comments' => $result
          );

          tep_db_perform('orders_status_history', $sql_data_array);
        }
      }

      $messageStack->add_session($this->_app->getDef('ms_success_doCapture'), 'success');
    } else {
      $messageStack->add_session($this->_app->getDef('ms_error_doCapture'), 'error');
    }
  }

  public function doVoid($comments, $order) {
    global $messageStack;

    $this->_app->setupCredentials($this->server === 1 ? 'live' : 'sandbox');

    $error = false;

    try {
      $response = Braintree\Transaction::void($comments['Transaction ID']);
    } catch (Exception $e) {
      $error = true;
    }

    if (($error === false) && is_object($response) && (get_class($response) == 'Braintree\\Result\\Successful') && ($response->success === true) && (get_class($response->transaction) == 'Braintree\\Transaction') && isset($response->transaction->id) && ($response->transaction->id == $comments['Transaction ID'])) {
      $result = 'Braintree App: Void (' . tep_db_prepare_input($response->transaction->amount) . ')' . "\n" .
                'Transaction ID: ' . tep_db_prepare_input($response->transaction->id) . "\n" .
                'Payment Status: ' . tep_db_prepare_input($response->transaction->status) . "\n" .
                'Status History:';

      foreach ($response->transaction->statusHistory as $sh) {
        $sh->timestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));

        $result .= "\n" . tep_db_prepare_input('[' . $sh->timestamp->format('Y-m-d H:i:s T') . '] ' . $sh->status . ' ' . $sh->amount . ' ' . $response->transaction->currencyIsoCode);
      }

      $sql_data_array = array('orders_id' => (int)$order['orders_id'],
                              'orders_status_id' => OSCOM_APP_PAYPAL_BRAINTREE_TRANSACTIONS_ORDER_STATUS_ID,
                              'date_added' => 'now()',
                              'customer_notified' => '0',
                              'comments' => $result);

      tep_db_perform('orders_status_history', $sql_data_array);

      $messageStack->add_session($this->_app->getDef('ms_success_doVoid'), 'success');
    } else {
      $messageStack->add($this->_app->getDef('ms_error_doVoid'), 'error');
    }
  }

  public function refundTransaction($comments, $order) {
    global $messageStack;

    $refund_value = (isset($_POST['btRefundAmount']) && !empty($_POST['btRefundAmount'])) ? $this->_app->formatCurrencyRaw($_POST['btRefundAmount'], $order['currency'], 1) : null;

    $this->_app->setupCredentials($this->server === 1 ? 'live' : 'sandbox');

    $error = false;

    try {
      $response = Braintree\Transaction::refund($comments['Transaction ID'], $refund_value);
    } catch (Exception $e) {
      $error = true;
    }

    if (($error === false) && is_object($response) && (get_class($response) == 'Braintree\\Result\\Successful') && ($response->success === true) && (get_class($response->transaction) == 'Braintree\\Transaction') && isset($response->transaction->refundedTransactionId) && ($response->transaction->refundedTransactionId == $comments['Transaction ID'])) {
      $result = 'Braintree App: Refund (' . tep_db_prepare_input($response->transaction->amount) . ')' . "\n" .
                'Credit Transaction ID: ' . tep_db_prepare_input($response->transaction->id) . "\n" .
                'Transaction ID: ' . tep_db_prepare_input($response->transaction->refundedTransactionId) . "\n" .
                'Payment Status: ' . tep_db_prepare_input($response->transaction->status) . "\n" .
                'Status History:';

      foreach ($response->transaction->statusHistory as $sh) {
        $sh->timestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));

        $result .= "\n" . tep_db_prepare_input('[' . $sh->timestamp->format('Y-m-d H:i:s T') . '] ' . $sh->status . ' ' . $sh->amount . ' ' . $response->transaction->currencyIsoCode);
      }

      $sql_data_array = array('orders_id' => (int)$order['orders_id'],
                              'orders_status_id' => OSCOM_APP_PAYPAL_BRAINTREE_TRANSACTIONS_ORDER_STATUS_ID,
                              'date_added' => 'now()',
                              'customer_notified' => '0',
                              'comments' => $result);

      tep_db_perform('orders_status_history', $sql_data_array);

      $messageStack->add_session($this->_app->getDef('ms_success_doRefund', array(
        'refund_amount' => tep_db_prepare_input($response->transaction->amount)
      )), 'success');
    } else {
      $messageStack->add_session($this->_app->getDef('ms_error_doRefund', array(
        'refund_amount' => tep_db_prepare_input($response->transaction->amount)
      )), 'error');
    }
  }
}
