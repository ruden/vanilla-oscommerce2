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

// needs to be included earlier to set the success message in the messageStack
require('includes/languages/' . $language . '/account_newsletters.php');

$newsletter_query = tep_db_query("select customers_newsletter from customers where customers_id = '" . (int)$customer_id . "'");
$newsletter = tep_db_fetch_array($newsletter_query);

if (isset($_POST['action']) && ($_POST['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  if (isset($_POST['newsletter_general']) && is_numeric($_POST['newsletter_general'])) {
    $newsletter_general = tep_db_prepare_input($_POST['newsletter_general']);
  } else {
    $newsletter_general = '0';
  }

  if ($newsletter_general != $newsletter['customers_newsletter']) {
    $newsletter_general = (($newsletter['customers_newsletter'] == '1') ? '0' : '1');

    tep_db_query("update customers set customers_newsletter = '" . (int)$newsletter_general . "' where customers_id = '" . (int)$customer_id . "'");
  }

  $messageStack->add_session('account', SUCCESS_NEWSLETTER_UPDATED, 'success');

  tep_redirect(tep_href_link('account.php'));
}

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('account_newsletters.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php echo tep_draw_form('account_newsletter', tep_href_link('account_newsletters.php'), 'post', '', true) . tep_draw_hidden_field('action', 'process'); ?>

  <div class="col-lg-6 mb-5">

    <p><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER_DESCRIPTION; ?></p>

    <div class="mb-3">
      <div class="form-check">
        <?php echo tep_draw_checkbox_field('newsletter_general', '1', ($newsletter['customers_newsletter'] == '1' ? true : false), 'id="newsletter_general" class="form-check-input"'); ?>
        <label class="form-check-label fw-bold" for="newsletter_general"><?php echo MY_NEWSLETTERS_GENERAL_NEWSLETTER; ?></label>
      </div>
    </div>
    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php'), 'btn-light'); ?>
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');