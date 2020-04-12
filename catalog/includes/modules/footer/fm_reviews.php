<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class fm_reviews {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_FOOTER_REVIEWS_TITLE;
      $this->description = MODULE_FOOTER_REVIEWS_DESCRIPTION;

      if ( defined('MODULE_FOOTER_REVIEWS_STATUS') ) {
        $this->sort_order = MODULE_FOOTER_REVIEWS_SORT_ORDER;
        $this->enabled = (MODULE_FOOTER_REVIEWS_STATUS == 'True');
      }
    }

    public function execute() {
      global $oscTemplate, $languages_id;

      $reviews_query = tep_db_query("select r.*, p.products_id, p.products_image, pd.products_name from reviews r, reviews_description rd, products p, products_description pd where p.products_status = '1' and p.products_id = r.products_id and r.reviews_id = rd.reviews_id and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and rd.languages_id = '" . (int)$languages_id . "' and r.reviews_status = 1 order by r.reviews_id desc limit " . (int)MODULE_FOOTER_REVIEWS_MAX_DISPLAY_REVIEWS);

      if (tep_db_num_rows($reviews_query) >= (int)MODULE_FOOTER_REVIEWS_MIN_DISPLAY_REVIEWS) {
        ob_start();
        include('includes/modules/' . $this->group . '/templates/reviews.php');

        $oscTemplate->addBlock(ob_get_clean(), $this->group);
      }
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_FOOTER_REVIEWS_STATUS');
    }

    public function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Module', 'MODULE_FOOTER_REVIEWS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Min reviews', 'MODULE_FOOTER_REVIEWS_MIN_DISPLAY_REVIEWS', '1', 'Minimum number of reviews to display.', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Max reviews', 'MODULE_FOOTER_REVIEWS_MAX_DISPLAY_REVIEWS', '6', 'Maximum number of reviews to display.', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_FOOTER_REVIEWS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    public function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      return array('MODULE_FOOTER_REVIEWS_STATUS', 'MODULE_FOOTER_REVIEWS_MIN_DISPLAY_REVIEWS', 'MODULE_FOOTER_REVIEWS_MAX_DISPLAY_REVIEWS', 'MODULE_FOOTER_REVIEWS_SORT_ORDER');
    }
  }
