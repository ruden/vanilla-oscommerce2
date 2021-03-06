<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class sb_pinterest {
    var $code = 'sb_pinterest';
    var $title;
    var $description;
    var $sort_order;
    var $icon;
    var $enabled = false;

    function __construct() {
      $this->title = MODULE_SOCIAL_BOOKMARKS_PINTEREST_TITLE;
      $this->public_title = MODULE_SOCIAL_BOOKMARKS_PINTEREST_PUBLIC_TITLE;
      $this->description = MODULE_SOCIAL_BOOKMARKS_PINTEREST_DESCRIPTION;

      if ( defined('MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS') ) {
        $this->sort_order = MODULE_SOCIAL_BOOKMARKS_PINTEREST_SORT_ORDER;
        $this->enabled = (MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS == 'True');
      }
    }

    function getOutput() {
      global $oscTemplate, $product_info;

// add the js in the footer
      $oscTemplate->addBlock('<script src="//assets.pinterest.com/js/pinit.js"></script>', 'footer_scripts');

      $params = array();

// grab the product name (used for description)
      $params['description'] = $product_info['products_name'];

// and image (used for media)
      if (!empty($product_info['products_image'])) {
        $image_file = $product_info['products_image'];

        $products_images = empty($product_info['products_images']) ? array() : explode(',', $product_info['products_images']);

        foreach ($products_images as $pi) {
          if (!empty($pi)) {
            $image_file = $pi; // overwrite image with first multiple product image
            break;
          }
        }

        $params['media'] = tep_href_link('images/products/originals/' . $image_file, '', 'SSL', false, false);
      }

// url
      $params['url'] = tep_href_link('product_info.php', 'products_id=' . $product_info['products_id'], 'SSL', false);

      $output = '<a href="http://pinterest.com/pin/create/button/?';

      foreach ($params as $key => $value) {
        $output .= $key . '=' . urlencode($value) . '&';
      }

      $output = substr($output, 0, -1); //remove last & from the url

      $output .= '" class="pin-it-button" count-layout="' . strtolower(MODULE_SOCIAL_BOOKMARKS_PINTEREST_BUTTON_COUNT_POSITION) . '"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="' . $this->public_title . '" /></a>';

      return $output;
    }

    function isEnabled() {
      return $this->enabled;
    }

    function getPublicTitle() {
      return $this->public_title;
    }

    function check() {
      return defined('MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS');
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Pinterest Module', 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS', 'True', 'Do you want to allow Pinterest Button?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Layout Position', 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_BUTTON_COUNT_POSITION', 'None', 'Horizontal or Vertical or None', '6', '2', 'tep_cfg_select_option(array(\'Horizontal\', \'Vertical\', \'None\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SOCIAL_BOOKMARKS_PINTEREST_STATUS', 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_BUTTON_COUNT_POSITION', 'MODULE_SOCIAL_BOOKMARKS_PINTEREST_SORT_ORDER');
    }
  }