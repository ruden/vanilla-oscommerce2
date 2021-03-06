<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

class fm_cookie_bar {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_FOOTER_COOKIE_BAR_TITLE;
    $this->description = MODULE_FOOTER_COOKIE_BAR_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_FOOTER_COOKIE_BAR_SORT_ORDER;
      $this->enabled = (MODULE_FOOTER_COOKIE_BAR_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $cookie_path;

    $cookie_groups = $this->getCookieGroups();

    ob_start();
    include('includes/modules/' . $this->group . '/templates/cookie_bar.php');

    $oscTemplate->addBlock(ob_get_clean(), 'footer_bottom');
    $oscTemplate->addBlock('<script src="includes/modules/' . $this->group . '/templates/cookie_bar.js"></script>', 'footer_scripts');
  }

  public function isEnabled() {
    global $PHP_SELF;

    if (isset($_COOKIE['cookieAccepted']) && basename($PHP_SELF) != 'cookie_consent.php') {
      return false;
    }

    if ($this->hasCountry()) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_FOOTER_COOKIE_BAR_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_FOOTER_COOKIE_BAR_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', NOW())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Cookie Modules', 'MODULE_FOOTER_COOKIE_BAR_COOKIE_MODULES', 'header_tags/ht_google_analytics.php;header_tags/ht_google_adwords_conversion.php', 'Add modules that use the cookie, semicolon separator', '6', '0', 'tep_cfg_textarea(', NOW())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Display EU Countries', 'MODULE_FOOTER_COOKIE_BAR_DISPLAY_EU_COUNTRIES', 'False', 'Display the cookie consent bar to EU countries only?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', NOW())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_FOOTER_COOKIE_BAR_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', NOW())");
  }

  public function remove() {
    tep_db_query("DELETE FROM configuration WHERE configuration_key IN ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_FOOTER_COOKIE_BAR_STATUS', 'MODULE_FOOTER_COOKIE_BAR_COOKIE_MODULES', 'MODULE_FOOTER_COOKIE_BAR_DISPLAY_EU_COUNTRIES', 'MODULE_FOOTER_COOKIE_BAR_SORT_ORDER');
  }

  public function hasCountry() {
    if (MODULE_FOOTER_COOKIE_BAR_DISPLAY_EU_COUNTRIES == 'False') {
      return true;
    }

    $country = $this->getCountry(tep_get_ip_address());

    return $country === 'XX' || in_array($country, $this->getEUCountries()); // XX - localhost
  }

  public function getCountry($ip) {
    return file_get_contents('http://api.hostip.info/country.php?ip=' . $ip);
  }

  public function getEUCountries() {
    return array('AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GB', 'GR', 'HU', 'HR', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PL', 'PT', 'RO', 'SE', 'SI', 'SK');
  }

  public function getCookieGroups() {
    global $language;

    $groups_array = array();

    if (!empty(MODULE_FOOTER_COOKIE_BAR_COOKIE_MODULES)) {
      $cookie_modules = explode(';', tep_output_string(MODULE_FOOTER_COOKIE_BAR_COOKIE_MODULES));

      foreach ($cookie_modules as $module) {
        $class = substr(basename($module), 0, strrpos(basename($module), '.'));

        if (!class_exists($class)) {
          include('includes/languages/' . $language . '/modules/' . $module);
          include('includes/modules/' . $module);
        }

        $m = new $class();

        if ($m->isEnabled()) {
          $groups_array[] = $m->cookie_group;
        }
      }
    }

    return $groups_array;
  }
}
