<?php
/*
$Id$

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2020 osCommerce

Released under the GNU General Public License
*/

// start the timer for the page parse time log
define('PAGE_PARSE_START_TIME', microtime());

// set the level of error reporting
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

if (strlen(DB_SERVER) < 1) {
  if (is_dir('install')) {
    header('Location: install/index.php');
  }
}

// define the project version --- obsolete, now retrieved with tep_get_version()
define('PROJECT_VERSION', 'osCommerce Online Merchant v2.3.5');

// some code to solve compatibility issues
require('includes/functions/compatibility.php');

// set the type of request (secure or not)
$request_type = (getenv('HTTPS') == 'on') ? 'SSL' : 'NONSSL';

// set php_self in the local scope
$req = parse_url($_SERVER['SCRIPT_NAME']);
$PHP_SELF = substr($req['path'], ($request_type == 'NONSSL') ? strlen(DIR_WS_HTTP_CATALOG) : strlen(DIR_WS_HTTPS_CATALOG));

if ($request_type == 'NONSSL') {
  define('DIR_WS_CATALOG', DIR_WS_HTTP_CATALOG);
} else {
  define('DIR_WS_CATALOG', DIR_WS_HTTPS_CATALOG);
}

// include the list of project filenames
require('includes/filenames.php');

// include the list of project database tables
require('includes/database_tables.php');

// include the database functions
require('includes/functions/database.php');

// make a connection to the database... now
tep_db_connect() or die('Unable to connect to database server!');

// set the application parameters
$configuration_query = tep_db_query("select configuration_key as cfgKey, configuration_value as cfgValue from configuration");
while ($configuration = tep_db_fetch_array($configuration_query)) {
  define($configuration['cfgKey'], $configuration['cfgValue']);
}

require('includes/vendor/autoload.php');

// if gzip_compression is enabled
if (GZIP_COMPRESSION == 'true' && extension_loaded('zlib') && !headers_sent()) {
  if ((int)ini_get('zlib.output_compression') == 1) {
    ini_set('zlib.output_compression_level', GZIP_LEVEL);
  } else {
    ob_start('ob_gzhandler');
  }
}

// define general functions used application-wide
require('includes/functions/general.php');
require('includes/functions/html_output.php');

// set the cookie domain
$cookie_domain = (($request_type == 'NONSSL') ? HTTP_COOKIE_DOMAIN : HTTPS_COOKIE_DOMAIN);
$cookie_path = (($request_type == 'NONSSL') ? HTTP_COOKIE_PATH : HTTPS_COOKIE_PATH);

// include cache functions if enabled
if (USE_CACHE == 'true') include('includes/functions/cache.php');

// include shopping cart class
require('includes/classes/shopping_cart.php');

require('includes/classes/wishlist.php');

// include navigation history class
require('includes/classes/navigation_history.php');

// define how the session functions will be used
require('includes/functions/sessions.php');

// set the session name and save path
tep_session_name('osCsid');
tep_session_save_path(SESSION_WRITE_DIRECTORY);

// set the session cookie parameters
session_set_cookie_params(0, $cookie_path, $cookie_domain);

@ini_set('session.use_only_cookies', (SESSION_FORCE_COOKIE_USE == 'True') ? 1 : 0);

// set the session ID if it exists
if (SESSION_FORCE_COOKIE_USE == 'False') {
  if (isset($_GET[tep_session_name()]) && (!isset($_COOKIE[tep_session_name()]) || ($_COOKIE[tep_session_name()] != $_GET[tep_session_name()]))) {
    tep_session_id($_GET[tep_session_name()]);
  } elseif (isset($_POST[tep_session_name()]) && (!isset($_COOKIE[tep_session_name()]) || ($_COOKIE[tep_session_name()] != $_POST[tep_session_name()]))) {
    tep_session_id($_POST[tep_session_name()]);
  }
}

