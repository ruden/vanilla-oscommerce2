<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class hm_currencies {
  public $code;
  public $group = 'header';
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_HEADER_CURRENCIES_TITLE;
    $this->description = MODULE_HEADER_CURRENCIES_DESCRIPTION;

    if (defined('MODULE_HEADER_CURRENCIES_STATUS')) {
      $this->sort_order = MODULE_HEADER_CURRENCIES_SORT_ORDER;
      $this->enabled = (MODULE_HEADER_CURRENCIES_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $PHP_SELF, $currencies, $request_type, $currency, $oscTemplate;

    $currencies_array = array();

    foreach ($currencies->currencies as $key => $value) {
      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
    }

    $hidden_get_variables = '';
    foreach ($_GET as $key => $value) {
      if (is_string($value) && ($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y')) {
        $hidden_get_variables .= tep_draw_hidden_field($key, $value);
      }
    }

    ob_start();
    include('includes/modules/' . $this->group . '/templates/currencies.php');

    $oscTemplate->addBlock(ob_get_clean(), 'header_top');
  }

  public function isEnabled() {
    global $PHP_SELF, $currencies;

    if (substr(basename($PHP_SELF), 0, 8) != 'checkout' && (isset($currencies) && is_object($currencies) && (count($currencies->currencies) > 1))) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_HEADER_CURRENCIES_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_HEADER_CURRENCIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_HEADER_CURRENCIES_SORT_ORDER', '10', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_HEADER_CURRENCIES_STATUS', 'MODULE_HEADER_CURRENCIES_SORT_ORDER');
  }
}
