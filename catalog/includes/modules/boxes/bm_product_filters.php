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
    global $oscTemplate, $language, $languages_id, $current_category_id, $cPath;

    if (defined('MODULE_PRODUCT_FILTERS_INSTALLED') && !empty(MODULE_PRODUCT_FILTERS_INSTALLED)) {
      $pfm_array = explode(';', MODULE_PRODUCT_FILTERS_INSTALLED);

      $product_filters = array();
      $select_str = $from_str = $where_str = '';

      foreach ($pfm_array as $pfm) {
        $class = substr($pfm, 0, strrpos($pfm, '.'));

        if (!class_exists($class)) {
          include('includes/languages/' . $language . '/modules/product_filters/' . $pfm);
          include('includes/modules/product_filters/' . $class . '.php');
        }

        $pf = new $class();

        if ($pf->isEnabled()) {
          $product_filters[] = $pf->getOutput();

          if (method_exists($pf, 'select')) {
            $select_str .= $pf->select();
          }

          if (method_exists($pf, 'from')) {
            $from_str .= $pf->from();
          }

          if (method_exists($pf, 'where')) {
            $where_str .= $pf->where();
          }
        }
      }

      if (!empty($pfm_array)) {
        if (!empty($select_str) || !empty($from_str) || !empty($where_str)) {
          $oscTemplate->_data['index']['listing_sql'] =  "select p.*, pd.*, m.*, if(s.status, s.specials_new_products_price, null) as specials_new_products_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price $select_str from products_description pd, products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id left join specials s on p.products_id = s.products_id $from_str, products_to_categories p2c where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "' $where_str group by p.products_id"; //
        }

        ob_start();
        include('includes/modules/' . $this->group . '/templates/product_filters.php');

        $oscTemplate->addBlock(ob_get_clean(), 'boxes_column_left');
      }
    }
  }

  public function isEnabled() {
    global $category_depth, $current_category_id;

    if ($category_depth == 'products') {
      if (tep_count_products_in_category($current_category_id) > 1) {
        return $this->enabled;
      }
    }

    return false;
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
