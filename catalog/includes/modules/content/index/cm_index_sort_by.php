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
    global $oscTemplate, $language, $request_type, $PHP_SELF, $listing_sql;

    if (defined('MODULE_LISTING_SORT_INSTALLED') && !empty(MODULE_LISTING_SORT_INSTALLED)) {
      $lsm_array = explode(';', MODULE_LISTING_SORT_INSTALLED);

      $sort_array = array();
      $order = '';

      foreach ($lsm_array as $lsm) {
        $class = substr($lsm, 0, strrpos($lsm, '.'));

        if (!class_exists($class)) {
          include('includes/languages/' . $language . '/modules/listing_sort/' . $lsm);
          include('includes/modules/listing_sort/' . $class . '.php');
        }

        $ls = new $class();

        if ($ls->isEnabled() === true) {
          if (empty($sort_array)) {
            $order = $ls->order();
          }

          if (isset($_GET['sort']) && strpos($_GET['sort'], $ls->param) !== false) {
            $order = $ls->order();
          }

          $sort_array = array_merge($sort_array, $ls->sort());
        }
      }

      $GLOBALS['listing_sql'] = $listing_sql . $order;
    }

    if (!empty($sort_array)) {
       //var_dump($sort_array);die;
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
        if (is_string($value) && !in_array($key, $exclude_array)) {
          $hidden_get_params .= tep_draw_hidden_field($key, $value);
        }
      }

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
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_INDEX_SORT_BY_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_INDEX_SORT_BY_STATUS', 'MODULE_CONTENT_INDEX_SORT_BY_SORT_ORDER');
  }
}
