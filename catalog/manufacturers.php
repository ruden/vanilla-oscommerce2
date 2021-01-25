<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require('includes/languages/' . $language . '/manufacturers.php');

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('manufacturers.php'));

$manufacturers_heading_title = HEADING_TITLE;

if (isset($_GET['manufacturer_id'])) {
  $manufacturers_query = tep_db_query("select m.*, mi.* from manufacturers m left join manufacturers_info mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "') where m.manufacturers_id = '" . (int)$_GET['manufacturer_id'] . "'");
  $manufacturers = tep_db_fetch_array($manufacturers_query);

  if (isset($manufacturers['manufacturers_id'])) {
    $breadcrumb->add($manufacturers['manufacturers_name'], tep_href_link('manufacturers.php', 'manufacturer_id=' . $manufacturers['manufacturers_id']));

    $manufacturers_heading_title = $manufacturers['manufacturers_name'];
  } else {
    http_response_code(404);
  }
} else {
  $manufacturers_array = array();

  $manufacturers_query = tep_db_query("select m.*, mi.* from manufacturers m left join manufacturers_info mi on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$languages_id . "')");
  while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
    $manufacturers_array[$manufacturers['manufacturers_name']] = $manufacturers;
  }

  uksort($manufacturers_array, function ($a, $b) {
    return is_int($a) != is_int($b) ? $b <=> $a : $a <=> $b;
  });
}

require('includes/template_top.php');
?>

  <h1><?php echo $manufacturers_heading_title; ?></h1>

<?php
if (isset($_GET['manufacturer_id'])) {
  $listing_sql = "select p.*, pd.*, if(s.status, s.specials_new_products_price, null) as specials_new_products_price, if(s.status, s.specials_new_products_price, p.products_price) as final_price from products p left join specials s on p.products_id = s.products_id, products_description pd, manufacturers m where p.products_status = '1' and pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.manufacturers_id = m.manufacturers_id and m.manufacturers_id = '" . (int)$_GET['manufacturer_id'] . "'";

  $listing_split = new splitPageResults($listing_sql, MAX_DISPLAY_SEARCH_RESULTS, 'p.products_id');

  require('includes/modules/product_listing.php');
} else {
  ?>

  <div class="row mb-5">

    <?php
    if (!empty($manufacturers_array)) {
      $last_letter = null;

      foreach ($manufacturers_array as $manufacturers) {
        $letter = $manufacturers['manufacturers_name'][0];

        if (strtolower($letter) !== strtolower($last_letter)) {
          if (!empty($last_letter)) {
            ?>

            </ul>

            <?php
          }
          ?>

          <h5><?php echo strtoupper($letter); ?></h5>

          <ul class="list-unstyled">

          <?php
          $last_letter = $letter;
        }
        ?>

        <li>
          <a href="<?php echo tep_href_link('manufacturers.php', 'manufacturer_id=' . $manufacturers['manufacturers_id']); ?>"><?php echo $manufacturers['manufacturers_name']; ?></a>
        </li>

        <?php
      }
      ?>

      </ul>

      <?php
    } else {
      ?>

      <p><?php echo TEXT_MANUFACTURER_NOT_FOUND; ?></p>

      <?php
    }
    ?>

  </div>

  <?php
}
require('includes/template_bottom.php');
require('includes/application_bottom.php');