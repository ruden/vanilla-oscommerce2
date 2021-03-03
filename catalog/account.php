<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

if (!isset($_SESSION['customer_id'])) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link('login.php'));
}

require('includes/languages/' . $language . '/account.php');

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('account.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('account') > 0) {
  echo $messageStack->output('account');
}
?>

  <div class="col-lg-6 mb-5">

    <?php echo $oscTemplate->getContent('account'); ?>

  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');