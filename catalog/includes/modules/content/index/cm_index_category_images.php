<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cm_index_category_images {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_INDEX_CATEGORY_IMAGES_TITLE;
    $this->description = MODULE_CONTENT_INDEX_CATEGORY_IMAGES_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_INDEX_CATEGORY_IMAGES_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_INDEX_CATEGORY_IMAGES_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $current_category_id, $languages_id;

    $categories_query = tep_db_query("select c.*, cd.* from categories c, categories_description cd where c.parent_id = '" . (int)$current_category_id . "' and c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");

    if (tep_db_num_rows($categories_query) > 0) {
      $categories_array = array();

      while ($categories = tep_db_fetch_array($categories_query)) {
        if (!empty($categories['categories_image'])) {
          $categories_array[$categories['categories_id']] = $categories;
          $categories_array[$categories['categories_id']]['cPath'] = tep_get_path($categories['categories_id']);
        }
      }

      if (!empty($categories_array)) {
        ob_start();
        include('includes/modules/content/' . $this->group . '/templates/category_images.php');

        $oscTemplate->addContent(ob_get_clean(), $this->group);
      }
    }
  }

  public function isEnabled() {
    global $category_depth, $current_category_id;

    if ($category_depth == 'nested' && !empty($current_category_id)) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_CONTENT_INDEX_CATEGORY_IMAGES_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_CONTENT_INDEX_CATEGORY_IMAGES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_INDEX_CATEGORY_IMAGES_SORT_ORDER', '31', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_INDEX_CATEGORY_IMAGES_STATUS', 'MODULE_CONTENT_INDEX_CATEGORY_IMAGES_SORT_ORDER');
  }
}
