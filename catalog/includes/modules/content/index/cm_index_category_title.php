<?php
/*
$Id$

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2020 osCommerce

Released under the GNU General Public License
*/

class cm_index_category_title {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_INDEX_CATEGORY_TITLE_TITLE;
    $this->description = MODULE_CONTENT_INDEX_CATEGORY_TITLE_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_INDEX_CATEGORY_TITLE_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_INDEX_CATEGORY_TITLE_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $categories;

    $catname = '';

    if (isset($categories['categories_name'])) {
      $catname = $categories['categories_name'];
    } elseif (defined('HEADING_TITLE')) {
      $catname = HEADING_TITLE;
    } elseif (defined('HEADING_TITLE_2')) {
      $catname = HEADING_TITLE_2;
    } elseif (defined('HEADING_TITLE_1')) {
      $catname = HEADING_TITLE_1;
    }

    if (!empty($catname)) {
      ob_start();
      include('includes/modules/content/' . $this->group . '/templates/category_title.php');

      $oscTemplate->addContent(ob_get_clean(), $this->group);
    }
  }

  public function isEnabled() {
    global $current_category_id;

    if ($current_category_id > 0) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_CONTENT_INDEX_CATEGORY_TITLE_STATUS');
  }

  public function install() {
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_CONTENT_INDEX_CATEGORY_TITLE_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_INDEX_CATEGORY_TITLE_SORT_ORDER', '21', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_INDEX_CATEGORY_TITLE_STATUS', 'MODULE_CONTENT_INDEX_CATEGORY_TITLE_SORT_ORDER');
  }
}
