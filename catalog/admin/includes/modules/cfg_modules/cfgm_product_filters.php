<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cfgm_product_filters {
  public $code = 'product_filters';
  public $directory;
  public $language_directory = DIR_FS_CATALOG_LANGUAGES;
  public $key = 'MODULE_PRODUCT_FILTERS_INSTALLED';
  public $title;
  public $template_integration = false;

  function __construct() {
    $this->directory = DIR_FS_CATALOG_MODULES . 'product_filters/';
    $this->title = MODULE_CFG_MODULE_PRODUCT_FILTERS_ITLE;
  }
}
