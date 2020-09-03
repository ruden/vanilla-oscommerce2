<?php
/*
$Id$

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2020 osCommerce

Released under the GNU General Public License
*/

class cm_pi_review_star_icons {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_TITLE;
    $this->description = MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_STATUS == 'True');

      if (!defined('MODULE_CONTENT_PRODUCT_INFO_REVIEWS_STATUS') || MODULE_CONTENT_PRODUCT_INFO_REVIEWS_STATUS == "False") {
        $this->enabled = false;

        $this->description = '<div class="secWarning">' . MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_ERROR_MAIN_MODULE . '<br /><br /><a target="_blank" href="' . tep_href_link('modules_content.php', 'module=cm_pi_reviews&action=install') . '"><u>' . MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_INSTALL_MAIN_MODULE . '</a></u></div>' . $this->description;
      }
    }
  }

  public function execute() {
    global $oscTemplate, $product_info;

    $rating_data = tep_get_reviews_rating($product_info['products_id']);

    ob_start();
    include('includes/modules/content/' . $this->group . '/templates/review_star_icons.php');

    $oscTemplate->addContent(ob_get_clean(), 'product_info_right');
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_STATUS');
  }

  public function install() {
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_SORT_ORDER', '1', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_STATUS', 'MODULE_CONTENT_PRODUCT_INFO_REVIEW_STAR_ICONS_SORT_ORDER');
  }
}
