<script>
  function checkFormProductFilters() {
    let error_message = "<?php echo JS_ERROR; ?>";
    let error_found = false;
    let error_field;
    const pfrom = document.product_filters.pfrom.value;
    const pto = document.product_filters.pto.value;
    let pfrom_float;
    let pto_float;

    if (pfrom.length > 0) {
      pfrom_float = parseFloat(pfrom);
      if (isNaN(pfrom_float)) {
        error_message = error_message + "* <?php echo MODULE_PRODUCT_FILTERS_PRICE_ERROR_PRICE_MIN_MUST_BE_NUM; ?>\n";
        error_field = document.product_filters.pfrom;
        error_found = true;
      }
    } else {
      pfrom_float = 0;
    }

    if (pto.length > 0) {
      pto_float = parseFloat(pto);
      if (isNaN(pto_float)) {
        error_message = error_message + "* <?php echo MODULE_PRODUCT_FILTERS_PRICE_ERROR_PRICE_MAX_MUST_BE_NUM; ?>\n";
        error_field = document.product_filters.pto;
        error_found = true;
      }
    } else {
      pto_float = 0;
    }

    if ((pfrom.length > 0) && (pto.length > 0)) {
      if ((!isNaN(pfrom_float)) && (!isNaN(pto_float)) && pto_float < pfrom_float) {
        error_message = error_message + "* <?php echo MODULE_PRODUCT_FILTERS_PRICE_ERROR_PRICE_MAX_LESS_THAN_PRICE_MIN; ?>\n";
        error_field = document.product_filters.pto;
        error_found = true;
      }
    }

    if (error_found) {
      alert(error_message);
      error_field.focus();
      return false;
    } else {
      return true;
    }
  }
</script>

<div class="border-1 border-bottom mb-3">
  <h6><?php echo MODULE_PRODUCT_FILTERS_PRICE_TITLE; ?></h6>

  <div class="mb-3">
    <div class="row gx-1">
      <div class="col">
        <?php echo tep_draw_input_field('pfrom', null, 'class="form-control me-0" placeholder="Min"'); ?>
      </div>
      <div class="col mb-3">
        <?php echo tep_draw_input_field('pto', null, 'class="form-control ms-0" placeholder="Max"'); ?>
      </div>

      <?php echo tep_draw_button(IMAGE_BUTTON_SEARCH, 'triangle-1-e', null, 'btn-primary', array('params' => 'onclick="return checkFormProductFilters();"')); ?>
    </div>
  </div>
</div>