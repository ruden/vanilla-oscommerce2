<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class fm_recently_viewed_products {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_TITLE;
    $this->description = MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_SORT_ORDER;
      $this->enabled = (MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $currencies, $languages_id;

    if (!empty($_SESSION['recently_viewed_products'])) {
      $recently_viewed_products_query = tep_db_query("select p.*, pd.*, if(s.status, s.specials_new_products_price, null) as specials_new_products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id in ('" . implode("', '", array_slice(tep_db_prepare_input($_SESSION['recently_viewed_products']), 0, (int)MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_MAX_DISPLAY_PRODUCTS)) . "')");

      if (tep_db_num_rows($recently_viewed_products_query) > 0) {
        $recently_viewed_products_array = array();

        while ($recently_viewed_products = tep_db_fetch_array($recently_viewed_products_query)) {
          $recently_viewed_products['products_price'] = $currencies->display_price($recently_viewed_products['products_price'], tep_get_tax_rate($recently_viewed_products['products_tax_class_id']));

          if (!empty($recently_viewed_products['specials_new_products_price'])) {
            $recently_viewed_products['specials_new_products_price'] = $currencies->display_price($recently_viewed_products['specials_new_products_price'], tep_get_tax_rate($recently_viewed_products['products_tax_class_id']));
          }

          $recently_viewed_products_array[] = $recently_viewed_products;
        }

        ob_start();
        include('includes/modules/' . $this->group . '/templates/recently_viewed_products.php');

        $oscTemplate->addBlock(ob_get_clean(), 'footer_top');
      }
    }
  }

  function isEnabled() {
    global $PHP_SELF;

    if (in_array($PHP_SELF, explode(';', MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_PAGES))) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Max number products to display', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_MAX_DISPLAY_PRODUCTS', '5', 'Maximum number of recently viewed products to display in page.', '6', '0', now())");
    tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Show box on pages', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_PAGES', '" . implode(';', tep_set_custom_pages()) . "', 'The pages on which box is shown.', '6', '0', 'tep_cfg_show_pages', 'tep_cfg_edit_pages(', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Image Width', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_IMAGE_WIDTH', '0', 'The pixel width of images.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Image Height', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_IMAGE_HEIGHT', '96', 'The pixel height of images.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_SORT_ORDER', '11', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_STATUS', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_MAX_DISPLAY_PRODUCTS', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_PAGES', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_IMAGE_WIDTH', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_IMAGE_HEIGHT', 'MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_SORT_ORDER');
  }
}
