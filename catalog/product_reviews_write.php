<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/product_reviews_write.php');

if (!isset($_SESSION['customer_id'])) {
  $navigation->set_snapshot();
  tep_redirect(tep_href_link('login.php'));
}

$valid_product = false;
if (isset($_GET['products_id'])) {
  $product_info_query = tep_db_query("select p.products_id, p.products_model, p.products_image, p.products_price, p.products_tax_class_id, pd.products_name, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price from products p left join specials s on p.products_id = s.products_id, products_description pd where p.products_id = '" . (int)$_GET['products_id'] . "' and p.products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . (int)$languages_id . "'");
  if (tep_db_num_rows($product_info_query)) {
    $valid_product = true;

    $product_info = tep_db_fetch_array($product_info_query);
  }
}

if ($valid_product == false) {
  tep_redirect(tep_href_link('product_info.php', 'products_id=' . $_GET['products_id']));
}

$customer = tep_db_fetch_array(tep_db_query("select customers_firstname, customers_lastname from customers where customers_id = '" . (int)$customer_id . "'"));
$customer_name = tep_output_string_protected($customer['customers_firstname'] . ' ' . $customer['customers_lastname']);

if (isset($_GET['action']) && ($_GET['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] == $sessiontoken)) {
  $rating = tep_db_prepare_input($_POST['rating']);
  $review = tep_db_prepare_input($_POST['review']);

  $error = false;
  if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
    $error = true;

    $messageStack->add('review', JS_REVIEW_TEXT);
  }

  if (($rating < 1) || ($rating > 5)) {
    $error = true;

    $messageStack->add('review', JS_REVIEW_RATING);
  }

  $actionRecorder = new actionRecorder('ar_write_review', $customer_id, $customer_name);
  if (!$actionRecorder->canPerform()) {
    $error = true;

    $actionRecorder->record(false);

    $messageStack->add('review', sprintf(ERROR_ACTION_RECORDER, (defined('MODULE_ACTION_RECORDER_WRITE_REVIEW_MINUTES') ? (int)MODULE_ACTION_RECORDER_WRITE_REVIEW_MINUTES : 5)));
  }

  if ($error == false) {
    tep_db_query("insert into reviews (products_id, customers_id, customers_name, reviews_rating, date_added, reviews_text) values ('" . (int)$product_info['products_id'] . "', '" . (int)$customer_id . "', '" . tep_db_input($customer['customers_firstname']) . ' ' . tep_db_input($customer['customers_lastname']) . "', '" . tep_db_input($rating) . "', now(), '" . tep_db_input($review) . "')");

    $actionRecorder->record();

    tep_redirect(tep_href_link('product_reviews_write.php', 'action=success&products_id=' . $product_info['products_id']));
  }
}

$breadcrumb->add(NAVBAR_TITLE, tep_href_link('product_reviews_write.php', tep_get_all_get_params()));

require('includes/template_top.php');
?>

  <h1><?php echo $product_info['products_name']; ?></h1>

<?php
if ($messageStack->size('review') > 0) {
  echo $messageStack->output('review');
}

if (isset($_GET['action']) && ($_GET['action'] == 'success')) {
  ?>

  <p><?php echo TEXT_REVIEW_RECEIVED; ?></p>

  <div class="text-end mb-3">
    <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', tep_href_link('product_info.php', 'products_id=' . $_GET['products_id'])); ?>
  </div>

  <?php
} else {
  ?>

  <script>
    function checkForm() {
      var error = 0;
      var error_message = "<?php echo JS_ERROR; ?>";

      var review = document.product_reviews_write.review.value;

      if (review.length < <?php echo REVIEW_TEXT_MIN_LENGTH; ?>) {
        error_message = error_message + "<?php echo JS_REVIEW_TEXT; ?>";
        error = 1;
      }

      if ((document.product_reviews_write.rating[0].checked) || (document.product_reviews_write.rating[1].checked) || (document.product_reviews_write.rating[2].checked) || (document.product_reviews_write.rating[3].checked) || (document.product_reviews_write.rating[4].checked)) {
      } else {
        error_message = error_message + "<?php echo JS_REVIEW_RATING; ?>";
        error = 1;
      }

      if (error === 1) {
        alert(error_message);
        return false;
      } else {
        return true;
      }
    }
  </script>

  <?php echo tep_draw_form('product_reviews_write', tep_href_link('product_reviews_write.php', 'action=process&products_id=' . $product_info['products_id']), 'post', 'onsubmit="return checkForm();"', true); ?>

  <div class="col-lg-6 mb-5">

    <div class="mb-3">
      <label><?php echo SUB_TITLE_FROM; ?></label>
      <strong><?php echo $customer_name; ?></strong>
    </div>
    <div class="mb-3">
      <label for="review"><?php echo SUB_TITLE_REVIEW; ?></label>
      <?php echo tep_draw_textarea_field('review', '', 'id="review" class="form-control" rows="5" placeholder="' . TEXT_NO_HTML . '" required'); ?>
    </div>
    <div class="mb-3">
      <label for="rating"><?php echo SUB_TITLE_RATING; ?></label>
      <span class="px-1 text-danger"><?php echo TEXT_BAD; ?></span>
      <?php echo tep_draw_radio_field('rating', '1') . ' ' . tep_draw_radio_field('rating', '2') . ' ' . tep_draw_radio_field('rating', '3') . ' ' . tep_draw_radio_field('rating', '4') . ' ' . tep_draw_radio_field('rating', '5'); ?>
      <span class="px-1 text-danger"><?php echo TEXT_GOOD; ?></span>
    </div>
    <div class="btn-toolbar justify-content-between">
      <?php echo tep_draw_button(IMAGE_BUTTON_BACK, 'triangle-1-w', tep_href_link('product_info.php', 'products_id=' . $product_info['products_id']), 'btn-light'); ?>
      <?php echo tep_draw_button(IMAGE_BUTTON_CONTINUE, 'triangle-1-e', null, 'btn-primary'); ?>
    </div>

  </div>

  </form>

  <?php
}

require('includes/template_bottom.php');
require('includes/application_bottom.php');
