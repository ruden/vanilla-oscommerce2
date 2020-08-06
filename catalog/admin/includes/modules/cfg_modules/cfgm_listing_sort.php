<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class cfgm_listing_sort {
  public $code = 'listing_sort';
  public $directory;
  public $language_directory = DIR_FS_CATALOG_LANGUAGES;
  public $key = 'MODULE_LISTING_SORT_INSTALLED';
  public $title;
  public $template_integration = false;

  function __construct() {
    $this->directory = DIR_FS_CATALOG_MODULES . 'listing_sort/';
    $this->title = MODULE_CFG_MODULE_LISTING_SORT_ITLE;
  }
}