<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class bm_categories {
    var $code = 'bm_categories';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function __construct() {
      $this->title = MODULE_BOXES_CATEGORIES_TITLE;
      $this->description = MODULE_BOXES_CATEGORIES_DESCRIPTION;

      if ( defined('MODULE_BOXES_CATEGORIES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_CATEGORIES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_CATEGORIES_STATUS == 'True');

        $this->group = ((MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      }
    }

    function getData() {
      global $categories_string, $tree, $languages_id, $cPath, $cPath_array;

      $categories_string = '';
      $tree = array();

      $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from categories c, categories_description cd where c.parent_id = '0' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
      while ($categories = tep_db_fetch_array($categories_query))  {
        $tree[$categories['categories_id']] = array('name' => $categories['categories_name'],
                                                    'parent' => $categories['parent_id'],
                                                    'level' => 0,
                                                    'path' => $categories['categories_id'],
                                                    'next_id' => false);

        if (isset($parent_id)) {
          $tree[$parent_id]['next_id'] = $categories['categories_id'];
        }

        $parent_id = $categories['categories_id'];

        if (!isset($first_element)) {
          $first_element = $categories['categories_id'];
        }
      }

      if (tep_not_null($cPath)) {
        $new_path = '';
        foreach ($cPath_array as $key => $value) {
          unset($parent_id);
          unset($first_id);
          $categories_query = tep_db_query("select c.categories_id, cd.categories_name, c.parent_id from categories c, categories_description cd where c.parent_id = '" . (int)$value . "' and c.categories_id = cd.categories_id and cd.language_id='" . (int)$languages_id ."' order by sort_order, cd.categories_name");
          if (tep_db_num_rows($categories_query)) {
            $new_path .= $value;
            while ($row = tep_db_fetch_array($categories_query)) {
              $tree[$row['categories_id']] = array('name' => $row['categories_name'],
                                                   'parent' => $row['parent_id'],
                                                   'level' => $key+1,
                                                   'path' => $new_path . '_' . $row['categories_id'],
                                                   'next_id' => false);

              if (isset($parent_id)) {
                $tree[$parent_id]['next_id'] = $row['categories_id'];
              }

              $parent_id = $row['categories_id'];

              if (!isset($first_id)) {
                $first_id = $row['categories_id'];
              }

              $last_id = $row['categories_id'];
            }
            $tree[$last_id]['next_id'] = $tree[$value]['next_id'];
            $tree[$value]['next_id'] = $first_id;
            $new_path .= '_';
          } else {
            break;
          }
        }
      }

      $this->tep_show_category($first_element);

      ob_start();
      include 'includes/modules/boxes/templates/categories.php';

      return ob_get_clean();
    }

    function execute() {
      global $SID, $oscTemplate;

      if ((USE_CACHE == 'true') && empty($SID)) {
        $output = $this->cache();
      } else {
        $output = $this->getData();
      }

      $oscTemplate->addBlock($output, $this->group);
    }

    function isEnabled() {
      global $PHP_SELF;

      if (in_array($PHP_SELF, explode(';', MODULE_BOXES_CATEGORIES_PAGES))) {
        return $this->enabled;
      }

      return false;
    }

    function check() {
      return defined('MODULE_BOXES_CATEGORIES_STATUS');
    }

    function install() {
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Categories Module', 'MODULE_BOXES_CATEGORIES_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('Show box on pages', 'MODULE_BOXES_CATEGORIES_PAGES', '" . implode(';', tep_set_default_pages()) . "', 'The pages on which box is shown.', '6', '0', 'tep_cfg_show_pages', 'tep_cfg_edit_pages(', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT', 'Left Column', 'Should the module be loaded in the left or right column?', '6', '0', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Show Category Counts', 'MODULE_BOXES_CATEGORIES_SHOW_COUNTS', 'true', 'Count recursively how many products are in each category', '6', '0', 'tep_cfg_select_option(array(\'true\', \'false\'), ', now())");
      tep_db_query("insert into configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_CATEGORIES_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_CATEGORIES_STATUS', 'MODULE_BOXES_CATEGORIES_PAGES', 'MODULE_BOXES_CATEGORIES_CONTENT_PLACEMENT', 'MODULE_BOXES_CATEGORIES_SHOW_COUNTS', 'MODULE_BOXES_CATEGORIES_SORT_ORDER');
    }

    function tep_show_category($counter) {
      global $tree, $categories_string, $cPath_array;

      $categories_string .= '<li class="list-group-item';

      for ($i=0; $i<$tree[$counter]['level']; $i++) {
        $categories_string .= ' bg-light';
      }

      $categories_string .= '"><a href="';

      if ($tree[$counter]['parent'] == 0) {
        $cPath_new = 'cPath=' . $counter;
      } else {
        $cPath_new = 'cPath=' . $tree[$counter]['path'];
      }

      $categories_string .= tep_href_link('index.php', $cPath_new) . '">';

      if (isset($cPath_array) && in_array($counter, $cPath_array)) {
        $categories_string .= '<strong>';
      }

      $categories_string .= $tree[$counter]['name'];

      if (isset($cPath_array) && in_array($counter, $cPath_array)) {
        $categories_string .= '</strong>';
      }

      if (tep_has_category_subcategories($counter)) {
        $categories_string .= '-&gt;';
      }

      $categories_string .= '</a>';

      if (MODULE_BOXES_CATEGORIES_SHOW_COUNTS == 'true') {
        $products_in_category = tep_count_products_in_category($counter);
        if ($products_in_category > 0) {
          $categories_string .= '&nbsp;(' . $products_in_category . ')';
        }
      }

      $categories_string .= '</li>';

      if ($tree[$counter]['next_id'] != false) {
        $this->tep_show_category($tree[$counter]['next_id']);
      }
    }

    function cache($auto_expire = false, $refresh = false) {
      global $cPath, $language;

      $cache_output = '';

      if (($refresh == true) || !read_cache($cache_output, 'categories_box-' . $language . '.cache' . $cPath, $auto_expire)) {
        $cache_output = $this->getData();

        write_cache($cache_output, 'categories_box-' . $language . '.cache' . $cPath);
      }

      return $cache_output;
    }
  }