<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class ht_open_graph {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;
    public $data;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(dirname(__FILE__));

      $this->title = MODULE_HEADER_TAGS_OPEN_GRAPH_TITLE;
      $this->description = MODULE_HEADER_TAGS_OPEN_GRAPH_DESCRIPTION;

      if ($this->check()) {
        $this->sort_order = MODULE_HEADER_TAGS_OPEN_GRAPH_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_OPEN_GRAPH_STATUS == 'True');
      }
    }

    public function execute() {
      global $oscTemplate, $currency, $lng, $product_info, $categories, $cPath, $manufacturers;

      if (!isset($lng) || (isset($lng) && !is_object($lng))) {
        include('includes/classes/language.php');
        $lng = new language;
      }

      foreach (array_keys($lng->catalog_languages) as $code) {
        if ($code == DEFAULT_LANGUAGE) {
          $this->data['og:locale'] = $this->locale(DEFAULT_LANGUAGE);
        } else {
          $this->data['og:locale:alternate'] = $this->locale($code);
        }
      }

      $this->data['og:site_name'] = tep_output_string(STORE_NAME);

      if (isset($product_info['products_id'])) {
        $this->data['og:type'] = 'product';
        $this->data['og:url'] = tep_href_link('product_info.php', 'products_id=' . $product_info['products_id'], 'SSL', false);
        $this->data['og:title'] = $product_info['products_name'];
        $this->get_og_description($product_info['products_description']);
        $this->get_og_image('images/products/originals/' . $product_info['products_image']);

        if (!empty($product_info['products_images'])) {
          $products_images = explode(',', $product_info['products_images']);

          foreach ($products_images as $pi) {
            $this->get_og_image('images/products/originals/' . $pi);
          }
        }

        if (!empty($product_info['manufacturers_name'])) {
          $this->data['product:brand'] = $product_info['manufacturers_name'];
        }

        if (!empty($categories['categories_name'])) {
          $this->data['product:category'] = $categories['categories_name'];
        }

        if (!empty($product_info['specials_new_products_price'])) {
          $products_price = $this->format_raw($product_info['specials_new_products_price']);

          $this->data['product:original_price:amount'] = $this->format_raw($product_info['products_price']);
          $this->data['product:original_price:currency'] = $currency;
        } else {
          $products_price = $this->format_raw($product_info['products_price']);
        }

        $this->data['product:price:amount'] = $products_price;
        $this->data['product:price:currency'] = $currency;

        if (STOCK_CHECK == 'true') {
          if ($product_info['products_date_available'] > date('Y-m-d H:i:s')) {
            $this->data['product:availability'] = 'pending';
          } elseif ($product_info['products_quantity'] < 1) {
            $this->data['product:availability'] = 'oos';
          } else {
            $this->data['product:availability'] = 'instock';
          }
        } else {
          $this->data['product:availability'] = 'instock';
        }
      } elseif (isset($categories['categories_id']) && !empty($cPath)) {
        $this->data['og:type'] = 'product.group';
        $this->data['og:title'] = $categories['categories_name'];
        $this->data['og:url'] = tep_href_link('index.php', 'cPath=' . $cPath, 'SSL', false);
        $this->get_og_description($categories['categories_description']);
        $this->get_og_image('images/categories/' . $categories['categories_image']);
      } elseif (isset($manufacturers['manufacturers_id'])) {
        $this->data['og:type'] = 'product.group';
        $this->data['og:title'] = $manufacturers['manufacturers_name'];
        $this->data['og:url'] = tep_href_link('manufacturers.php', 'manufacturer_id=' . $manufacturers['manufacturers_id'], 'SSL', false);
        $this->get_og_image('images/manufacturers/' . $manufacturers['manufacturers_image']);
      } else { // default page
        $this->data['og:type'] = 'product.group';
        $this->data['og:title'] = tep_output_string(STORE_NAME);
        $this->data['og:url'] = tep_href_link('index.php', '', 'SSL', false);
        $this->get_og_description();
        $this->get_og_image('images/store_logo.png');
      }

      if (count($this->data) > (count($lng->catalog_languages) + 1)) {
        $result = '';

        if (!empty(MODULE_HEADER_TAGS_OPEN_GRAPH_APP_ID)) {
          $result .= '<meta prefix="fb:http://ogp.me/ns/fb#" property="fb:app_id" content="' . htmlspecialchars(MODULE_HEADER_TAGS_OPEN_GRAPH_APP_ID) . '">' . "\n";
        }

        foreach ($this->data as $key => $value) {
          if (!is_numeric($key)) {
            $result .= '<meta property="' . htmlspecialchars($key) . '" content="' . htmlspecialchars($value) . '">' . "\n";
          } else {
            foreach ($value as $k => $v) {
              $result .= '<meta property="' . htmlspecialchars($k) . '" content="' . htmlspecialchars($v) . '">' . "\n";
            }
          }
        }

        $oscTemplate->addBlock($result, $this->group);
      }
    }

    function isEnabled() {
      global $PHP_SELF;

      if (in_array($PHP_SELF, array('index.php', 'manufacturers.php', 'product_info.php'))) {
        return $this->enabled;
      }

      return false;
    }

    public function check() {
      return defined('MODULE_HEADER_TAGS_OPEN_GRAPH_STATUS');
    }

    public function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Open Graph Module', 'MODULE_HEADER_TAGS_OPEN_GRAPH_STATUS', 'True', 'Do you want to allow Open Graph Meta Tags (good for Facebook and Pinterest and other sites) to be added to your product page?  Note that your product thumbnails MUST be at least 200px by 200px.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Facebook App ID', 'MODULE_HEADER_TAGS_OPEN_GRAPH_APP_ID', '', 'Your Facebook APP ID<br />Note: Not Required.<br><br><strong>Helper Links</strong><br /><small>https://developers.facebook.com/docs/opengraph/getting-started/<br />https://developers.facebook.com/docs/opengraph/using-objects/</small>', '6', '0', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_OPEN_GRAPH_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    public function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    public function keys() {
      return array('MODULE_HEADER_TAGS_OPEN_GRAPH_STATUS', 'MODULE_HEADER_TAGS_OPEN_GRAPH_APP_ID', 'MODULE_HEADER_TAGS_OPEN_GRAPH_SORT_ORDER');
    }

    public function format_raw($number, $currency_code = '', $currency_value = '') {
      global $currencies, $currency;

      if (empty($currency_code) || !$currencies->is_set($currency_code)) {
        $currency_code = $currency;
      }

      if (empty($currency_value) || !is_numeric($currency_value)) {
        $currency_value = $currencies->currencies[$currency_code]['value'];
      }

      return number_format(tep_round($number * $currency_value, $currencies->currencies[$currency_code]['decimal_places']), $currencies->currencies[$currency_code]['decimal_places'], '.', '');
    }

    public function locale($code) {
      $locales = array('ar' => 'ar_AR',
                       'bg' => 'bg_BG',
                       'br' => 'pt_BR',
                       'ca' => 'ca_ES',
                       'cs' => 'cs_CZ',
                       'da' => 'da_DK',
                       'de' => 'de_DE',
                       'el' => 'el_GR',
                       'en' => 'en_US',
                       'es' => 'es_ES',
                       'et' => 'et_EE',
                       'fi' => 'fi_FI',
                       'fr' => 'fr_FR',
                       'gl' => 'gl_ES',
                       'he' => 'he_IL',
                       'hu' => 'hu_HU',
                       'id' => 'id_ID',
                       'it' => 'it_IT',
                       'ja' => 'ja_JP',
                       'ko' => 'ko_KR',
                       'ka' => 'ka_GE',
                       'lt' => 'lt_LT',
                       'lv' => 'lv_LV',
                       'nl' => 'nl_NL',
                       'no' => 'nb_NO',
                       'pl' => 'pl_PL',
                       'pt' => 'pt_PT',
                       'ro' => 'ro_RO',
                       'ru' => 'ru_RU',
                       'sk' => 'sk_SK',
                       'sr' => 'sr_RS',
                       'sv' => 'sv_SE',
                       'th' => 'th_TH',
                       'tr' => 'tr_TR',
                       'uk' => 'uk_UA',
                       'tw' => 'zh_TW',
                       'zh' => 'zh_CN');

      if (isset($locales[$code])) {
        return $locales[$code];
      }

      return null;
    }

    public function get_og_description($string = '') {
      if (empty($string) && defined('TEXT_MAIN')) {
        $string = TEXT_MAIN;
      }

      if (empty($string)) {
        return null;
      }

      $string = explode("\n", wordwrap(trim(strip_tags($string)), 197));

      $this->data['og:description'] = $string[0] . '...';

      return $this;
    }

    public function get_og_image($src) {
      if ($image = @getimagesize($src)) {
        array_push($this->data, array('og:image' => tep_href_link($src, '', 'SSL', false, false),
                                       'og:image:type' => $image['mime'],
                                       'og:image:width' => $image[0],
                                       'og:image:height' => $image[1]));

        return $this;
      }

      return null;
    }
  }