<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

$content = 'configure.php';

$modules = $OSCOM_Braintree->getModules();
$modules[] = 'G';

if (!$OSCOM_Braintree->isInstalled('CC')) {
  $OSCOM_Braintree->install('CC');
}

$default_module = 'G';

foreach ($modules as $m) {
  if ($OSCOM_Braintree->isInstalled($m)) {
    $default_module = $m;
    break;
  }
}

$current_module = (isset($_GET['module']) && in_array($_GET['module'], $modules)) ? $_GET['module'] : $default_module;

if (!defined('OSCOM_APP_PAYPAL_BRAINTREE_VERIFY_SSL')) {
  $OSCOM_Braintree->saveParameter('OSCOM_APP_PAYPAL_BRAINTREE_VERIFY_SSL', '1');
}

if (!defined('OSCOM_APP_PAYPAL_BRAINTREE_PROXY')) {
  $OSCOM_Braintree->saveParameter('OSCOM_APP_PAYPAL_BRAINTREE_PROXY', '');
}
