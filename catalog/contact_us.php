<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/contact_us.php');

if (isset($_GET['action']) && ($_GET['action'] == 'send') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  $error = false;

  $name = tep_db_prepare_input($_POST['name']);
  $email_address = tep_db_prepare_input($_POST['email']);
  $enquiry = tep_db_prepare_input($_POST['enquiry']);

  if (!tep_validate_email($email_address)) {
    $error = true;

    $messageStack->add('contact', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  }

  $actionRecorder = new actionRecorder('ar_contact_us', (isset($_SESSION['customer_id']) ? $customer_id : null), $name);
  if (!$actionRecorder->canPerform()) {
    $error = true;

    $actionRecorder->record(false);

    $messageStack->add('contact', sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES') ? (int)MODULE_ACTION_RECORDER_CONTACT_US_EMAIL_MINUTES : 15)));
  }

  if ($error == false) {
    tep_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, EMAIL_SUBJECT, $enquiry, $name, $email_address);

    $actionRecorder->record();

    tep_redirect(tep_href_link('contact_us.php', 'action=success'));
  }
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('contact_us.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('contact') > 0) {
  echo $messageStack->output('contact');
}

if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
  ?>

  <p><?php echo TEXT_SUCCESS; ?></p>

  <div class="text-end mb-3">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('index.php')); ?>
  </div>

  <?php
} else {
  ?>

  <?php echo tep_draw_form('contact_us', tep_href_link('contact_us.php', 'action=send'), 'post', '', true); ?>

  <div class="col-lg-6 mb-5">

    <div class="mb-3">
      <label for="name"><?php echo ENTRY_NAME; ?></label>
      <?php echo tep_draw_input_field('name', null, 'id="name" class="form-control" required'); ?>
    </div>
    <div class="mb-3">
      <label for="email"><?php echo ENTRY_EMAIL; ?></label>
      <?php echo tep_draw_input_field('email', null, 'id="email" class="form-control" required'); ?>
    </div>
    <div class="mb-3">
      <label for="enquiry"><?php echo ENTRY_ENQUIRY; ?></label>
      <?php echo tep_draw_textarea_field('enquiry', null, 'id="enquiry" class="form-control" rows="5"'); ?>
    </div>
    <div class="text-end">
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

  <?php
}

require('includes/template_bottom.php');
require('includes/application_bottom.php');