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
require('includes/languages/' . $language . '/account_notifications.php');

$global_query = tep_db_query("select global_product_notifications from customers_info where customers_info_id = '" . (int)$customer_id . "'");
$global = tep_db_fetch_array($global_query);

if (isset($_POST['action']) && ($_POST['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  if (isset($_POST['product_global']) && is_numeric($_POST['product_global'])) {
    $product_global = tep_db_prepare_input($_POST['product_global']);
  } else {
    $product_global = '0';
  }

  if ($product_global != $global['global_product_notifications']) {
    $product_global = (($global['global_product_notifications'] == '1') ? '0' : '1');

    tep_db_query("update customers_info set global_product_notifications = '" . (int)$product_global . "' where customers_info_id = '" . (int)$customer_id . "'");
  } elseif (sizeof($_POST['products']) > 0) {
    $products_parsed = array();
    foreach ($_POST['products'] as $value) {
      if (is_numeric($value)) {
        $products_parsed[] = $value;
      }
    }

    if (sizeof($products_parsed) > 0) {
      $check_query = tep_db_query("select count(*) as total from products_notifications where customers_id = '" . (int)$customer_id . "' and products_id not in (" . implode(',', $products_parsed) . ")");
      $check = tep_db_fetch_array($check_query);

      if ($check['total'] > 0) {
        tep_db_query("delete from products_notifications where customers_id = '" . (int)$customer_id . "' and products_id not in (" . implode(',', $products_parsed) . ")");
      }
    }
  } else {
    $check_query = tep_db_query("select count(*) as total from products_notifications where customers_id = '" . (int)$customer_id . "'");
    $check = tep_db_fetch_array($check_query);

    if ($check['total'] > 0) {
      tep_db_query("delete from products_notifications where customers_id = '" . (int)$customer_id . "'");
    }
  }

  $messageStack->add_session('account', SUCCESS_NOTIFICATIONS_UPDATED, 'success');

  tep_redirect(tep_href_link('account.php'));
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('account_notifications.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php echo tep_draw_form('account_notifications', tep_href_link('account_notifications.php'), 'post', '', true) . tep_draw_hidden_field('action', 'process'); ?>

  <div class="col-lg-6 mb-5">

    <p><?php echo MY_NOTIFICATIONS_DESCRIPTION; ?></p>

    <h2><?php echo GLOBAL_NOTIFICATIONS_TITLE; ?></h2>

    <div class="mb-3">
      <div class="form-check">
        <?php echo tep_draw_checkbox_field('product_global', '1', ($global['global_product_notifications'] == '1' ? true : false), 'id="product_global" class="form-check-input"'); ?>
        <label class="form-check-label fw-bold" for="product_global"><?php echo GLOBAL_NOTIFICATIONS_TITLE; ?></label>
      </div>
    </div>

    <p><?php echo GLOBAL_NOTIFICATIONS_DESCRIPTION; ?></p>

    <?php
    if ($global['global_product_notifications'] != '1') {
      ?>

      <h2><?php echo NOTIFICATIONS_TITLE; ?></h2>

      <?php
      $products_check_query = tep_db_query("select count(*) as total from products_notifications where customers_id = '" . (int)$customer_id . "'");
      $products_check = tep_db_fetch_array($products_check_query);
      if ($products_check['total'] > 0) {
        ?>

        <p><?php echo NOTIFICATIONS_DESCRIPTION; ?></p>

        <div class="mb-3">

          <?php
          $counter = 0;
          $products_query = tep_db_query("select p.products_id, pd.products_name from products p left join products_description pd on (p.products_id = pd.products_id and p.products_status = '1') left join products_notifications pn on (pd.products_id = pn.products_id and pd.language_id = '" . (int)$languages_id . "') where pn.customers_id = '" . (int)$customer_id . "' order by pd.products_name");
          while ($products = tep_db_fetch_array($products_query)) {
            ?>

            <div class="form-check">
              <?php echo tep_draw_checkbox_field('products[' . $counter . ']', $products['products_id'], true, 'id="products_' . $counter . '" class="form-check-input"'); ?>
              <label class="form-check-label" for="products_<?php echo $counter; ?>"><a href="<?php echo tep_href_link('product_info.php', 'products_id=' . $products['products_id']); ?>" target="_blank"><?php echo $products['products_name']; ?></a></label>
            </div>

            <?php
            $counter++;
          }
          ?>

        </div>

        <?php
      } else {
        ?>

        <p><?php echo NOTIFICATIONS_NON_EXISTING; ?></p>

        <?php
      }
    }
    ?>

    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php'), 'btn-light'); ?>
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');