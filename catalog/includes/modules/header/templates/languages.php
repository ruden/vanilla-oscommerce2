<?php
  $languages_string = '';
  foreach ($lng->catalog_languages as $key => $value) {
  $languages_string .= ' <a href="' . tep_href_link($PHP_SELF, tep_get_all_get_params(array('language', 'currency')) . 'language=' . $key, $request_type) . '">' . tep_image('includes/languages/' .  $value['directory'] . '/images/' . $value['image'], $value['name']) . '</a> ';
  }

  echo MODULE_HEADER_LANGUAGES_TEXT_LANGUAGES . ' ' . $languages_string;