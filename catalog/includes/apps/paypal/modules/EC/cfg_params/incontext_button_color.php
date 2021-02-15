<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_PayPal_EC_Cfg_incontext_button_color {
  public $default = '1';
  public $title;
  public $description;
  public $sort_order = 210;

  public function __construct() {
    global $OSCOM_PayPal;

    $this->title = $OSCOM_PayPal->getDef('cfg_ec_incontext_button_color_title');
    $this->description = $OSCOM_PayPal->getDef('cfg_ec_incontext_button_color_desc');
  }

  public function getSetField() {
    global $OSCOM_PayPal;

    $input = '<input type="radio" id="incontextButtonColorSelectionGold" name="incontext_button_color" value="1"' . (OSCOM_APP_PAYPAL_EC_INCONTEXT_BUTTON_COLOR == '1' ? ' checked="checked"' : '') . '><label for="incontextButtonColorSelectionGold">' . $OSCOM_PayPal->getDef('cfg_ec_incontext_button_color_gold') . '</label>' .
             '<input type="radio" id="incontextButtonColorSelectionBlue" name="incontext_button_color" value="2"' . (OSCOM_APP_PAYPAL_EC_INCONTEXT_BUTTON_COLOR == '2' ? ' checked="checked"' : '') . '><label for="incontextButtonColorSelectionBlue">' . $OSCOM_PayPal->getDef('cfg_ec_incontext_button_color_blue') . '</label>' .
             '<input type="radio" id="incontextButtonColorSelectionSilver" name="incontext_button_color" value="3"' . (OSCOM_APP_PAYPAL_EC_INCONTEXT_BUTTON_COLOR == '3' ? ' checked="checked"' : '') . '><label for="incontextButtonColorSelectionSilver">' . $OSCOM_PayPal->getDef('cfg_ec_incontext_button_color_silver') . '</label>';

    $result = <<<EOT
<div>
  <p>
    <label>{$this->title}</label>

    {$this->description}
  </p>

  <div id="incontextButtonColorSelection">
    {$input}
  </div>
</div>

<script>
$(function() {
  $('#incontextButtonColorSelection').buttonset();
});
</script>
EOT;

    return $result;
  }
}
