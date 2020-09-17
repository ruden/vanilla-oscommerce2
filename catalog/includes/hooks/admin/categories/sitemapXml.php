<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class hook_admin_categories_sitemapXml {
  public function __construct() {
    if ($this->isAction()) {
      $this->listen_removeSitemapIndexXml();
    }
  }

  public function listen_removeSitemapIndexXml() {
    $file_index = DIR_FS_CATALOG . '/sitemap-index.xml';

    if (file_exists($file_index)) {
      @unlink($file_index);
    }
  }

  public function isAction() {
    $action_list = 'setflag|insert|update|confirm';

    return isset($_GET['action']) && preg_match('/^(' . $action_list . ')/', $_GET['action']);
  }
}
