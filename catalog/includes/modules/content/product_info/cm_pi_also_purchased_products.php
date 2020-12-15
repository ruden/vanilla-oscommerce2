<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cm_pi_also_purchased_products {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_TITLE;
    $this->description = MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $languages_id, $currencies;

    $orders_query = tep_db_query("select distinct p.*, pd.*, if(s.status, s.specials_new_products_price, null) as specials_new_products_price from orders_products opa left join orders_products opb on opa.orders_id = opb.orders_id left join orders o on opb.orders_id = o.orders_id left join products p on opb.products_id = p.products_id left join products_description pd on pd.products_id = p.products_id left join specials s on p.products_id = s.products_id where opa.products_id = '" . (int)$_GET['products_id'] . "' and opb.products_id != '" . (int)$_GET['products_id'] . "' and pd.language_id = '" . (int)$languages_id . "' and p.products_status = '1' group by p.products_id, o.date_purchased order by o.date_purchased desc limit " . (int)MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MAX_DISPLAY_PRODUCTS);

    if (tep_db_num_rows($orders_query) >= MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MIN_DISPLAY_PRODUCTS) {
      $orders_array = array();

      while ($orders = tep_db_fetch_array($orders_query)) {
        $orders['products_price'] = $currencies->display_price($orders['products_price'], tep_get_tax_rate($orders['products_tax_class_id']));

        if (!empty($orders['specials_new_products_price'])) {
          $orders['specials_new_products_price'] = $currencies->display_price($orders['specials_new_products_price'], tep_get_tax_rate($orders['products_tax_class_id']));
        }

        $orders_array[] = $orders;
      }

      ob_start();
      include('includes/modules/content/' . $this->group . '/templates/also_purchased_products.php');

      $oscTemplate->addContent(ob_get_clean(), $this->group);
    }
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable New User Module', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_STATUS', 'True', 'Do you want to enable the new user module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Min products', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MIN_DISPLAY_PRODUCTS', '1', 'Minimum number of products to display.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Max products', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MAX_DISPLAY_PRODUCTS', '6', 'Maximum number of products to display.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_SORT_ORDER', '15', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_STATUS', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MIN_DISPLAY_PRODUCTS', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MAX_DISPLAY_PRODUCTS', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_SORT_ORDER');
  }
}