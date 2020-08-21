<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if (!isset($_GET['pages_id'])) {
  tep_redirect(tep_href_link('index.php'));
}

require('includes/languages/' . $language . '/information.php');

$information_query = tep_db_query("select ipc.* from information_pages ip, information_pages_content ipc where ip.pages_status = '1' and ip.pages_id = '" . (int)$_GET['pages_id'] . "' and ip.pages_id = ipc.pages_id and ipc.language_id = '" . (int)$languages_id . "'");
$information = tep_db_fetch_array($information_query);

if (empty($information)) {
  http_response_code(404);
  $breadcrumb->add(HEADING_TITLE);
} else {
  $breadcrumb->add($information['pages_name'], tep_href_link('information.php', 'pages_id=' . $information['pages_id']));
}

require('includes/template_top.php');

if (empty($information)) {
  ?>

  <div class="mb-5">
    <p><?php echo TEXT_INFORMATION_PAGES_NOT_FOUND; ?></p>

    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php'), 'btn-light'); ?>
  </div>

  <?php
} else {
  ?>

  <h1><?php echo $information['pages_name']; ?></h1>

  <div class="mb-5">
    <?php echo stripslashes($information['pages_content']); ?>
  </div>

  <?php
}

require('includes/template_bottom.php');
require('includes/application_bottom.php');