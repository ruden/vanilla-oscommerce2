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
if (isset($cPath) && !empty($cPath)) {
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

require('includes/languages/' . $language . '/index.php');
require('includes/template_top.php');
?>

<?php echo $oscTemplate->getContent('index'); ?>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');