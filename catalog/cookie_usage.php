<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/cookie_usage.php');

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('cookie_usage.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

  <div class="mb-5">
    <div class="mb-3">
      <p><?php echo TEXT_INFORMATION; ?></p>

      <h5><?php echo BOX_INFORMATION_HEADING; ?></h5>

      <p><?php echo BOX_INFORMATION; ?></p>
    </div>

    <div class="text-end">
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?>
    </div>
  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');