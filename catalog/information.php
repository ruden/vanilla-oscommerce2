<?php
/**
 *   $Id$
 *
 *   osCommerce, Open Source E-Commerce Solutions
 *   http://www.oscommerce.com
 *
 *   Copyright (c) 2020 osCommerce
 *
 *   Released under the GNU General Public License
 */

  require('includes/application_top.php');

  if (!isset($_GET['pages_id'])) {
    tep_redirect(tep_href_link('index.php'));
  }

  require(DIR_WS_LANGUAGES . $language . '/information.php');

  $information_query = tep_db_query("select ipc.pages_name, ipc.pages_content from information_pages ip, information_pages_content ipc where ip.pages_status = '1' and ip.pages_id = '" . (int)$_GET['pages_id'] . "' and ip.pages_id = ipc.pages_id and ipc.language_id = '" . (int)$languages_id . "'");
  $information = tep_db_fetch_array($information_query);

  if (empty($information)) {
    http_response_code(404);
  }

  require 'includes/template_top.php';

  if (empty($information)) {
?>

    <div class="contentContainer">
      <div class="contentText">
        <?php echo TEXT_INFORMATION_PAGES_NOT_FOUND; ?>
      </div>

      <div class="buttonSet">
        <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?></span>
      </div>
    </div>

<?php
  } else {
?>

    <h1><?php echo $information['pages_name']; ?></h1>

    <div class="contentContainer">
      <div class="contentText">
        <?php echo stripslashes($information['pages_content']); ?>
      </div>
    </div>

<?php
  }

  require(DIR_WS_INCLUDES . 'template_bottom.php');
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>