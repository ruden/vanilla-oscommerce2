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

<div class="contentContainer">
  <div class="contentText">
    <div class="ui-widget infoBoxContainer" style=" width: 40%; float: right; padding: 0 0 10px 10px;">
      <div class="ui-widget-header infoBoxHeading"><?php echo BOX_INFORMATION_HEADING; ?></div>

      <div class="ui-widget-content infoBoxContents">
        <?php echo BOX_INFORMATION; ?>
      </div>
    </div>

    <?php echo TEXT_INFORMATION; ?>
  </div>

  <div class="buttonSet">
    <span class="buttonAction"><?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?></span>
  </div>
</div>

<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
