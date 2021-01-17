<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cm_index_category_description {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_TITLE;
    $this->description = MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $categories, $cPath_array;

    $description = '';

    if (isset($cPath_array) && isset($categories['categories_description'])) {
      $description = $categories['categories_description'];
    } elseif (defined('TEXT_MAIN') && !empty(TEXT_MAIN)) {
      $description = TEXT_MAIN;
    }

    if (!empty($description)) {
      ob_start();
      include('includes/modules/content/' . $this->group . '/templates/category_description.php');

      $oscTemplate->addContent(ob_get_clean(), $this->group);
    }
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_SORT_ORDER', '71', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_STATUS', 'MODULE_CONTENT_INDEX_CATEGORY_DESCRIPTION_SORT_ORDER');
  }
}
