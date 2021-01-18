<div class="col-auto">

  <label class="me-2 col-form-label-sm"><?php echo MODULE_HEADER_LANGUAGES_TEXT_LANGUAGES; ?></label>

  <?php
  foreach ($languages_array as $key => $value) {
    ?>

    <a href="<?php echo tep_href_link($PHP_SELF, tep_get_all_get_params() . 'language=' . $key, $request_type); ?>"><?php echo tep_image('includes/languages/' . $value['directory'] . '/images/' . $value['image'], $value['name']); ?></a>

    <?php
  }
  ?>

</div>
