<?php
$currencies_array = array();

foreach ($currencies->currencies as $key => $value) {
  $currencies_array[] = array('id' => $key, 'text' => $value['title']);
}

$hidden_get_variables = '';
foreach ($_GET as $key => $value) {
  if (is_string($value) && ($key != 'currency') && ($key != tep_session_name()) && ($key != 'x') && ($key != 'y')) {
    $hidden_get_variables .= tep_draw_hidden_field($key, $value);
  }
}

echo MODULE_HEADER_CURRENCIES_TEXT_CURRENCIES . ' ' . tep_draw_form('currencies', tep_href_link($PHP_SELF, '', $request_type, false), 'get') .
  '    ' . tep_draw_pull_down_menu('currency', $currencies_array, $currency, 'onchange="this.form.submit();" style="width: 100%"') . $hidden_get_variables . tep_hide_session_id() . '</form>';