// start the session
$session_started = false;
if (SESSION_FORCE_COOKIE_USE == 'True') {
  tep_setcookie('cookie_test', 'please_accept_for_session', time() + 60 * 60 * 24 * 30, $cookie_path, $cookie_domain, ($request_type == 'SSL'));

  if (isset($_COOKIE['cookie_test'])) {
    tep_session_start();
    $session_started = true;
  }
} elseif (SESSION_BLOCK_SPIDERS == 'True') {
  $user_agent = strtolower(getenv('HTTP_USER_AGENT'));
  $spider_flag = false;

  if (tep_not_null($user_agent)) {
    $spiders = file(DIR_WS_INCLUDES . 'spiders.txt');

    for ($i = 0, $n = sizeof($spiders); $i < $n; $i++) {
      if (tep_not_null($spiders[$i])) {
        if (is_integer(strpos($user_agent, trim($spiders[$i])))) {
          $spider_flag = true;
          break;
        }
      }
    }
  }

  if ($spider_flag == false) {
    tep_session_start();
    $session_started = true;
  }
} else {
  tep_session_start();
  $session_started = true;
}

if ($session_started == true) { // force register_globals
  extract($_SESSION, EXTR_OVERWRITE + EXTR_REFS);
}

// initialize a session token
if (!tep_session_is_registered('sessiontoken')) {
  $sessiontoken = md5(tep_rand() . tep_rand() . tep_rand() . tep_rand());
  tep_session_register('sessiontoken');
}

// set SID once, even if empty
$SID = (defined('SID') ? SID : '');

// verify the ssl_session_id if the feature is enabled
if (($request_type == 'SSL') && (SESSION_CHECK_SSL_SESSION_ID == 'True') && (ENABLE_SSL == true) && ($session_started == true)) {
  $ssl_session_id = getenv('SSL_SESSION_ID');
  if (!tep_session_is_registered('SSL_SESSION_ID')) {
    $SESSION_SSL_ID = $ssl_session_id;
    tep_session_register('SESSION_SSL_ID');
  }

  if ($SESSION_SSL_ID != $ssl_session_id) {
    tep_session_destroy();
    tep_redirect(tep_href_link('ssl_check.php'));
  }
}

// verify the browser user agent if the feature is enabled
if (SESSION_CHECK_USER_AGENT == 'True') {
  $http_user_agent = getenv('HTTP_USER_AGENT');
  if (!tep_session_is_registered('SESSION_USER_AGENT')) {
    $SESSION_USER_AGENT = $http_user_agent;
    tep_session_register('SESSION_USER_AGENT');
  }

  if ($SESSION_USER_AGENT != $http_user_agent) {
    tep_session_destroy();
    tep_redirect(tep_href_link('login.php'));
  }
}

// verify the IP address if the feature is enabled
if (SESSION_CHECK_IP_ADDRESS == 'True') {
  $ip_address = tep_get_ip_address();
  if (!tep_session_is_registered('SESSION_IP_ADDRESS')) {
    $SESSION_IP_ADDRESS = $ip_address;
    tep_session_register('SESSION_IP_ADDRESS');
  }

  if ($SESSION_IP_ADDRESS != $ip_address) {
    tep_session_destroy();
    tep_redirect(tep_href_link('login.php'));
  }
}

// create the shopping cart
if (!tep_session_is_registered('cart') || !is_object($cart)) {
  tep_session_register('cart');
  $cart = new shoppingCart;
}

$cart->update_content();

if (!tep_session_is_registered('wishlist') || !is_object($wishlist)) {
  tep_session_register('wishlist');
  $wishlist = new wishList;
}

$wishlist->update_list();
$wishlist->add_products();

// include currencies class and create an instance
require('includes/classes/currencies.php');
$currencies = new currencies();

// include the mail classes
//require('includes/classes/mime.php');
//require('includes/classes/email.php');

// set the language
if (!tep_session_is_registered('language') || isset($_GET['language'])) {
  if (!tep_session_is_registered('language')) {
    tep_session_register('language');
    tep_session_register('languages_id');
  }

  include('includes/classes/language.php');
  $lng = new language();

  if (isset($_GET['language']) && tep_not_null($_GET['language'])) {
    $lng->set_language($_GET['language']);
  } else {
    $lng->get_browser_language();
  }

  $language = $lng->language['directory'];
  $languages_id = $lng->language['id'];
}

// include the language translations
$_system_locale_numeric = setlocale(LC_NUMERIC, 0);
require('includes/languages/' . $language . '.php');
setlocale(LC_NUMERIC, $_system_locale_numeric); // Prevent LC_ALL from setting LC_NUMERIC to a locale with 1,0 float/decimal values instead of 1.0 (see bug #634)

