<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class Images_products_thumbs {
  public $action = 'resize';
  public $function_name = 'tep_get_products_name';
  public $directory = 'products/thumbs/';
  public $origin_directory = 'products/originals/';
  public $title;

  public function __construct() {
    $this->title = MODULE_IMAGES_PRODUCTS_THUMBS_TITLE;
  }

  public function getOutput() {
    $output = array();

    $products_query = tep_db_query("select products_id, products_image from products");
    while ($products = tep_db_fetch_array($products_query)) {
      $output[$products['products_id']][] = $products['products_image'];

      $pi_query = tep_db_query("select image from products_images where products_id = '" . (int)$products['products_id'] . "'");

      if (tep_db_num_rows($pi_query) > 0) {
        while ($pi = tep_db_fetch_array($pi_query)) {
          $output[$products['products_id']][] = $pi['image'];
        }
      }
    }

    tep_db_free_result($products_query);

    return $output;
  }
}