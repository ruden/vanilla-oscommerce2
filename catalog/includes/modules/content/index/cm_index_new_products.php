<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cm_index_new_products {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_INDEX_NEW_PRODUCTS_TITLE;
    $this->description = MODULE_CONTENT_INDEX_NEW_PRODUCTS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_INDEX_NEW_PRODUCTS_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_INDEX_NEW_PRODUCTS_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $currencies, $languages_id, $current_category_id;

    if (!isset($current_category_id) || $current_category_id == '0') {
      $new_products_query = tep_db_query("select p.*, pd.*, if(s.status, s.specials_new_products_price, p.products_price) as products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit " . (int)MODULE_CONTENT_INDEX_NEW_PRODUCTS_MAX_DISPLAY_PRODUCTS);
    } else {
      $new_products_query = tep_db_query("select distinct p.*, pd.*, if(s.status, s.specials_new_products_price, p.products_price) as products_price from products p left join specials s on p.products_id = s.products_id, products_description pd, products_to_categories p2c, categories c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$current_category_id . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' order by p.products_date_added desc limit " . (int)MODULE_CONTENT_INDEX_NEW_PRODUCTS_MAX_DISPLAY_PRODUCTS);
    }

    if (tep_db_num_rows($new_products_query) > 0) {
      $new_products_array = array();

      while ($new_products = tep_db_fetch_array($new_products_query)) {
        $new_products['products_price'] = $currencies->display_price($new_products['products_price'], tep_get_tax_rate($new_products['products_tax_class_id']));

        $new_products_array[] = $new_products;
      }

      ob_start();
      include('includes/modules/content/' . $this->group . '/templates/new_products.php');

      $oscTemplate->addContent(ob_get_clean(), $this->group);
    }
  }

  public function isEnabled() {
    global $category_depth;

    if ($category_depth == 'top') {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_CONTENT_INDEX_NEW_PRODUCTS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_CONTENT_INDEX_NEW_PRODUCTS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Max number products to display', 'MODULE_CONTENT_INDEX_NEW_PRODUCTS_MAX_DISPLAY_PRODUCTS', '6', 'Maximum number of recently viewed products to display in a index page and category.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_INDEX_NEW_PRODUCTS_SORT_ORDER', '200', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_INDEX_NEW_PRODUCTS_STATUS', 'MODULE_CONTENT_INDEX_NEW_PRODUCTS_MAX_DISPLAY_PRODUCTS', 'MODULE_CONTENT_INDEX_NEW_PRODUCTS_SORT_ORDER');
  }
}
