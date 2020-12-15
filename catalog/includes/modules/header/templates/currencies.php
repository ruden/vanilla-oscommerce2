<div class="col-auto d-flex align-items-center">
  <label for="currency" class="me-2 col-form-label-sm"><?php echo MODULE_HEADER_CURRENCIES_TEXT_CURRENCIES; ?></label>

  <?php echo tep_draw_form('currencies', tep_href_link($PHP_SELF, '', $request_type, false), 'get') . $hidden_get_variables . tep_hide_session_id(); ?>

  <?php echo tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onchange="this.form.submit();" id="currency" class="form-select form-select-sm w-auto"'); ?>

  </form>
</div>


