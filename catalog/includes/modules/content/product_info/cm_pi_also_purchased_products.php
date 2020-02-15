<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class cm_pi_also_purchased_products {
    var $code;
    var $group;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_TITLE;
      $this->description = MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_DESCRIPTION;

      if ($this->check()) {
        $this->sort_order = MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_SORT_ORDER;
        $this->enabled = (MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_STATUS == 'True');
      }
    }

    function getData() {
      global $oscTemplate, $language, $currencies;

      $orders_query = tep_db_query("select p.products_id, p.products_image from orders_products opa, orders_products opb, orders o, products p, products_description pd where opa.products_id = '" . (int)$_GET['products_id'] . "' and opa.orders_id = opb.orders_id and opb.products_id != '" . (int)$_GET['products_id'] . "' and opb.products_id = p.products_id and opb.orders_id = o.orders_id and pd.products_id = p.products_id and pd.language_id = '" . (int)$language . "' and p.products_status = '1' group by p.products_id, o.date_purchased order by o.date_purchased desc limit " . MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MAX_DISPLAY_PRODUCTS);
      $num_products_ordered = tep_db_num_rows($orders_query);

      if ($num_products_ordered >= MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MIN_DISPLAY_PRODUCTS) {
        $orders_array = array();

        while ($orders = tep_db_fetch_array($orders_query)) {
          $orders_array[] = $orders;
        }

        ob_start();
        include('includes/modules/content/' . $this->group . '/templates/also_purchased_products.php');

        return ob_get_clean();
      }
    }

    function execute() {
      global $SID, $oscTemplate;

      if ((USE_CACHE == 'true') && empty($SID)) {
        $output = $this->cache(3600);
      } else {
        $output = $this->getData();
      }

      $oscTemplate->addContent($output, $this->group);
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_STATUS');
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable New User Module', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_STATUS', 'True', 'Do you want to enable the new user module?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Min products', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MIN_DISPLAY_PRODUCTS', '1', 'Minimum number of products to display.', '6', '0', now())");
      tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Max products', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MAX_DISPLAY_PRODUCTS', '6', 'Maximum number of products to display.', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_STATUS', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MIN_DISPLAY_PRODUCTS', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_MAX_DISPLAY_PRODUCTS', 'MODULE_CONTENT_ALSO_PURCHASED_PRODUCTS_SORT_ORDER');
    }

    function cache($auto_expire = false, $refresh = false) {
      global $language;

      $cache_output = '';

      if (isset($_GET['products_id']) && is_numeric($_GET['products_id'])) {
        if (($refresh == true) || !read_cache($cache_output, 'also_purchased-' . $language . '.cache' . $_GET['products_id'], $auto_expire)) {
          $cache_output = $this->getData();

          write_cache($cache_output, 'also_purchased-' . $language . '.cache' . $_GET['products_id']);
        }
      }

      return $cache_output;
    }
  }