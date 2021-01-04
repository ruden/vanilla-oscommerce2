<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require('includes/languages/' . $language . '/index.php');
require('includes/template_top.php');
?>

<?php echo $oscTemplate->getContent('index'); ?>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');