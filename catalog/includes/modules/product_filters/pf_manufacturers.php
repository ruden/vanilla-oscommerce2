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

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_PRODUCT_FILTERS_MANUFACTURERS_TITLE;
    $this->description = MODULE_PRODUCT_FILTERS_MANUFACTURERS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_PRODUCT_FILTERS_MANUFACTURERS_SORT_ORDER;
      $this->enabled = (MODULE_PRODUCT_FILTERS_MANUFACTURERS_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $current_category_id, $languages_id, $cPath;

    if (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id'])) {
      $filterlist_sql = "select distinct c.categories_id as id, cd.categories_name as name from products p, products_to_categories p2c, categories c, categories_description cd where p.products_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' order by cd.categories_name";
    } else {
      $filterlist_sql = "select distinct m.manufacturers_id as id, m.manufacturers_name as name from products p, products_to_categories p2c, manufacturers m where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and p.products_id = p2c.products_id and p2c.categories_id = '" . (int)$current_category_id . "' order by m.manufacturers_name";
    }
    $filterlist_query = tep_db_query($filterlist_sql);
    if (tep_db_num_rows($filterlist_query) > 1) {
      echo '<div>' . tep_draw_form('filter', 'index.php', 'get') . '<p align="right">' . TEXT_SHOW . '&nbsp;';
      if (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id'])) {
        echo tep_draw_hidden_field('manufacturers_id', $_GET['manufacturers_id']);
        $options = array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES));
      } else {
        echo tep_draw_hidden_field('cPath', $cPath);
        $options = array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS));
      }
      echo tep_draw_hidden_field('sort', $_GET['sort']);
      while ($filterlist = tep_db_fetch_array($filterlist_query)) {
        $options[] = array('id' => $filterlist['id'], 'text' => $filterlist['name']);
      }
      echo tep_draw_pull_down_menu('filter_id', $options, (isset($_GET['filter_id']) ? $_GET['filter_id'] : ''), 'onchange="this.form.submit()"');
      echo tep_hide_session_id() . '</p></form></div>' . "\n";
    }

    ob_start();
    include('includes/modules/' . $this->group . '/templates/manufacturers.php');

    $oscTemplate->addBlock(ob_get_clean(), $this->group);
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_PRODUCT_FILTERS_MANUFACTURERS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_PRODUCT_FILTERS_MANUFACTURERS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_PRODUCT_FILTERS_MANUFACTURERS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_PRODUCT_FILTERS_MANUFACTURERS_STATUS', 'MODULE_PRODUCT_FILTERS_MANUFACTURERS_SORT_ORDER');
  }
}
