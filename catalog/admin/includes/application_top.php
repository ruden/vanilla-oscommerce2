<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

// Start the clock for the page parse time log
  define('PAGE_PARSE_START_TIME', microtime());

// Set the level of error reporting
  error_reporting(E_ALL & ~E_NOTICE);

  if (defined('E_DEPRECATED')) {
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
  }

// load server configuration parameters
  if (file_exists('includes/local/configure.php')) { // for developers
    include('includes/local/configure.php');
  } else {
    include('includes/configure.php');
  }

// Define the project version --- obsolete, now retrieved with tep_get_version()
  define('PROJECT_VERSION', 'osCommerce Online Merchant v2.3.5');

// some code to solve compatibility issues
  require(DIR_FS_CATALOG . 'includes/functions/compatibility.php');

// set the type of request (secure or not)
  $request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
  $req = parse_url($_SERVER['SCRIPT_NAME']);
  $PHP_SELF = substr($req['path'], ($request_type == 'SSL') ? strlen(DIR_WS_HTTPS_ADMIN) : strlen(DIR_WS_ADMIN));

// Used in the "Backup Manager" to compress backups
  define('LOCAL_EXE_GZIP', '/bin/gzip');
  define('LOCAL_EXE_GUNZIP', '/bin/gunzip');
  define('LOCAL_EXE_ZIP', '/usr/bin/zip');
  define('LOCAL_EXE_UNZIP', '/usr/bin/unzip');

// include the list of project filenames
  require('includes/filenames.php');

// include the list of project database tables
  require('includes/database_tables.php');

// Define how do we update currency exchange rates
// Possible values are 'oanda' 'xe' or ''
  define('CURRENCY_SERVER_PRIMARY', 'ecb_s1');
  define('CURRENCY_SERVER_BACKUP', 'ecb_s2');

// include the database functions
  require('includes/functions/database.php');

// make a connection to the database... now
  tep_db_connect() or die('Unable to connect to database server!');

// set application wide parameters
  $configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from configuration");
  while ($configuration = tep_db_fetch_array($configuration_query)) {
    define($configuration['cfgKey'], $configuration['cfgValue']);
  }

  require(DIR_FS_CATALOG . 'includes/vendor/autoload.php');

// define our general functions used application-wide
  require('includes/functions/general.php');
  require('includes/functions/html_output.php');

// initialize the logger class
  require('includes/classes/logger.php');

// include shopping cart class
  require('includes/classes/shopping_cart.php');

// define how the session functions will be used
  require(DIR_FS_CATALOG . 'includes/functions/sessions.php');

// set the cookie domain
  $cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
  $cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// set the session name and save path
  tep_session_name('osCAdminID');
  tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
  session_set_cookie_params(0, $cookie_path, $cookie_domain, ($request_type == 'SSL'));

  @ini_set('session.use_only_cookies', (SESSION_FORCE_COOKIE_USE == 'True') ? 1 : 0);

  if (version_compare(PHP_VERSION, '7.3.0', '>=')) {
    @ini_set('session.cookie_samesite', 'Lax');
  }

// lets start our session
  tep_session_start();

  // force register_globals
  extract($_SESSION, EXTR_OVERWRITE+EXTR_REFS);

// set the language
  if (!isset($_SESSION['language']) || isset($_GET['language'])) {
    if (!isset($_SESSION['language'])) {
      tep_session_register('language');
      tep_session_register('languages_id');
    }

    include(DIR_FS_CATALOG . 'includes/classes/language.php');
    $lng = new language();

    if (isset($_GET['language']) && !empty($_GET['language'])) {
      $lng->set_language($_GET['language']);
    } else {
      $lng->get_browser_language();
    }

    $language = $lng->language['directory'];
    $languages_id = $lng->language['id'];
  }

// redirect to login page if administrator is not yet logged in
  if (!isset($_SESSION['admin'])) {
    $redirect = false;

    $current_page = $PHP_SELF;

// if the first page request is to the login page, set the current page to the index page
// so the redirection on a successful login is not made to the login page again
    if ( ($current_page == 'login.php') && !isset($_SESSION['redirect_origin']) ) {
      $current_page = 'index.php';
      $_GET = array();
    }

    if ($current_page != 'login.php') {
      if (!isset($_SESSION['redirect_origin'])) {
        tep_session_register('redirect_origin');

        $redirect_origin = array('page' => $current_page,
                                 'get' => $_GET);
      }

// try to automatically login with the HTTP Authentication values if it exists
      if (!isset($_SESSION['auth_ignore'])) {
        if (isset($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']) && !empty($_SERVER['PHP_AUTH_PW'])) {
          $redirect_origin['auth_user'] = $_SERVER['PHP_AUTH_USER'];
          $redirect_origin['auth_pw'] = $_SERVER['PHP_AUTH_PW'];
        }
      }

      $redirect = true;
    }

    if (!isset($login_request) || isset($_GET['login_request']) || isset($_POST['login_request']) || isset($_COOKIE['login_request']) || isset($_SESSION['login_request']) || isset($_FILES['login_request']) || isset($_SERVER['login_request'])) {
      $redirect = true;
    }

    if ($redirect == true) {
      tep_redirect(tep_href_link('login.php', (isset($redirect_origin['auth_user']) ? 'action=process' : '')));
    }

    unset($redirect);
  }

// include the language translations
  $_system_locale_numeric = setlocale(LC_NUMERIC, 0);
  require('includes/languages/' . $language . '.php');
  setlocale(LC_NUMERIC, $_system_locale_numeric); // Prevent LC_ALL from setting LC_NUMERIC to a locale with 1,0 float/decimal values instead of 1.0 (see bug #634)

  $current_page = basename($PHP_SELF);
  if (file_exists('includes/languages/' . $language . '/' . $current_page)) {
    include('includes/languages/' . $language . '/' . $current_page);
  }

// define our localization functions
  require('includes/functions/localization.php');

// Include validation functions (right now only email address)
  require(DIR_FS_CATALOG . 'includes/functions/validations.php');

// setup our boxes
  require('includes/classes/table_block.php');
  require('includes/classes/box.php');

// initialize the message stack for output messages
  require('includes/classes/message_stack.php');
  $messageStack = new messageStack;

// split-page-results
  require('includes/classes/split_page_results.php');

// entry/item info classes
  require('includes/classes/object_info.php');

// email classes
//  require(DIR_FS_CATALOG . 'includes/classes/mime.php');
//  require(DIR_FS_CATALOG . 'includes/classes/email.php');

// file uploading class
  require('includes/classes/upload.php');

// action recorder
  require('includes/classes/action_recorder.php');

// calculate category path
  if (isset($_GET['cPath'])) {
    $cPath = $_GET['cPath'];
  } else {
    $cPath = '';
  }

  if (!empty($cPath)) {
    $cPath_array = tep_parse_category_path($cPath);
    $cPath = implode('_', $cPath_array);
    $current_category_id = $cPath_array[(sizeof($cPath_array)-1)];
  } else {
    $current_category_id = 0;
  }

// initialize configuration modules
  require('includes/classes/cfg_modules.php');
  $cfgModules = new cfg_modules();

// the following cache blocks are used in the Tools->Cache section
// ('language' in the filename is automatically replaced by available languages)
  $cache_blocks = array(array('title' => TEXT_CACHE_CATEGORIES, 'code' => 'categories', 'file' => 'categories_box-language.cache', 'multiple' => true));

  require(DIR_FS_CATALOG . 'includes/classes/hooks.php');
  $OSCOM_Hooks = new hooks('admin');
  $OSCOM_Hooks->register(basename($PHP_SELF, '.php'));
