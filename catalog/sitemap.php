<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if (file_exists(__DIR__ . '/sitemap-index.xml') && filesize(__DIR__ . '/sitemap-index.xml') > 0) {
  header('Location: sitemap-index.xml', true, 301);

  exit();
}

require('includes/application_top.php');

$base_url = ($request_type == 'SSL' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG;
$sitemap_array = array();
$languages_array = array();

$languages_query = tep_db_query("SELECT languages_id, code FROM languages");
while ($languages = tep_db_fetch_array($languages_query)) {
  if ($languages['code'] == DEFAULT_LANGUAGE || SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
    $languages_array[] = $languages;
  }
}

$directory = 'includes/modules/sitemap';
if ($dir = @dir($directory)) {
  while ($file = $dir->read()) {
    if (!is_dir($directory . $file)) {
      if (substr($file, strrpos($file, '.')) == '.php') {
        $modules = substr($file, 0, strrpos($file, '.'));

        include($directory . '/' . $modules . '.php');
      }
    }
  }
}

// create sitemap index
if (!empty($sitemap_array)) {
  $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

  foreach ($sitemap_array as $sitemap) {
    $xml .= '  <sitemap>' . "\n";
    $xml .= '    <loc>' . $base_url . $sitemap . '</loc>' . "\n";
    $xml .= '    <lastmod>' . date('Y-m-d') . '</lastmod>' . "\n";
    $xml .= '  </sitemap>' . "\n";
  }

  $xml .= '</sitemapindex>';

  $file_index = DIR_FS_CATALOG . '/sitemap-index.xml';

  file_put_contents($file_index, $xml);

  header('Content-type: text/xml');
  echo $xml;

  if (file_exists($file_index) && filesize($file_index) > 0) {
    @file_get_contents('http://google.com/webmasters/sitemaps/ping?sitemap=' . urlencode($base_url . 'sitemap-index.xml'), 'r');
    @file_get_contents('http://www.bing.com/webmaster/ping.aspx?siteMap=' . urlencode($base_url . 'sitemap-index.xml'), 'r');
  }
}