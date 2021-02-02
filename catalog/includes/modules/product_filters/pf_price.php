<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class pf_price {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;
  public $pfrom;
  public $pto;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_PRODUCT_FILTERS_PRICE_TITLE;
    $this->description = MODULE_PRODUCT_FILTERS_PRICE_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_PRODUCT_FILTERS_PRICE_SORT_ORDER;
      $this->enabled = (MODULE_PRODUCT_FILTERS_PRICE_STATUS == 'True');
    }

    $this->pfrom = (isset($_GET['pfrom']) && is_numeric($_GET['pfrom']) ? tep_db_prepare_input($_GET['pfrom']) : null);
    $this->pto = (isset($_GET['pto']) && is_numeric($_GET['pto']) ? tep_db_prepare_input($_GET['pto']) : null);
  }

  public function getOutput() {
    ob_start();
    include('includes/modules/' . $this->group . '/templates/price.php');

    return ob_get_clean();
  }

  public function select() {
    if (DISPLAY_PRICE_WITH_TAX == 'true' && (!empty($this->pfrom) || !empty($this->pto))) {
      return ", sum(tr.tax_rate) as tax_rate ";
    }

    return null;
  }

  public function where() {
    global $currencies, $currency;

    $where_str = '';

    if (!empty($this->pfrom)) {
      if ($currencies->is_set($currency)) {
        $rate = $currencies->get_value($currency);

        $this->pfrom = $this->pfrom / $rate;
      }
    }

    if (!empty($this->pto)) {
      if (isset($rate)) {
        $this->pto = $this->pto / $rate;
      }
    }

    if (DISPLAY_PRICE_WITH_TAX == 'true') {
      if ($this->pfrom > 0) {
        $where_str .= " and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$this->pfrom . ") ";
      }

      if ($this->pto > 0) {
        $where_str .= " and (if(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$this->pto . ") ";
      }
    } else {
      if ($this->pfrom > 0) {
        $where_str .= " and (if(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$this->pfrom . ") ";
      }

      if ($this->pto > 0) {
        $where_str .= " and (if(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$this->pto . ") ";
      }
    }

    if (DISPLAY_PRICE_WITH_TAX == 'true' && (!empty($this->pfrom) || !empty($this->pto))) {
      $where_str .= " group by p.products_id, tr.tax_priority ";
    }

    return $where_str;
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_PRODUCT_FILTERS_PRICE_STATUS');
  }

  public function install() {
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_PRODUCT_FILTERS_PRICE_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_PRODUCT_FILTERS_PRICE_SORT_ORDER', '300', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_PRODUCT_FILTERS_PRICE_STATUS', 'MODULE_PRODUCT_FILTERS_PRICE_SORT_ORDER');
  }
}
