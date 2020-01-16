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

  class securityCheck_sitemap_index_xml {
    var $type = 'warning';

    function __construct() {
      global $language;

      include(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/security_check/sitemap_index_xml.php');

      $this->title = MODULE_SECURITY_CHECK_SITEMAP_INDEX_XML_TITLE;
    }

    function pass() {
      if (!file_exists(DIR_FS_CATALOG . 'sitemap-index.xml')) {
        return false;
      }

      return true;
    }

    function getMessage() {
      return '<a href="' . tep_catalog_href_link('sitemap.php') . '" target="_blank">' . WARNING_SITEMAP_INDEX_XML_EXIST . '</a>';
    }
  }
?>
