<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class ht_manufacturer_title {
  public $code = 'ht_manufacturer_title';
  public $group = 'header_tags';
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->title = MODULE_HEADER_TAGS_MANUFACTURER_TITLE_TITLE;
    $this->description = MODULE_HEADER_TAGS_MANUFACTURER_TITLE_DESCRIPTION;

    if (defined('MODULE_HEADER_TAGS_MANUFACTURER_TITLE_STATUS')) {
      $this->sort_order = MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SORT_ORDER;
      $this->enabled = (MODULE_HEADER_TAGS_MANUFACTURER_TITLE_STATUS == 'True');
    }
  }

  public function execute() {
    global $PHP_SELF, $oscTemplate, $manufacturers;

    if (basename($PHP_SELF) == 'manufacturers.php') {
      // $manufacturers is set in application_top.php to add the manufacturer to the breadcrumb
      if (isset($manufacturers['manufacturers_id'])) {
        $oscTemplate->setTitle($manufacturers['manufacturers_name'] . ', ' . $oscTemplate->getTitle());
      }
    }
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_HEADER_TAGS_MANUFACTURER_TITLE_STATUS');
  }

  public function install() {
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Manufacturer Title Module', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_STATUS', 'True', 'Do you want to allow manufacturer titles to be added to the page title?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_HEADER_TAGS_MANUFACTURER_TITLE_STATUS', 'MODULE_HEADER_TAGS_MANUFACTURER_TITLE_SORT_ORDER');
  }
}
