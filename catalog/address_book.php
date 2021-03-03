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

require('includes/languages/' . $language . '/address_book.php');

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('account.php'));
$breadcrumb->add(NAVBAR_TITLE_2, tep_href_link('address_book.php'));

require('includes/template_top.php');
?>

  <h1><?php echo HEADING_TITLE; ?></h1>

<?php
if ($messageStack->size('addressbook') > 0) {
  echo $messageStack->output('addressbook');
}
?>

  <div class="mb-5">

    <h2><?php echo PRIMARY_ADDRESS_TITLE; ?></h2>

    <p><?php echo tep_address_label($customer_id, $customer_default_address_id, true, ' ', '<br />'); ?></p>
    <p><?php echo PRIMARY_ADDRESS_DESCRIPTION; ?></p>

    <h2><?php echo ADDRESS_BOOK_TITLE; ?></h2>

    <div class="row">

      <?php
      $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from address_book where customers_id = '" . (int)$customer_id . "' order by firstname, lastname");
      while ($addresses = tep_db_fetch_array($addresses_query)) {
        $format_id = tep_get_address_format_id($addresses['country_id']);
        ?>

        <div class="col-md-4">
          <div class="fw-bold"><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?><?php if ($addresses['address_book_id'] == $customer_default_address_id) echo '<span class="badge bg-danger ms-2">' . PRIMARY_ADDRESS . '</span>'; ?></div>

          <p><?php echo tep_address_format($format_id, $addresses, true, ' ', '<br />'); ?></p>

          <div class="mb-3">
            <?php echo tep_draw_button(SMALL_IMAGE_BUTTON_DELETE, 'trash', tep_href_link('address_book_process.php', 'delete=' . $addresses['address_book_id']), 'btn-light') . ' ' . tep_draw_button(SMALL_IMAGE_BUTTON_EDIT, 'document', tep_href_link('address_book_process.php', 'edit=' . $addresses['address_book_id'])); ?>
          </div>
        </div>

        <?php
      }
      ?>

    </div>

    <p class="text-end"><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></p>

    <div class="btn-toolbar justify-content-between">

      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('account.php'), 'btn-light'); ?>

      <?php
      if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
        ?>

        <?php echo tep_draw_button(IMAGE_BUTTON_ADD_ADDRESS, 'home', tep_href_link('address_book_process.php'), 'btn-primary'); ?>

        <?php
      }
      ?>

    </div>

  </div>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');