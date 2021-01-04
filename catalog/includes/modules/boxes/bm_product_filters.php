<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class bm_product_filters {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_BOXES_PRODUCT_FILTERS_TITLE;
    $this->description = MODULE_BOXES_PRODUCT_FILTERS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_BOXES_PRODUCT_FILTERS_SORT_ORDER;
      $this->enabled = (MODULE_BOXES_PRODUCT_FILTERS_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $language, $cPath;

    if (defined('MODULE_PRODUCT_FILTERS_INSTALLED') && !empty(MODULE_PRODUCT_FILTERS_INSTALLED)) {
      $sbm_array = explode(';', MODULE_PRODUCT_FILTERS_INSTALLED);

      $product_filters = array();

      foreach ($sbm_array as $sbm) {
        $class = substr($sbm, 0, strrpos($sbm, '.'));

        if (!class_exists($class)) {
          include('includes/languages/' . $language . '/modules/product_filters/' . $sbm);
          include('includes/modules/product_filters/' . $class . '.php');
        }

        $sb = new $class();

        if ($sb->isEnabled()) {
          $product_filters[] = $sb->getOutput();
        }
      }
    }

    ob_start();
    include('includes/modules/' . $this->group . '/templates/product_filters.php');

    $oscTemplate->addBlock(ob_get_clean(), 'boxes_column_left');
  }

  public function isEnabled() {
    global $cPath_array;

    if (isset($cPath_array) && !empty($cPath_array)) {
      return $this->enabled;
    }

    return null;
  }

  public function check() {
    return defined('MODULE_BOXES_PRODUCT_FILTERS_STATUS');
  }

  public function install() {
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_BOXES_PRODUCT_FILTERS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_PRODUCT_FILTERS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_BOXES_PRODUCT_FILTERS_STATUS', 'MODULE_BOXES_PRODUCT_FILTERS_SORT_ORDER');
  }
}
