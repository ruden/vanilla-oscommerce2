<div class="d-flex justify-content-end align-items-center mb-5">
  <label for="sorting" class="me-2"><?php echo TEXT_SORT_PRODUCTS; ?></label>

    <?php echo tep_draw_form('sorting', tep_href_link($PHP_SELF, tep_get_all_get_params()), 'get') . tep_draw_pull_down_menu('sort', $sort_array, (isset($_GET['sort']) ? $_GET['sort'] : ''), 'onchange="this.form.submit();" id="sorting" class="form-select form-select-sm w-auto"') . $hidden_get_params . tep_hide_session_id(); ?>
    </form>

</div>