<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class ht_twitter_product_card {
    public $code = 'ht_twitter_product_card';
    public $group = 'header_tags';
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->title = MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_TITLE;
      $this->description = MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_DESCRIPTION;

      if ($this->check()) {
        $this->sort_order = MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_STATUS == 'True');
      }
    }

    public function execute() {
      global $oscTemplate, $currencies, $currency, $product_info;

      if (isset($product_info['products_id'])) {
        $data = array('card' => 'summary_large_image',
                      'title' => $product_info['products_name']);

        if (!empty(MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_SITE_ID)) {
          $data['site'] = MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_SITE_ID;
        }

        if (!empty(MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_USER_ID)) {
          $data['creator'] = MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_USER_ID;
        }

        $product_description = explode("\n", wordwrap(trim(strip_tags($product_info['products_description'])), 197));

        $data['description'] = $product_description[0] . '...';

        $i = '';
        if (!empty($product_info['products_images'])) {
          $products_images = explode(',', $product_info['products_images']);
          $i = count($products_images);

          foreach ($products_images as $pi) {
            $data['image' . $i] = tep_href_link('images/products/originals/' . $pi, '', 'SSL', false, false);
            $i--;
          }
        }

        $data['image' . $i] = tep_href_link('images/products/originals/' . $product_info['products_image'], '', 'SSL', false, false);

        $result = '';

        foreach ($data as $key => $value) {
          $result .= '<meta name="twitter:' . htmlspecialchars($key) . '" content="' . htmlspecialchars($value) . '">' . "\n";
        }

        $oscTemplate->addBlock($result, $this->group);
      }
    }

    public function isEnabled() {
      global $PHP_SELF;

      if ($PHP_SELF == 'product_info.php') {
        return $this->enabled;
      }

      return false;
    }

    public function check() {
      return defined('MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_STATUS');
    }

    public function install() {
      tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Twitter Product Card Module', 'MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_STATUS', 'True', 'Do you want to allow Twitter Product Card tags to be added to your product information pages? Note that your product images MUST be at least 160px by 160px.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Twitter Author @username', 'MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_USER_ID', '', 'Your @username at Twitter', '6', '0', now())");
      tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Twitter Shop @username', 'MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_SITE_ID', '', 'Your shops @username at Twitter (or leave blank if it is the same as your @username above).', '6', '0', now())");
      tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    public function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      return array('MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_STATUS', 'MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_USER_ID', 'MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_SITE_ID', 'MODULE_HEADER_TAGS_TWITTER_PRODUCT_CARD_SORT_ORDER');
    }
  }