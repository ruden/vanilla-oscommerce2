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

  if (is_file(__DIR__ . '/sitemap-index.xml')) {
    header('Location: sitemap-index.xml', true, 301);
    exit();
  }

  include 'includes/application_top.php';
  include 'includes/functions/sitemap.php';

  function set_cPath($categories, $id) {
    static $entry_id, $cPath_array;

    if (!isset($entry_id) || $entry_id == null) {
      $entry_id = $id;
    }

    (empty($cPath_array) ? $cPath_array = array($id) : null);

    array_push($cPath_array, $categories[$id]['parent']);

    if ((isset($categories[$categories[$id]['parent']]['parent']) && $categories[$categories[$id]['parent']]['parent'] != '0')) {
      set_cPath($categories, $categories[$id]['parent']);
    }

    $rpath = implode('_', array_reverse($cPath_array));

    if ($id == $entry_id) {
      $entry_id = null;
      $cPath_array = array();

      return $rpath;
    }
  }

  function tep_get_categories_tree() {
    $result = tep_db_query("SELECT categories_id, parent_id, date_added, last_modified FROM categories GROUP BY categories_id ORDER BY date_added, last_modified");

    if (!tep_db_num_rows($result)) {
      return array();
    }

    while ($row = tep_db_fetch_array($result)) {
      $categories[$row['categories_id']] = array('id' => $row['categories_id'],
                                                 'parent' => $row['parent_id'],
                                                 'path' => '',
                                                 'date' => (strtotime($row['last_modified']) > strtotime($row['date_added'])) ? $row['last_modified'] : $row['date_added']);
    }

    tep_db_free_result($result);

    foreach ($categories as $key) {
      if ($key['parent'] != '0') {
        (isset($categories[$key['parent']]['children']) && ($categories[$key['parent']]['children'] !== null)) ? null : $categories[$key['parent']]['children'] = '';
        $categories[$key['parent']]['children'] .= $key['id'] . ',';
      } else {
        $categories[$key['id']]['path'] .= $key['id'];
      }
    }

    foreach ($categories as $key) {
      $full_path = '';
      if ($key['parent'] != '0') {
        $full_path = set_cPath($categories, $key['id']);
        $categories[$key['id']]['path'] = $full_path;
      }
    }

    return $categories;
  }

  function create_sitemap_catalog(&$doc, &$root, $file_extension = '.xml') {
    $sitemap = array('sitemap-categories', 'sitemap-products', 'sitemap-manufacturers');

    foreach ($sitemap as $map) {
      create_node($doc, $root, array('url' => tep_href_link($map . $file_extension, '', 'SSL', false),
                                     'lastmod' => date('Y-m-d'),
                                     'freq' => 'weekly',
                                     'priority' => '0.5'), false);
    }
  }

  function create_sitemap($file_extension = '.xml') {
    init_xml($doc, $root);

    $categories = tep_get_categories_tree();

    foreach ($categories as $id => $data) {
      if (preg_match('/[0-9_]/', $data['path'])) {
        create_node($doc, $root, array('url' => tep_href_link('index.php', 'cPath=' . $data['path'], 'SSL', false),
                                       'lastmod' => date('Y-m-d', strtotime($data['date'])),
                                       'freq' => 'weekly',
                                       'priority' => '0.5'));
      }
    }

    save_xml($doc, 'sitemap-categories' . $file_extension);

    init_xml($doc, $root);

    $result = tep_db_query("SELECT DISTINCT p.products_id, p.products_date_added, p.products_last_modified FROM products p LEFT JOIN products_description pd ON p.products_id = pd.products_id WHERE p.products_status = '1' ORDER BY p.products_last_modified DESC, p.products_date_added DESC");

    while ($row = tep_db_fetch_array($result)) {
      create_node($doc, $root, array('url' => tep_href_link('product_info.php', 'products_id=' . (int)$row['products_id'], 'SSL', false),
                                     'lastmod' => (strtotime($row['products_last_modified']) > strtotime($row['products_date_added'])) ? date('Y-m-d', strtotime($row['products_last_modified'])) : date('Y-m-d', strtotime($row['products_date_added'])),
                                     'freq' => 'weekly',
                                     'priority' => '0.5'));
    }

    tep_db_free_result($result);

    save_xml($doc, 'sitemap-products' . $file_extension);

    init_xml($doc, $root);

    $result = tep_db_query("SELECT m.manufacturers_id, m.date_added, m.last_modified FROM manufacturers m ORDER BY m.last_modified DESC, m.date_added DESC");

    while ($row = tep_db_fetch_array($result)) {
      create_node($doc, $root, array('url' => tep_href_link('index.php', 'manufacturers_id=' . (int)$row['manufacturers_id'], 'SSL', false),
                                     'lastmod' => (strtotime($row['last_modified']) > strtotime($row['date_added'])) ? date("Y-m-d", strtotime($row['last_modified'])) : date("Y-m-d", strtotime($row['date_added'])),
                                     'freq' => 'weekly',
                                     'priority' => '0.5'));
    }

    tep_db_free_result($result);

    save_xml($doc, 'sitemap-manufacturers' . $file_extension);
  }

  init_xml($doc, $root, true);

  if (SEARCH_ENGINE_FRIENDLY_URLS == 'true' || (defined('SEO_ENABLED') && SEO_ENABLED == 'true')) {
    $languages = tep_db_fetch_all(tep_db_query("SELECT languages_id, name, code, image, directory FROM languages"));
  } else {
    $languages[]['code'] = DEFAULT_LANGUAGE;
  }

  foreach ($languages as $lang) {
    $file_extension = ($lang['code'] == DEFAULT_LANGUAGE ? '' : '-' . $lang['code']);

    create_sitemap($file_extension . '.xml');
    create_sitemap_catalog($doc, $root, $file_extension . '.xml');
  }

  save_xml($doc, 'sitemap-index.xml');

  @file_get_contents('http://google.com/webmasters/sitemaps/ping?sitemap=' . urlencode(($request_type == 'SSL' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . 'sitemap-index.xml'), 'r');
  @file_get_contents('http://www.bing.com/webmaster/ping.aspx?siteMap=' . urlencode(($request_type == 'SSL' ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG . 'sitemap-index.xml'), 'r');

  tep_redirect(tep_href_link('sitemap-index.xml'));