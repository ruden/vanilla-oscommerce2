<?php
/**
 * osCommerce Online Merchant
 *
 * @copyright (c) 2020 osCommerce; https://www.oscommerce.com
 * @license MIT; https://www.oscommerce.com/license/mit.txt
 *
 * @author Valeriy Rudenko <ruden@osclab.com>
 * @copyright (c) 2020 OSCLab: https://osclab.com
 */

if (!function_exists('tep_db_fetch_all')) {
  function tep_db_fetch_all($db_query) {
    return mysqli_fetch_all($db_query, MYSQLI_ASSOC);
  }
}

function init_xml(&$doc, &$root, $index = false) {
  $doc = new DOMDocument('1.0', 'UTF-8');
  // $doc->formatOutput = true; // we want a nice output

  if ($index === false) {
    $root = $doc->createElement('urlset');
  } else {
    $root = $doc->createElement('sitemapindex');
  }

  $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
  $doc->appendChild($root);
}

function create_node(&$doc, &$root, $data, $url = true) {
  if ($url) {
    $parent = $doc->createElement('url');
  } else {
    $parent = $doc->createElement('sitemap');
  }

  $current = $doc->createElement('loc');
  $current->appendChild($doc->createTextNode($data['url']));
  $mod = $doc->createElement('lastmod');
  $mod->appendChild($doc->createTextNode($data['lastmod']));
  $freq = $doc->createElement('changefreq');
  $freq->appendChild($doc->createTextNode($data['freq']));
  $priority = $doc->createElement('priority');
  $priority->appendChild($doc->createTextNode($data['priority']));
  $parent->appendChild($current);
  $parent->appendChild($mod);

  if ($url) {
    $parent->appendChild($freq);
  }

  if ($url) {
    $parent->appendChild($priority);
  }

  $root->appendChild($parent);
}

function save_xml($doc, $file) {
  $file = DIR_FS_CATALOG . $file;

  if (file_put_contents($file, '') !== false) {
    $doc->save($file);
  }
}