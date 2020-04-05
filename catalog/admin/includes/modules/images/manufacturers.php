<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class Images_manufacturers {
  public $action = 'check';
  public $function_name = 'tep_get_manufacturers_name';
  public $directory = 'manufacturers/';
  public $title;

  public function __construct() {
    $this->title = MODULE_IMAGES_MANUFACTURERS_TITLE;
  }

  public function getOutput() {
    $output = array();

    $manufacturers_query = tep_db_query("select manufacturers_id, manufacturers_image from manufacturers");
    while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
      $output[$manufacturers['manufacturers_id']][] = $manufacturers['manufacturers_image'];
    }

    return $output;
  }
}

if (!function_exists('tep_get_manufacturers_name')) {
  function tep_get_manufacturers_name($manufacturer_id, $language_id = null) {
    $manufacturer_query = tep_db_query("select manufacturers_name from manufacturers where manufacturers_id = '" . (int)$manufacturer_id . "'");
    $manufacturer = tep_db_fetch_array($manufacturer_query);

    return $manufacturer['manufacturers_name'];
  }
}
