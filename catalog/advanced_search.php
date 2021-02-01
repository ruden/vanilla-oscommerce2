<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

require('includes/languages/' . $language . '/advanced_search.php');

$breadcrumb->add(NAVBAR_TITLE_1, tep_href_link('advanced_search.php'));

require('includes/template_top.php');
?>

  <script>
    function check_form() {
      var error_message = "<?php echo JS_ERROR; ?>";
      var error_found = false;
      var error_field;
      var keywords = document.advanced_search.keywords.value;
      var pfrom = document.advanced_search.pfrom.value;
      var pto = document.advanced_search.pto.value;
      var pfrom_float;
      var pto_float;

      if (((keywords === '') || (keywords.length < 1)) && ((pfrom === '') || (pfrom.length < 1)) && ((pto === '') || (pto.length < 1))) {
        error_message = error_message + "* <?php echo ERROR_AT_LEAST_ONE_INPUT; ?>\n";
        error_field = document.advanced_search.keywords;
        error_found = true;
      }

      if (pfrom.length > 0) {
        pfrom_float = parseFloat(pfrom);
        if (isNaN(pfrom_float)) {
          error_message = error_message + "* <?php echo ERROR_PRICE_FROM_MUST_BE_NUM; ?>\n";
          error_field = document.advanced_search.pfrom;
          error_found = true;
        }
      } else {
        pfrom_float = 0;
      }

      if (pto.length > 0) {
        pto_float = parseFloat(pto);
        if (isNaN(pto_float)) {
          error_message = error_message + "* <?php echo ERROR_PRICE_TO_MUST_BE_NUM; ?>\n";
          error_field = document.advanced_search.pto;
          error_found = true;
        }
      } else {
        pto_float = 0;
      }

      if ((pfrom.length > 0) && (pto.length > 0)) {
        if ((!isNaN(pfrom_float)) && (!isNaN(pto_float)) && (pto_float < pfrom_float)) {
          error_message = error_message + "* <?php echo ERROR_PRICE_TO_LESS_THAN_PRICE_FROM; ?>\n";
          error_field = document.advanced_search.pto;
          error_found = true;
        }
      }

      if (error_found === true) {
        alert(error_message);
        error_field.focus();
        return false;
      } else {
        return true;
      }
    }
  </script>

  <h1><?php echo HEADING_TITLE_1; ?></h1>

<?php
if ($messageStack->size('search') > 0) {
  echo $messageStack->output('search');
}
?>

<?php echo tep_draw_form('advanced_search', tep_href_link('advanced_search_result.php', '', 'SSL', false), 'get', 'onsubmit="return check_form(this);"') . tep_hide_session_id(); ?>

  <div class="col-lg-6 mb-5">
    <h2><?php echo HEADING_SEARCH_CRITERIA; ?></h2>

    <div class="mb-3">
      <?php echo tep_draw_input_field('keywords', '', 'class="form-control"') . tep_draw_hidden_field('search_in_description', '1'); ?>
    </div>

    <div class="btn-toolbar justify-content-between mb-3">
      <a href="advanced_search.php#help" data-bs-toggle="modal" data-bs-target="#help"><?php echo TEXT_SEARCH_HELP_LINK; ?></a>

      <?php echo tep_draw_button(IMAGE_BUTTON_SEARCH, 'search', null, 'btn-primary'); ?>
    </div>

    <div class="modal fade" id="help">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="text-end m-3">
            <a href="advanced_search.php#modal" class="text-danger" data-bs-dismiss="modal">
              <?php echo TEXT_CLOSE_WINDOW; ?>
            </a>
          </div>
          <div class="modal-body py-0">
            <p><?php echo TEXT_SEARCH_HELP; ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label for="categories_id" class="form-label"><?php echo ENTRY_CATEGORIES; ?></label>
      <?php echo tep_draw_pull_down_menu('categories_id', tep_get_categories(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES))), '', 'class="form-select" id="categories_id"'); ?>
    </div>
    <div class="form-check mb-3">
      <?php echo tep_draw_checkbox_field('inc_subcat', '1', true, 'class="form-check-input" id="inc_subcat"'); ?>
      <label for="inc_subcat" class="form-check-label"><?php echo ENTRY_INCLUDE_SUBCATEGORIES; ?></label>
    </div>
    <div class="mb-3">
      <label for="manufacturers_id" class="form-label"><?php echo ENTRY_MANUFACTURERS; ?></label>
      <?php echo tep_draw_pull_down_menu('manufacturers_id', tep_get_manufacturers(array(array('id' => '', 'text' => TEXT_ALL_MANUFACTURERS))), '', 'class="form-select" id="manufacturers_id"'); ?>
    </div>
    <div class="row mb-3">
      <div class="col">
        <label for="pfrom" class="form-label"><?php echo ENTRY_PRICE_FROM; ?></label>
        <?php echo tep_draw_input_field('pfrom', '', 'class="form-control" id="pfrom"'); ?>
      </div>
      <div class="col">
        <label for="pto" class="form-label"><?php echo ENTRY_PRICE_TO; ?></label>
        <?php echo tep_draw_input_field('pto', '', 'class="form-control" id="pto"'); ?>
      </div>
    </div>
  </div>

  </form>

<?php
require('includes/template_bottom.php');
require('includes/application_bottom.php');