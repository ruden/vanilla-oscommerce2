<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class ht_rich_snippets {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_HEADER_TAGS_RICH_SNIPPETS_TITLE;
    $this->description = MODULE_HEADER_TAGS_RICH_SNIPPETS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_HEADER_TAGS_RICH_SNIPPETS_SORT_ORDER;
      $this->enabled = (MODULE_HEADER_TAGS_RICH_SNIPPETS_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate, $product_info;

    $data = array('@context' => 'https://schema.org',
                  '@type' => 'BreadcrumbList',
                  'itemListElement' => $this->ListItem());

    $oscTemplate->addBlock('<script type="application/ld+json">' . json_encode($data) . '</script>', 'footer_scripts');

    $data = array();

    if (!empty($product_info)) {
      $data = $this->Product($product_info);
    }

    if (!empty($data)) {
      $oscTemplate->addBlock('<script type="application/ld+json">' . json_encode($data) . '</script>', 'footer_scripts');
    }
  }

  public function isEnabled() {
    global $PHP_SELF;

    if (in_array($PHP_SELF, array('index.php', 'product_info.php'))) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_HEADER_TAGS_RICH_SNIPPETS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Rich Snippet Module', 'MODULE_HEADER_TAGS_RICH_SNIPPETS_STATUS', 'True', 'Do you want to enable the rich snippet module? ', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES('Add Reviews', 'MODULE_HEADER_TAGS_RICH_SNIPPETS_REVIEWS', '0', 'Specify the number of latest reviews to included in structured data. Zero or empty disable reviews.', '6', '0', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES('Sort Order', 'MODULE_HEADER_TAGS_RICH_SNIPPETS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("DELETE FROM configuration WHERE configuration_key IN ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_HEADER_TAGS_RICH_SNIPPETS_STATUS', 'MODULE_HEADER_TAGS_RICH_SNIPPETS_REVIEWS', 'MODULE_HEADER_TAGS_RICH_SNIPPETS_SORT_ORDER');
  }

  public function Product($product) {
    $data = array('@context' => 'https://schema.org/',
                  '@type' => 'Product',
                  'name' => strip_tags($product['products_name']),
                  'description' => wordwrap(trim(strip_tags($product['products_description'])), 200),
                  'offers' => $this->Offer($product));

    if (!empty($product['products_image'])) {
      $data['image'][] = tep_href_link('images/products/originals/' . $product['products_image'], '', 'SSL', false, false);

      $pi_query = tep_db_query("select image from products_images where products_id = '" . (int)$product['products_id'] . "' order by sort_order");
      while ($pi = tep_db_fetch_array($pi_query)) {
        $data['image'][] = tep_href_link('images/products/originals/' . $pi['image'], '', 'SSL', false, false);
      }
    }

    if (!empty($product['manufacturers_id'])) {
      $data['brand'] = $this->Brand($product);
    }

    if (!empty($product['products_model'])) {
      $data['mpn'] = $product['products_model'];
    }

    $reviews_array = array();
    $reviews_query = tep_db_query("select * from reviews where products_id = '" . (int)$product['products_id'] . "' and reviews_status = 1");
    while ($reviews = tep_db_fetch_array($reviews_query)) {
      $reviews_array[] = $reviews;
    }

    if (!empty($reviews_array)) {
      $data['aggregateRating'] = $this->AggregateRating($reviews_array);

      if (!empty(MODULE_HEADER_TAGS_RICH_SNIPPETS_REVIEWS)) {
        $data['review'] = $this->Review($reviews_array);
      }
    }

    return $data;
  }

  public function Brand($product) {
    return array('@type' => 'Brand',
                 'name' => $product['manufacturers_name']);
  }

  public function ListItem() {
    global $cPath, $breadcrumb;

    $trail = $breadcrumb->_trail;

    $crumb = array();
    foreach ($trail as $key => $value) {
      if (empty($value['link'])) {
        $value['link'] = tep_href_link('index.php', 'cPath=' . $cPath, 'SSL', false);
      }

      $crumb[] = array('@type' => 'ListItem',
                       'position' => ($key + 1),
                       'item' => array('@id' => $value['link'],
                                       'name' => strip_tags($value['title'])));
    }

    return $crumb;
  }

  public function Offer(array $product) {
    global $currency, $cPath;

    if (empty($product['specials_new_products_price'])) {
      $products_price = $this->format_raw($product['products_price']);
    } else {
      $products_price = $this->format_raw($product['specials_new_products_price']);
    }

    return array('@type' => 'Offer',
                 'url' => tep_href_link('product_info.php', 'cPath=' . $cPath . '&products_id=' . $_GET['products_id']),
                 'priceCurrency' => $currency,
                 'price' => $products_price,
                 'itemCondition' => 'https://schema.org/NewCondition',
                 'availability' => $this->ItemAvailability($product));
  }

  public function ItemAvailability(array $product) {
    if (STOCK_CHECK == 'true') {
      if ($product['products_date_available'] > date('Y-m-d H:i:s')) {
        return 'https://schema.org/PreOrder';
      } elseif ($product['products_quantity'] < STOCK_REORDER_LEVEL + 1 && $product['products_quantity'] > 0) {
        return 'https://schema.org/LimitedAvailabilit';
      } elseif ($product['products_quantity'] < 1) {
        return 'https://schema.org/OutOfStock';
      }
    }

    return 'href="https://schema.org/InStock';
  }

  public function Review(array $reviews) {
    $data = array();

    $i = 0;
    foreach ($reviews as $review) {
      $i++;

      $data[] = array('@type' => 'Review',
                      'author' => $this->Person($review['customers_name']),
                      'datePublished' => date(DATE_ISO8601, strtotime($review['date_added'])),
                      'reviewBody' => htmlspecialchars($review['reviews_text']),
                      'reviewRating' => $this->Rating($review['reviews_rating']));

      if ($i == MODULE_HEADER_TAGS_RICH_SNIPPETS_REVIEWS) {
        return $data;
      }
    }

    return $data;
  }

  public function Person($name) {
    return array('@type' => 'Person',
                 'name' => htmlspecialchars($name));
  }

  public function Rating($rating) {
    return array('@type' => 'Rating',
                 'ratingValue' => $rating,
                 'bestRating' => 5,
                 'worstRating' => 1);
  }

  public function AggregateRating(array $reviews) {
    $max = 0;

    foreach ($reviews as $v) {
      $max += $v['reviews_rating'];
    }

    return array('@type' => 'AggregateRating',
                 'ratingValue' => ($max > 0 ? ceil($max / count($reviews)) : $max),
                 'reviewCount' => count($reviews),
                 'bestRating' => 5,
                 'worstRating' => 1);
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
}
