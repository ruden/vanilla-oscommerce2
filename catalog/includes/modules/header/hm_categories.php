<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class hm_categories {
  public $code;
  public $group = 'header';
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_HEADER_CATEGORIES_TITLE;
    $this->description = MODULE_HEADER_CATEGORIES_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_HEADER_CATEGORIES_SORT_ORDER;
      $this->enabled = (MODULE_HEADER_CATEGORIES_STATUS == 'True');

      $this->group = 'header_menu';
    }
  }

  public function execute() {
    global $oscTemplate, $SID, $cart, $wishlist;

    $special_products = false;
    if (tep_db_num_rows(tep_db_query("SELECT 1 FROM specials WHERE status = '1'"))) {
      $special_products = true;
    }

    $cart_count_contents = $cart->count_contents();
    if ($cart_count_contents == 0 ) {
      $cart_count_contents = '';
    }

    $wishlist_count_list = $wishlist->count_list();
    if ($wishlist_count_list == 0 ) {
      $wishlist_count_list = '';
    }

    if ((USE_CACHE == 'true') && empty($SID)) {
      $categories_list = $this->cache();
    } else {
      $categories_list = $this->showCategories();
    }

    ob_start();
    include 'includes/modules/header/templates/categories.php';

    $oscTemplate->addBlock(ob_get_clean(), $this->group);
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_HEADER_CATEGORIES_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_HEADER_CATEGORIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_HEADER_CATEGORIES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_HEADER_CATEGORIES_STATUS', 'MODULE_HEADER_CATEGORIES_SORT_ORDER');
  }

  public function showCategories($tree = array(), $parent_array = array(), &$categories_list = '', $cPath = array()) {
    if (empty($tree) && empty($categories_list)) {
      $tree = $this->getCategories();
      $parent_array = $tree[0];
    }

    foreach ($parent_array as $categories) {
      $li_dropdown = '';
      $a_dropdown = '';

      if (isset($tree[$categories['categories_id']])) {
        $li_dropdown = 'dropdown';
        $a_dropdown = 'dropdown-toggle';
      }

      $cPath[sizeof($parent_array). $categories['parent_id']] = $categories['categories_id'];

      if ($categories['parent_id'] == 0) {
        $categories_list .= '<li class="nav-item ' . $li_dropdown . '"><a class="nav-link fw-bold ' . $a_dropdown . '" href="' . tep_href_link('index.php', 'cPath=' . implode('_', $cPath), 'SSL', false) . '">' . $categories['categories_name'] . '</a>' . (isset($tree[$categories['categories_id']]) ? '' : '</li>');
      } else {
        $categories_list .= '<li class="' . $li_dropdown . ' dropdown-submenu"><a class="dropdown-item ' . $a_dropdown . '" href="' . tep_href_link('index.php', 'cPath=' . implode('_', $cPath), 'SSL', false) . '">' . $categories['categories_name'] . '</a>';
      }

      if (isset($tree[$categories['categories_id']])) {
        $categories_list .= '<ul class="dropdown-menu">';
        $this->showCategories($tree, $tree[$categories['categories_id']], $categories_list, $cPath);
        $categories_list .= '</li></ul>';
      }
    }

    return $categories_list;
  }

  public function getCategories() {
    global $languages_id;

    $category_tree = array();

    $categories_query = tep_db_query("select c.*, cd.categories_name from categories c, categories_description cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages_id . "' order by c.sort_order, cd.categories_name");
    while ($categories = tep_db_fetch_array($categories_query)) {
      $category_tree[$categories['parent_id']][] = $categories;
    }

    return $category_tree;
  }

  function cache($auto_expire = false, $refresh = false) {
    global $language;

    $cache_output = '';

    if (($refresh == true) || !read_cache($cache_output, 'categories_box-' . $language . '.cache', $auto_expire)) {
      $cache_output = $this->showCategories();

      write_cache($cache_output, 'categories_box-' . $language . '.cache');
    }

    return $cache_output;
  }
}
