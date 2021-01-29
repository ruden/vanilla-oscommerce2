<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

function do_magic_quotes_gpc(&$ar) {
  if (!is_array($ar)) return false;

  foreach ($ar as $key => $value) {
    if (is_array($ar[$key])) {
      do_magic_quotes_gpc($ar[$key]);
    } else {
      $ar[$key] = addslashes($value);
    }
  }
  reset($ar);
}

$HTTP_GET_VARS =& $_GET;
$HTTP_POST_VARS =& $_POST;
$HTTP_COOKIE_VARS =& $_COOKIE;
$HTTP_SESSION_VARS =& $_SESSION;
$HTTP_POST_FILES =& $_FILES;
$HTTP_SERVER_VARS =& $_SERVER;

// force magic_quotes_gpc
do_magic_quotes_gpc($_GET);
do_magic_quotes_gpc($_POST);
do_magic_quotes_gpc($_COOKIE);

// set default timezone if none exists (PHP 5.3 throws an E_WARNING)
if (!empty(ini_get('date.timezone')) && function_exists('date_default_timezone_set')) {
  date_default_timezone_set(@date_default_timezone_get());
}
