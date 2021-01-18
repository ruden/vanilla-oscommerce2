<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cm_index_sort_by {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_INDEX_SORT_BY_TITLE;
    $this->description = MODULE_CONTENT_INDEX_SORT_BY_DESCRIPTION;

    if ($this->check() === true) {
      $this->sort_order = MODULE_CONTENT_INDEX_SORT_BY_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_INDEX_SORT_BY_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $language, $PHP_SELF, $listing_sql;

    if (defined('MODULE_LISTING_SORT_INSTALLED') && !empty(MODULE_LISTING_SORT_INSTALLED)) {
      $sbm_array = explode(';', MODULE_LISTING_SORT_INSTALLED);

      $sort_array = array();
      $order = '';

      foreach ($sbm_array as $sbm) {
        $class = substr($sbm, 0, strrpos($sbm, '.'));

        if (!class_exists($class)) {
          include('includes/languages/' . $language . '/modules/listing_sort/' . $sbm);
          include('includes/modules/listing_sort/' . $class . '.php');
        }

        $sb = new $class();

        if ($sb->isEnabled()) {
          if (empty($sort_array)) {
            $order = $sb->order();
          }

          if (isset($_GET['sort']) && strpos($_GET['sort'], $sb->param) !== false) {
            $order = $sb->order();
          }

          $sort_array = array_merge($sort_array, $sb->sort());
        }
      }

      $oscTemplate->_data[$this->group]['order_by_str'] = $order; //
    }

    if (!empty($sort_array)) {
      $hidden_get_params = $this->allHiddenGetParams();

      ob_start();
      include('includes/modules/content/' . $this->group . '/templates/sort_by.php');

      $oscTemplate->addBlock(ob_get_clean(), 'sort_by');
    }
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_CONTENT_INDEX_SORT_BY_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_CONTENT_INDEX_SORT_BY_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_INDEX_SORT_BY_SORT_ORDER', '51', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_INDEX_SORT_BY_STATUS', 'MODULE_CONTENT_INDEX_SORT_BY_SORT_ORDER');
  }

  public function allHiddenGetParams() {
    $hidden_get_params = '';
    $exclude_array = array(session_name(),
                           'page',
                           'sort',
                           'x',
                           'y',
                           'language',
                           'currency',
                           'languages_id');

    foreach ($_GET as $key => $value) {
      if (!preg_match('/^(' . implode('|', $exclude_array)  . ')/', $key) && !empty($value)) {
        if (is_array($_GET[$key])) {
          foreach ($_GET[$key] as $k => $v) {
            $hidden_get_params .= tep_draw_hidden_field($key . '[' . $k . ']', tep_db_prepare_input($v));
          }
        } else {
          $hidden_get_params .= tep_draw_hidden_field($key, tep_db_prepare_input($value));
        }
      }
    }

    return $hidden_get_params;
  }
}
