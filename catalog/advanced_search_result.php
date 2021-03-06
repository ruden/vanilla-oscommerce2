<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/advanced_search.php');

$error = false;

if ((isset($_GET['keywords']) && empty($_GET['keywords'])) &&
    (isset($_GET['pfrom']) && !is_numeric($_GET['pfrom'])) &&
    (isset($_GET['pto']) && !is_numeric($_GET['pto']))) {
  $error = true;

  $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
} else {
  $pfrom = '';
  $pto = '';
  $keywords = '';

  if (isset($_GET['pfrom'])) {
    $pfrom = $_GET['pfrom'];
  }

  if (isset($_GET['pto'])) {
    $pto = $_GET['pto'];
  }

  if (isset($_GET['keywords'])) {
    $keywords = tep_db_prepare_input($_GET['keywords']);
  }

  $price_check_error = false;
  if (!empty($pfrom)) {
    if (!settype($pfrom, 'double')) {
      $error = true;
      $price_check_error = true;

      $messageStack->add_session('search', ERROR_PRICE_FROM_MUST_BE_NUM);
    }
  }

  if (!empty($pto)) {
    if (!settype($pto, 'double')) {
      $error = true;
      $price_check_error = true;

      $messageStack->add_session('search', ERROR_PRICE_TO_MUST_BE_NUM);
    }
  }

  if (($price_check_error == false) && is_float($pfrom) && is_float($pto)) {
    if ($pfrom >= $pto) {
      $error = true;

      $messageStack->add_session('search', ERROR_PRICE_TO_LESS_THAN_PRICE_FROM);
    }
  }

  if (!empty($keywords)) {
    if (!tep_parse_search_string($keywords, $search_keywords)) {
      $error = true;

      $messageStack->add_session('search', ERROR_INVALID_KEYWORDS);
    }
  }
}

if (empty($pfrom) && empty($pto) && empty($keywords)) {
  $error = true;

  $messageStack->add_session('search', ERROR_AT_LEAST_ONE_INPUT);
}

if ($error == true) {
  tep_redirect(tep_href_link('advanced_search.php', tep_get_all_get_params(), 'SSL', true, false));
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('advanced_search.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('advanced_search_result.php', tep_get_all_get_params(), 'SSL', true, false));

$select_str = "select distinct p.products_id, p.*, m.*, pd.*, if(s.status, s.specials_new_products_price, null) as specials_new_products_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price ";

if ((DISPLAY_PRICE_WITH_TAX == 'true') && (!empty($pfrom) || !empty($pto))) {
  $select_str .= ", SUM(tr.tax_rate) as tax_rate ";
}

$from_str = "from products p left join manufacturers m using(manufacturers_id) left join specials s on p.products_id = s.products_id";

if ((DISPLAY_PRICE_WITH_TAX == 'true') && (!empty($pfrom) || !empty($pto))) {
  if (!isset($_SESSION['customer_country_id'])) {
    $customer_country_id = STORE_COUNTRY;
    $customer_zone_id = STORE_ZONE;
  }
  $from_str .= " left join tax_rates tr on p.products_tax_class_id = tr.tax_class_id left join zones_to_geo_zones gz on tr.tax_zone_id = gz.geo_zone_id and (gz.zone_country_id is null or gz.zone_country_id = '0' or gz.zone_country_id = '" . (int)$customer_country_id . "') and (gz.zone_id is null or gz.zone_id = '0' or gz.zone_id = '" . (int)$customer_zone_id . "')";
}

$from_str .= ", products_description pd, categories c, products_to_categories p2c";

$where_str = " where p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id ";

if (isset($_GET['categories_id']) && !empty($_GET['categories_id'])) {
  if (isset($_GET['inc_subcat']) && ($_GET['inc_subcat'] == '1')) {
    $subcategories_array = array();
    tep_get_subcategories($subcategories_array, $_GET['categories_id']);

    $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and (p2c.categories_id = '" . (int)$_GET['categories_id'] . "'";

    for ($i = 0, $n = sizeof($subcategories_array); $i < $n; $i++) {
      $where_str .= " or p2c.categories_id = '" . (int)$subcategories_array[$i] . "'";
    }

    $where_str .= ")";
  } else {
    $where_str .= " and p2c.products_id = p.products_id and p2c.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "' and p2c.categories_id = '" . (int)$_GET['categories_id'] . "'";
  }
}

if (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id'])) {
  $where_str .= " and m.manufacturers_id = '" . (int)$_GET['manufacturers_id'] . "'";
}

if (isset($search_keywords) && (sizeof($search_keywords) > 0)) {
  $where_str .= " and (";
  for ($i = 0, $n = sizeof($search_keywords); $i < $n; $i++) {
    switch ($search_keywords[$i]) {
      case '(':
      case ')':
      case 'and':
      case 'or':
        $where_str .= " " . $search_keywords[$i] . " ";
        break;
      default:
        $keyword = tep_db_prepare_input($search_keywords[$i]);
        $where_str .= "(pd.products_name like '%" . tep_db_input($keyword) . "%' or p.products_model like '%" . tep_db_input($keyword) . "%' or m.manufacturers_name like '%" . tep_db_input($keyword) . "%'";
        if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) $where_str .= " or pd.products_description like '%" . tep_db_input($keyword) . "%'";
        $where_str .= ')';
        break;
    }
  }
  $where_str .= " )";
}

if (!empty($pfrom)) {
  if ($currencies->is_set($currency)) {
    $rate = $currencies->get_value($currency);

    $pfrom = $pfrom / $rate;
  }
}

if (!empty($pto)) {
  if (isset($rate)) {
    $pto = $pto / $rate;
  }
}

if (DISPLAY_PRICE_WITH_TAX == 'true') {
  if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . (double)$pfrom . ")";
  if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . (double)$pto . ")";
} else {
  if ($pfrom > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) >= " . (double)$pfrom . ")";
  if ($pto > 0) $where_str .= " and (IF(s.status, s.specials_new_products_price, p.products_price) <= " . (double)$pto . ")";
}

if ((DISPLAY_PRICE_WITH_TAX == 'true') && (!empty($pfrom) || !empty($pto))) {
  $where_str .= " group by p.products_id, tr.tax_priority";
}

$listing_sql = $select_str . $from_str . $where_str;

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE_2; ?></h1>

<?php
$listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

require('includes/modules/product_listing.php');

require('includes/template_bottom.php');
require('includes/application_bottom.php');