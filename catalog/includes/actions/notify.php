<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if (isset($_SESSION['customer_id'])) {
  if (isset($_GET['products_id'])) {
    $notify = $_GET['products_id'];
  } elseif (isset($_GET['notify'])) {
    $notify = $_GET['notify'];
  } elseif (isset($_POST['notify'])) {
    $notify = $_POST['notify'];
  } else {
    tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action', 'notify'))));
  }

  if (!is_array($notify)) {
    $notify = array($notify);
  }

  for ($i = 0, $n = sizeof($notify); $i < $n; $i++) {
    $check_query = tep_db_query("select count(*) as count from products_notifications where products_id = '" . (int)$notify[$i] . "' and customers_id = '" . (int)$customer_id . "'");
    $check = tep_db_fetch_array($check_query);

    if ($check['count'] < 1) {
      tep_db_query("insert into products_notifications (products_id, customers_id, date_added) values ('" . (int)$notify[$i] . "', '" . (int)$customer_id . "', now())");
    }
  }

  tep_redirect(tep_href_link($PHP_SELF, tep_get_all_get_params(array('action', 'notify'))));
} else {
  $_SESSION['navigation']->set_snapshot();

  tep_redirect(tep_href_link('login.php'));
}