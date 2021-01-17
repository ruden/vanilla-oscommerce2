<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cm_index_special_products {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_TITLE;
    $this->description = MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_DESCRIPTION;

    if (defined('MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_STATUS')) {
      $this->sort_order = MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $currencies, $languages_id;

    if (!isset($special_products_category_id) || $special_products_category_id == '0') {
      $special_products_query = tep_db_query("select p.*, pd.*, if(s.status, s.specials_new_products_price, p.products_price) as products_special_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by p.products_date_added desc limit " . (int)MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_MAX_DISPLAY_PRODUCTS);
    } else {
      $special_products_query = tep_db_query("select distinct p.*, pd.*, if(s.status, s.specials_new_products_price, p.products_price) as products_special_price from products p left join specials s on p.products_id = s.products_id, products_description pd, products_to_categories p2c, categories c where p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and c.parent_id = '" . (int)$special_products_category_id . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by p.products_date_added desc limit " . (int)MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_MAX_DISPLAY_PRODUCTS);
    }

    if (tep_db_num_rows($special_products_query) > 0) {
      $special_products_array = array();

      while ($special_products = tep_db_fetch_array($special_products_query)) {
        $special_products['products_price'] = $currencies->display_price($special_products['products_price'], tep_get_tax_rate($special_products['products_tax_class_id']));
        $special_products['products_special_price'] = $currencies->display_price($special_products['products_special_price'], tep_get_tax_rate($special_products['products_tax_class_id']));

        $special_products_array[] = $special_products;
      }

      ob_start();
      include('includes/modules/content/' . $this->group . '/templates/special_products.php');

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
    return defined('MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Best Sellers Module', 'MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Max number products to display', 'MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_MAX_DISPLAY_PRODUCTS', '6', 'Maximum number of recently viewed products to display in a index page and category.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_STATUS', 'MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_MAX_DISPLAY_PRODUCTS', 'MODULE_CONTENT_INDEX_SPECIAL_PRODUCTS_SORT_ORDER');
  }
}
