<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class Images_categories {
  public $action = 'check';
  public $function_name = 'tep_get_categories_name';
  public $directory = 'categories/';
  public $title;

  public function __construct() {
    $this->title = MODULE_IMAGES_CATEGORIES_TITLE;;
  }

  public function getOutput() {
    $output = array();

    $categories_query = tep_db_query("select categories_id, categories_image from categories");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $output[$categories['categories_id']][] = $categories['categories_image'];
    }

    return $output;
  }
}

if (!function_exists('tep_get_categories_name')) {
  function tep_get_categories_name($categories_id, $language_id) {
    $categories_query = tep_db_query("select categories_name from categories_description where categories_id = '" . (int)$categories_id . "' and language_id = '" . (int)$language_id . "'");
    $categories = tep_db_fetch_array($categories_query);

    return $categories['categories_name'];
  }
}
