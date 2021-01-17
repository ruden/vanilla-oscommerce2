<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class pf_manufacturers {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;
  public $mid = array();

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_PRODUCT_FILTERS_MANUFACTURERS_TITLE;
    $this->description = MODULE_PRODUCT_FILTERS_MANUFACTURERS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_PRODUCT_FILTERS_MANUFACTURERS_SORT_ORDER;
      $this->enabled = (MODULE_PRODUCT_FILTERS_MANUFACTURERS_STATUS == 'True');
    }

    $this->mid = (isset($_GET['mid']) && !empty($_GET['mid']) ? tep_db_prepare_input($_GET['mid']) : null);
  }

  public function getOutput() {
    global $current_category_id, $languages_id, $cPath;

    $filterlist_query = tep_db_query("select distinct m.* from products p, products_to_categories p2c, manufacturers m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name");

    if (tep_db_num_rows($filterlist_query) > 0) {
      ob_start();
      include('includes/modules/' . $this->group . '/templates/manufacturers.php');

      return ob_get_clean();
    }

    return null;
  }

  public function where() {
    if (!empty($this->mid) && is_array($this->mid)) {
      return " and m.manufacturers_id in ('" . implode("', '", $this->mid) . "') ";
    }

    return null;
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_PRODUCT_FILTERS_MANUFACTURERS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_PRODUCT_FILTERS_MANUFACTURERS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_PRODUCT_FILTERS_MANUFACTURERS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_PRODUCT_FILTERS_MANUFACTURERS_STATUS', 'MODULE_PRODUCT_FILTERS_MANUFACTURERS_SORT_ORDER');
  }
}
