<?php
/**
 * osCommerce Online Merchant
 *
 * @copyright (c) 2020 osCommerce; https://www.oscommerce.com
 * @license MIT; https://www.oscommerce.com/license/mit.txt
 *
 * @author Valeriy Rudenko <ruden@osclab.com>
 * @copyright (c) 2020 OSCLab: https://osclab.com
 */

  class hook_admin_manufacturers_sitemapXml {
    function __construct() {
      if ($this->isAction()) {
        $this->listen_removeSitemapIndexXml();
      }
    }

    function listen_removeSitemapIndexXml() {
      if (file_exists(DIR_FS_CATALOG . 'sitemap-index.xml')) {
        @unlink(DIR_FS_CATALOG . 'sitemap-index.xml');
      }
    }

    function isAction() {
      $action_list = 'new|edit|delete';

      return isset($_GET['action']) && !preg_match('/^(' . $action_list . ')/', $_GET['action']);
    }
  }