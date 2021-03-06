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

if (!isset($_GET['order_id']) || (isset($_GET['order_id']) && !is_numeric($_GET['order_id']))) {
  tep_redirect(tep_href_link('account_history.php'));
}

$customer_info_query = tep_db_query("select o.customers_id from orders o, orders_status s where o.orders_id = '" . (int)$_GET['order_id'] . "' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1'");
$customer_info = tep_db_fetch_array($customer_info_query);
if ($customer_info['customers_id'] != $customer_id) {
  tep_redirect(tep_href_link('account_history.php'));
}

require('includes/languages/' . $language . '/account_history_info.php');

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('account_history.php'));
$breadcrumb->add(sprintf(NAVBAR_TITLE_3, $_GET['order_id']), tep_href_link('account_history_info.php', 'order_id=' . $_GET['order_id']));

require('includes/classes/order.php');
$order = new order($_GET['order_id']);

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="mb-5">
    <h2><?php echo sprintf(HEADING_ORDER_NUMBER, $_GET['order_id']) . ' <span>(' . $order->info['orders_status'] . ')</span>'; ?></h2>

    <p>
      <span class="fw-bold me-1"><?php echo HEADING_ORDER_DATE; ?></span><?php echo tep_date_long($order->info['date_purchased']); ?>
    </p>

    <div class="row">

      <?php
      if ($order->delivery != false) {
        ?>

        <div class="col">
          <div class="fw-bold"><?php echo HEADING_DELIVERY_ADDRESS; ?></div>
          <p><?php echo tep_address_format($order->delivery['format_id'], $order->delivery, 1, ' ', '<br />'); ?></p>

          <?php
          if (!empty($order->info['shipping_method'])) {
            ?>

            <div class="fw-bold"><?php echo HEADING_SHIPPING_METHOD; ?></div>
            <p><?php echo $order->info['shipping_method']; ?></p>

            <?php
          }
          ?>

        </div>

        <?php
      }
      ?>

      <div class="col">
        <div class="fw-bold"><?php echo HEADING_BILLING_ADDRESS; ?></div>
        <p><?php echo tep_address_format($order->billing['format_id'], $order->billing, 1, ' ', '<br />'); ?></p>
        <div class="fw-bold"><?php echo HEADING_PAYMENT_METHOD; ?></div>
        <p><?php echo $order->info['payment_method']; ?></p>
      </div>

    </div>

    <table class="table align-top">
      <thead>
      <tr>

        <?php
        if (sizeof($order->info['tax_groups']) > 1) {
          ?>

          <th colspan="2"><strong><?php echo HEADING_PRODUCTS; ?></strong></th>
          <th class="text-end"><strong><?php echo HEADING_TAX; ?></strong></th>
          <th class="text-end"><strong><?php echo HEADING_TOTAL; ?></strong></th>

          <?php
        } else {
          ?>

          <th colspan="2"><?php echo HEADING_PRODUCTS; ?></th>
          <th class="text-end"><?php echo HEADING_TOTAL; ?></th>

          <?php
        }
        ?>

      </tr>
      </thead>
      <tbody>

      <?php
      for ($i = 0, $n = sizeof($order->products); $i < $n; $i++) {
        echo '<tr>
                <td class="text-end" style="width: 30px;">' . $order->products[$i]['qty'] . '&nbsp;x&nbsp;</td>
                <td>' . $order->products[$i]['name'];

        if ((isset($order->products[$i]['attributes'])) && (sizeof($order->products[$i]['attributes']) > 0)) {
          for ($j = 0, $n2 = sizeof($order->products[$i]['attributes']); $j < $n2; $j++) {
            echo '<br /><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'] . '</i></small>';
          }
        }

        echo '  </td>';

        if (sizeof($order->info['tax_groups']) > 1) {
          echo '  <td class="text-end">' . tep_display_tax_value($order->products[$i]['tax']) . '%</td>';
        }

        echo '  <td class="text-end">' . $currencies->format(tep_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>
            </tr>';
      }
      ?>

      </tbody>
    </table>

    <table class="mb-3">
      <tbody>

      <?php
      for ($i = 0, $n = sizeof($order->totals); $i < $n; $i++) {
        echo '<tr>
                <td class="text-end w-100"><span class="me-2">' . $order->totals[$i]['title'] . '</span></td>
                <td class="text-end">' . $order->totals[$i]['text'] . '</td>
              </tr>';
      }
      ?>

      </tbody>
    </table>

    <h2><?php echo HEADING_ORDER_HISTORY; ?></h2>

    <table class="table table-striped align-top">
      <tbody>

      <?php
      $statuses_query = tep_db_query("select os.orders_status_name, osh.date_added, osh.comments from orders_status os, orders_status_history osh where osh.orders_id = '" . (int)$_GET['order_id'] . "' and osh.orders_status_id = os.orders_status_id and os.language_id = '" . (int)$languages_id . "' and os.public_flag = '1' order by osh.date_added");
      while ($statuses = tep_db_fetch_array($statuses_query)) {
        echo '<tr>
                <td>' . tep_date_short($statuses['date_added']) . '</td>
                <td>' . $statuses['orders_status_name'] . '</td>
                <td class="w-75">' . (empty($statuses['comments']) ? '&nbsp;' : nl2br(tep_output_string_protected($statuses['comments']))) . '</td>
              </tr>';
      }
      ?>

      </tbody>
    </table>

    <?php
    if (DOWNLOAD_ENABLED == 'true') {
      include('includes/modules/downloads.php');
    }
    ?>

    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account_history.php', tep_get_all_get_params(array('order_id'))), 'btn-light'); ?>

  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');