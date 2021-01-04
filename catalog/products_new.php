<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/products_new.php');

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('products_new.php'));

$listing_sql = "select p.*, pd.*, m.*, if(s.status, s.specials_new_products_price, null) as specials_new_products_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price from products p left join manufacturers m on p.manufacturers_id = m.manufacturers_id left join products_description pd on p.products_id = pd.products_id left join specials s on p.products_id = s.products_id where p.products_status = '1' and pd.language_id = '" . (int)$languages_id . "'";

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

require('includes/modules/product_listing.php');

require('includes/template_bottom.php');
require('includes/application_bottom.php');
