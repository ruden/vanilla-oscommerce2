<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_PayPal_EC_Cfg_checkout_flow {
  public $default = '1';
  public $title;
  public $description;
  public $sort_order = 200;

  public function __construct() {
    global $OSCOM_PayPal;

    $this->title = $OSCOM_PayPal->getDef('cfg_ec_checkout_flow_title');
    $this->description = $OSCOM_PayPal->getDef('cfg_ec_checkout_flow_desc');
  }

  public function getSetField() {
    global $OSCOM_PayPal;

    $input = '<input type="radio" id="checkoutFlowSelectionInContext" name="checkout_flow" value="1"' . (OSCOM_APP_PAYPAL_EC_CHECKOUT_FLOW == '1' ? ' checked="checked"' : '') . '><label for="checkoutFlowSelectionInContext">' . $OSCOM_PayPal->getDef('cfg_ec_checkout_flow_in_context') . '</label>' .
             '<input type="radio" id="checkoutFlowSelectionDefault" name="checkout_flow" value="0"' . (OSCOM_APP_PAYPAL_EC_CHECKOUT_FLOW == '0' ? ' checked="checked"' : '') . '><label for="checkoutFlowSelectionDefault">' . $OSCOM_PayPal->getDef('cfg_ec_checkout_flow_default') . '</label>';

    $result = <<<EOT
<div>
  <p>
    <label>{$this->title}</label>

    {$this->description}
  </p>

  <div id="checkoutFlowSelection">
    {$input}
  </div>
</div>

<script>
$(function() {
  $('#checkoutFlowSelection').buttonset();
});
</script>
EOT;

    return $result;
  }
}