// currency
if (!tep_session_is_registered('currency') || isset($_GET['currency']) || ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && (LANGUAGE_CURRENCY != $currency))) {
  if (!tep_session_is_registered('currency')) tep_session_register('currency');

  if (isset($_GET['currency']) && $currencies->is_set($_GET['currency'])) {
    $currency = $_GET['currency'];
  } else {
    $currency = ((USE_DEFAULT_LANGUAGE_CURRENCY == 'true') && $currencies->is_set(LANGUAGE_CURRENCY)) ? LANGUAGE_CURRENCY : DEFAULT_CURRENCY;
  }
}

// navigation history
if (!tep_session_is_registered('navigation') || !is_object($navigation)) {
  tep_session_register('navigation');
  $navigation = new navigationHistory;
}
$navigation->add_current_page();

// action recorder
include('includes/classes/action_recorder.php');

// Shopping cart actions
if (isset($_GET['action'])) {
// redirect the customer to a friendly cookie-must-be-enabled page if cookies are disabled
  if ($session_started == false) {
    tep_redirect(tep_href_link('cookie_usage.php'));
  }

  if (DISPLAY_CART == 'true') {
    $goto = 'shopping_cart.php';
    $parameters = array('action', 'cPath', 'products_id', 'pid');
  } else {
    $goto = $PHP_SELF;
    if (($_GET['action'] == 'buy_now') || ($_GET['action'] == 'remove_product')) {
      $parameters = array('action', 'pid', 'products_id');
    } else {
      $parameters = array('action', 'pid');
    }
  }

  if (file_exists('includes/actions/' . basename($_GET['action']) . '.php')) {
    include('includes/actions/' . basename($_GET['action']) . '.php');
  }
}

// include the password crypto functions
require('includes/functions/password_funcs.php');

// include validation functions (right now only email address)
require('includes/functions/validations.php');

// split-page-results
require('includes/classes/split_page_results.php');

// auto expire special products
require('includes/functions/specials.php');
tep_expire_specials();

require('includes/classes/osc_template.php');
$oscTemplate = new oscTemplate();

// calculate category path
if (isset($_GET['cPath'])) {
  $cPath = $_GET['cPath'];
} elseif (isset($_GET['products_id'])) {
  $cPath = tep_get_product_path($_GET['products_id']);
} else {
  $cPath = '';
}

if (tep_not_null($cPath)) {
  $cPath_array = tep_parse_category_path($cPath);
  $cPath = implode('_', $cPath_array);
  $current_category_id = $cPath_array[(sizeof($cPath_array) - 1)];
} else {
  $current_category_id = 0;
}

// include the breadcrumb class and start the breadcrumb trail
require('includes/classes/breadcrumb.php');
$breadcrumb = new breadcrumb;

$breadcrumb->add(HEADER_TITLE_TOP, $request_type == 'SSL' ? HTTPS_SERVER : HTTP_SERVER);
$breadcrumb->add(HEADER_TITLE_CATALOG, tep_href_link('index.php'));

// add category names or the manufacturer name to the breadcrumb trail
if (isset($cPath_array)) {
  for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i++) {
    $categories_query = tep_db_query("select c.*, cd.* from categories c left join categories_description cd on (cd.categories_id = c.categories_id and cd.language_id = '" . (int)$languages_id . "') where c.categories_id = '" . (int)$cPath_array[$i] . "' and c.parent_id = '" . ($i > 0 ? (int)$cPath_array[$i - 1] : '0') . "'");
    $categories = tep_db_fetch_array($categories_query);
    if (isset($categories['categories_id'])) {
      $breadcrumb->add($categories['categories_name'], tep_href_link('index.php', 'cPath=' . implode('_', array_slice($cPath_array, 0, ($i + 1)))));
    } else {
// reset
      $cPath_array = [];
      $current_category_id = '-1';
      $breadcrumb->_trail = array_slice($breadcrumb->_trail, 0, 2);

      http_response_code(404);

      break;
    }
  }
}

// initialize the message stack for output messages
require('includes/classes/message_stack.php');
$messageStack = new messageStack;

require(DIR_FS_CATALOG . 'includes/classes/hooks.php');
$OSCOM_Hooks = new hooks('shop');
$OSCOM_Hooks->register(basename($PHP_SELF, '.php'));

require('includes/functions/reviews.php');