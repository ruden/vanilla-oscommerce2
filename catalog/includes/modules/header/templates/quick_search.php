<div class="m-auto col-5">

  <?php echo tep_draw_form('quick_find', tep_href_link('advanced_search_result.php', '', $request_type, false), 'get') . tep_draw_hidden_field('search_in_description', '1') . tep_hide_session_id(); ?>

  <div class="input-group">
    <?php echo tep_draw_input_field('keywords', '', 'class="form-control" placeholder="' . IMAGE_BUTTON_SEARCH . '"'); ?>

    <button class="btn btn-primary" type="submit">
      <svg class="svg-icon-search-form">
        <use href="#svg-icon-search"/>
      </svg>
    </button>
  </div>

  </form>

</div>