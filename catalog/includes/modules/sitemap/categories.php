<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

foreach ($languages_array as $languages) {
  $sitemap_file = 'sitemap-categories' . ($languages['code'] == DEFAULT_LANGUAGE ? '' : '-' . $languages['code']) . '.xml';
  $category_tree = array();

  $categories_query = tep_db_query("select c.categories_id, c.parent_id, c.date_added, c.last_modified, cd.categories_name from categories c, categories_description cd where c.categories_id = cd.categories_id and cd.language_id = '" . (int)$languages['languages_id'] . "' order by c.sort_order, cd.categories_name");
  while ($categories = tep_db_fetch_array($categories_query)) {
    $category_tree[$categories['parent_id']][] = $categories;
  }

  tep_db_free_result($categories_query);

  $fp = fopen($sitemap_file, 'w');
  $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

  $xml .= categoriesTree($category_tree, $category_tree[0]);

  $xml .= '</urlset>';

  if (file_put_contents(DIR_FS_CATALOG . $sitemap_file, $xml) !== false) {
    $sitemap_array[] = $sitemap_file;
  }
}

function categoriesTree(array $tree, array $parent_array, &$xml = '', $cPath = array()) {
  if (empty($parent_array)) {
    return null;
  }

  foreach ($parent_array as $categories) {
    $cPath[sizeof($parent_array)] = $categories['categories_id'];

    $xml .= '  <url>' . "\n";
    $xml .= '    <loc>' . tep_href_link('index.php', 'cPath=' . implode('_', $cPath), 'SSL', false) . '</loc>' . "\n";
    $xml .= '    <lastmod>' . (strtotime($categories['last_modified']) > strtotime($categories['date_added']) ? date("Y-m-d", strtotime($categories['last_modified'])) : date("Y-m-d", strtotime($categories['date_added']))) . '</lastmod>' . "\n";
    $xml .= '    <changefreq>weekly</changefreq>' . "\n";
    $xml .= '    <priority>0.5</priority>' . "\n";
    $xml .= '  </url>' . "\n";

    if (isset($tree[$categories['categories_id']])) {
      categoriesTree($tree, $tree[$categories['categories_id']], $xml, $cPath);
    }
  }

  return $xml;
}
