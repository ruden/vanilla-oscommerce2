<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class cfgm_footer {
    var $code = 'footer';
    var $directory;
    var $language_directory = DIR_FS_CATALOG_LANGUAGES;
    var $key = 'MODULE_FOOTER_INSTALLED';
    var $title;
    var $template_integration = true;

    function __construct() {
      $this->directory = DIR_FS_CATALOG_MODULES . 'footer/';
      $this->title = MODULE_CFG_MODULE_FOOTER_TITLE;
    }
  }