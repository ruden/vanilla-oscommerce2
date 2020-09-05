<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class cfgm_index_listing {
    var $code = 'index_listing';
    var $directory;
    var $language_directory = DIR_FS_CATALOG_LANGUAGES;
    var $key = 'MODULE_INDEX_LISTING_INSTALLED';
    var $title;
    var $template_integration = true;

    function __construct() {
      $this->directory = DIR_FS_CATALOG_MODULES . 'index_listing/';
      $this->title = MODULE_CFG_MODULE_INDEX_LISTING_TITLE;
    }
  }
?>