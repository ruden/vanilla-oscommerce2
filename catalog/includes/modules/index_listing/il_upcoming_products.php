<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class il_upcoming_products {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_TITLE;
      $this->description = MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_DESCRIPTION;

      if ( defined('MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_STATUS') ) {
        $this->sort_order = MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_SORT_ORDER;
        $this->enabled = (MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_STATUS == 'True');
      }
    }

    public function execute() {
      global $oscTemplate, $languages_id;

      $expected_products_query = tep_db_query("select p.products_id, pd.products_name, products_date_available as date_expected from products p, products_description pd where to_days(products_date_available) >= to_days(now()) and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by " . tep_db_input(EXPECTED_PRODUCTS_FIELD) . " " . tep_db_input(EXPECTED_PRODUCTS_SORT) . " limit " . (int)MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_MAX_DISPLAY_PRODUCTS);

      if (tep_db_num_rows($expected_products_query) > 0) {
        $expected_products_array = array();

        while ($expected_products = tep_db_fetch_array($expected_products_query)) {
          $expected_products_array[] = $expected_products;
        }

        ob_start();
        include('includes/modules/' . $this->group . '/templates/upcoming_products.php');

        $oscTemplate->addBlock(ob_get_clean(), $this->group);
      }
    }

    public function isEnabled() {
      global $category_depth;

      if (in_array($category_depth, explode(', ', strtolower(MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_CATEGORY_DEPTH)))) {
        return $this->enabled;
      }

      return false;
    }

    public function check() {
      return defined('MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_STATUS');
    }

    public function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Placement in index', 'MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_CATEGORY_DEPTH', 'Main', 'Should the module be shown in the main page, category or listing products page?', '6', '0', 'tep_cfg_select_multioption(array(\'Main\', \'Categories\', \'Listing\'), ', now())");
      tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Max number products to display', 'MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_MAX_DISPLAY_PRODUCTS', '6', 'Maximum number of recently viewed products to display in a index page and category.', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    public function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      return array('MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_STATUS', 'MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_CATEGORY_DEPTH', 'MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_MAX_DISPLAY_PRODUCTS', 'MODULE_INDEX_LISTING_UPCOMING_PRODUCTS_SORT_ORDER');
    }
  }
