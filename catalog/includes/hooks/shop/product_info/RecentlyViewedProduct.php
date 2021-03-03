<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class hook_shop_product_info_RecentlyViewedProduct {
    public function __construct() {
      if ($this->hasProduct() && $this->isEnabledModule()) {
        $this->listen_addRecentlyViewedProduct();
      }
    }

    public function listen_addRecentlyViewedProduct() {
      if (!isset($_SESSION['recently_viewed_products'])) {
        tep_session_register('recently_viewed_products');
      }

      $id = (int)$_GET['products_id'];

      if (isset($_SESSION['recently_viewed_products'])) {
        foreach ($_SESSION['recently_viewed_products'] as $key => $value) {
          if ($value == $id) {
            unset($_SESSION['recently_viewed_products'][$key]);
            break;
          }
        }

        if ($_SESSION['recently_viewed_products'] && count($_SESSION['recently_viewed_products']) > MAX_DISPLAY_SEARCH_RESULTS) {
          array_pop($_SESSION['recently_viewed_products']);
        }
      } else {
        $_SESSION['recently_viewed_products'] = array();
      }

      array_unshift($_SESSION['recently_viewed_products'], $id);
    }

    public function hasProduct() {
      return isset($_GET['products_id']) && !empty($_GET['products_id']);
    }

    public function isEnabledModule() {
      return defined('MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_STATUS') && MODULE_FOOTER_RECENTLY_VIEWED_PRODUCTS_STATUS == "True";
    }
  }