<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

$btUpdateLogResult = array('rpcStatus' => -1);

if (isset($_GET['v']) && is_numeric($_GET['v']) && file_exists(DIR_FS_CATALOG . 'includes/apps/braintree/work/update_log-' . basename($_GET['v']) . '.php')) {
  $btUpdateLogResult['rpcStatus'] = 1;
  $btUpdateLogResult['log'] = file_get_contents(DIR_FS_CATALOG . 'includes/apps/braintree/work/update_log-' . basename($_GET['v']) . '.php');
}

echo json_encode($btUpdateLogResult);

exit;
