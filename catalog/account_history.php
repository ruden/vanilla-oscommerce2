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

require('includes/languages/' . $language . '/account_history.php');

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('account_history.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="mb-5">

    <?php
    $orders_total = tep_count_customer_orders();

    if ($orders_total > 0) {
      $history_query_raw = "select o.orders_id, o.date_purchased, o.delivery_name, o.billing_name, ot.text as order_total, s.orders_status_name from orders o, orders_total ot, orders_status s where o.customers_id = '" . (int)$customer_id . "' and o.orders_id = ot.orders_id and ot.class = 'ot_total' and o.orders_status = s.orders_status_id and s.language_id = '" . (int)$languages_id . "' and s.public_flag = '1' order by orders_id DESC";
      $history_split = new splitPageResults($history_query_raw, (int)MAX_DISPLAY_SEARCH_RESULTS);
      $history_query = tep_db_query($history_split->sql_query);
      ?>

      <div class="table-responsive-md">
        <table class="table table-hover">
          <thead>
          <tr>
            <th><?php echo TEXT_ORDER_NUMBER; ?></th>
            <th><?php echo TEXT_ORDER_STATUS; ?></th>
            <th><?php echo TEXT_ORDER_DATE; ?></th>
            <th><?php echo TEXT_ORDER_SHIPPED_TO; ?></th>
            <th><?php echo TEXT_ORDER_PRODUCTS; ?></th>
            <th colspan="2"><?php echo TEXT_ORDER_COST; ?></th>
          </tr>
          </thead>
          <tbody>

          <?php
          while ($history = tep_db_fetch_array($history_query)) {
            $products_query = tep_db_query("select count(*) as count from orders_products where orders_id = '" . (int)$history['orders_id'] . "'");
            $products = tep_db_fetch_array($products_query);

            if (!empty($history['delivery_name'])) {
              $order_type = TEXT_ORDER_SHIPPED_TO;
              $order_name = $history['delivery_name'];
            } else {
              $order_type = TEXT_ORDER_BILLED_TO;
              $order_name = $history['billing_name'];
            }
            ?>

            <tr>
              <td><?php echo $history['orders_id']; ?></td>
              <td><?php echo $history['orders_status_name']; ?></td>
              <td><?php echo tep_date_short($history['date_purchased']); ?></td>
              <td><?php echo tep_output_string_protected($order_name); ?></td>
              <td><?php echo $products['count']; ?></td>
              <td><?php echo strip_tags($history['order_total']); ?></td>
              <td class="text-end"><?php echo tep_draw_button(SMALL_IMAGE_BUTTON_VIEW, 'document', tep_href_link('account_history_info.php', (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'order_id=' . $history['orders_id']), 'btn-primary btn-sm'); ?></td>
            </tr>

            <?php
          }
          ?>

          </tbody>
        </table>
      </div>

      <div class="row align-items-center my-3">
        <div class="col-md d-none d-md-block">
          <?php echo $history_split->display_count(TEXT_DISPLAY_NUMBER_OF_ORDERS); ?>
        </div>
        <div class="col-md">
          <?php echo $history_split->display_links(MAX_DISPLAY_PAGE_LINKS, tep_get_all_get_params(array('page', 'info', 'x', 'y'))); ?>
        </div>
      </div>

      <?php
    } else {
      ?>

      <p><?php echo TEXT_NO_PURCHASES; ?></p>

      <?php
    }
    ?>

    <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php'), 'btn-light'); ?>

  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');
