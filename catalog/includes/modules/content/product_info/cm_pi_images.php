<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cm_pi_images {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_PRODUCT_INFO_IMAGES_TITLE;
    $this->description = MODULE_CONTENT_PRODUCT_INFO_IMAGES_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_PRODUCT_INFO_IMAGES_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_PRODUCT_INFO_IMAGES_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $product_info;

    $products_name = addslashes($product_info['products_name']);
    $products_images_array = array();

    if (!empty($product_info['products_image']) && is_file('images/products/originals/' . $product_info['products_image'])) {
      $products_images_array[] = array('image' => $product_info['products_image'],
                                       'htmlcontent' => '');

      $pi_query = tep_db_query("select image, htmlcontent from products_images where products_id = '" . (int)$product_info['products_id'] . "' order by sort_order");

      if (tep_db_num_rows($pi_query) > 0) {
        while ($pi = tep_db_fetch_array($pi_query)) {
          $products_images_array[] = $pi;
        }
      }
    }

    ob_start();
    include('includes/modules/content/' . $this->group . '/templates/images.php');

    $oscTemplate->addContent(ob_get_clean(), 'product_info_left');
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_CONTENT_PRODUCT_INFO_IMAGES_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Original Image Width', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_ORIGINAL_IMAGE_WIDTH', '', 'The pixel width of original images.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Original Image Height', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_ORIGINAL_IMAGE_HEIGHT', '360', 'The pixel height of original images.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Thumb Image Width', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_THUMB_IMAGE_WIDTH', '', 'The pixel width of thumb images.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Thumb Image Height', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_THUMB_IMAGE_HEIGHT', '96', 'The pixel height of thumb images.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_SORT_ORDER', '100', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_PRODUCT_INFO_IMAGES_STATUS', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_ORIGINAL_IMAGE_WIDTH', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_ORIGINAL_IMAGE_HEIGHT', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_THUMB_IMAGE_WIDTH', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_THUMB_IMAGE_HEIGHT', 'MODULE_CONTENT_PRODUCT_INFO_IMAGES_SORT_ORDER');
  }
}
