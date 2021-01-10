<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class securityCheckExtended_https {
  var $type = 'warning';

  function __construct() {
    global $language;

    include(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/security_check/extended/https.php');

    $this->title = MODULE_SECURITY_CHECK_EXTENDED_HTTPS_TITLE;
  }

  function pass() {
    return defined('ENABLE_SSL') && ENABLE_SSL === true;
  }

  function getMessage() {
    return MODULE_SECURITY_CHECK_EXTENDED_HTTPS_ERROR;
  }
}