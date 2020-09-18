<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

foreach ($languages_array as $languages) {
  if ($languages['code'] == DEFAULT_LANGUAGE) {
    $sitemap_file = 'sitemap-products-images.xml';

    $products_query = tep_db_query("SELECT p.*, pd.* FROM products p LEFT JOIN products_description pd ON p.products_id = pd.products_id WHERE p.products_status = '1' and language_id = '" . (int)$languages['languages_id'] . "' ORDER BY p.products_last_modified DESC, p.products_date_added DESC");

    $fp = fopen($sitemap_file, 'w');
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

    while ($products = tep_db_fetch_array($products_query)) {
      if (!empty($products['products_image']) && file_exists('images/products/originals/' . $products['products_image'])) {
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . tep_href_link('product_info.php', 'products_id=' . (int)$products['products_id'], 'SSL', false) . '</loc>' . "\n";
        $xml .= '    <image:image>' . "\n";
        $xml .= '      <image:loc>' . $base_url . 'images/products/originals/' . $products['products_image'] . '</image:loc>' . "\n";
        $xml .= '    </image:image>' . "\n";

        $pi_query = tep_db_query("select image from products_images where products_id = '" . (int)$products['products_id'] . "' order by sort_order");
        if (tep_db_num_rows($pi_query) > 0) {
          while ($pi = tep_db_fetch_array($pi_query)) {
            if (file_exists('images/products/originals/' . $pi['image'])) {
              $xml .= '    <image:image>' . "\n";
              $xml .= '      <image:loc>' . $base_url . 'images/products/originals/' . $pi['image'] . '</image:loc>' . "\n";
              $xml .= '    </image:image>' . "\n";
            }
          }
        }

        $xml .= '  </url>' . "\n";
      }
    }

    $xml .= '</urlset>';

    tep_db_free_result($products_query);

    if (file_put_contents(DIR_FS_CATALOG . $sitemap_file, $xml) !== false) {
      $sitemap_array[] = $sitemap_file;
    }
  }
}
