<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

// the following cPath references come from application_top.php
$category_depth = 'top';
if (isset($cPath) && tep_not_null($cPath)) {
  $categories_products_query = tep_db_query("select 1 from products_to_categories where categories_id = '" . (int)$current_category_id . "' limit 1");
  if (tep_db_num_rows($categories_products_query)) {
    $category_depth = 'products'; // display products
  } else {
    $category_parent_query = tep_db_query("select 1 from categories where parent_id = '" . (int)$current_category_id . "' limit 1");
    if (tep_db_num_rows($category_parent_query)) {
      $category_depth = 'nested'; // navigate through the categories
    } else {
      $category_depth = 'products'; // category has no products, but display the 'no products' message
    }
  }
}

// show the products of a specified manufacturer
if (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id'])) {
  if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
// We are asked to show only a specific category
    $listing_sql = "select p.*, pd.*, m.*, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from products p left join specials s on p.products_id = s.products_id, products_description pd, manufacturers m, products_to_categories p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['filter_id'] . "'";
  } else {
// We show them all
    $listing_sql = "select p.*, pd.*, m.*, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from products p left join specials s on p.products_id = s.products_id, products_description pd, manufacturers m where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
  }
} else {
// show the products in a given categorie
  if (isset($_GET['filter_id']) && tep_not_null($_GET['filter_id'])) {
// We are asked to show only specific catgeory
    $listing_sql = "select p.*, pd.*, m.*, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, IF(s.status, s.specials_new_products_price, p.products_price) as final_price from products p left join specials s on p.products_id = s.products_id, products_description pd, manufacturers m, products_to_categories p2c where p.products_status = '1' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['filter_id'] . "' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$current_category_id . "'";
  } else {
// We show them all
    $where_str = "(p2c.categories_id = '" . (int)$current_category_id . "'";

    if (tep_has_category_subcategories($current_category_id) === true) {
      $subcategories_array = array();
      tep_get_subcategories($subcategories_array, $current_category_id);

      for ($i = 0, $n = sizeof($subcategories_array); $i < $n; $i++) {
        $where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
      }
    }

    $where_str .= ")";

    $listing_sql = "select p.*, pd.*, m.*, if(s.status, s.specials_new_products_price, null) as specials_new_products_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price from products_description pd, products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id left join specials s on p.products_id = s.products_id, products_to_categories p2c where p.products_status = '1' and p.products_id = p2c.products_id and pd.products_id = p2c.products_id and pd.language_id = '" . (int)$languages_id . "' and $where_str";
  }
}

require('includes/languages/' . $language . '/index.php');

require('includes/template_top.php');

$page_blocks = $oscTemplate->getBlocks('index_listing');

if ($category_depth != 'top') {
  ?>

  <div class="mb-5">
    <?php echo $page_blocks; ?>
  </div>

  <?php

  require('includes/modules/product_listing.php');
} else { // default page
  ?>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="mb-5">
    <?php echo $page_blocks; ?>
  </div>

  <?php
}

require('includes/template_bottom.php');
require('includes/application_bottom.php');
