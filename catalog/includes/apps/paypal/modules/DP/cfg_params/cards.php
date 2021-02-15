<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_PayPal_DP_Cfg_cards {
  public $default = 'visa;mastercard;discover;amex;maestro';
  public $title;
  public $description;
  public $sort_order = 200;
  public $cards = array('visa' => 'Visa', 'mastercard' => 'MasterCard', 'discover' => 'Discover Card', 'amex' => 'American Express', 'maestro' => 'Maestro');

  public function __construct() {
    global $OSCOM_PayPal;

    $this->title = $OSCOM_PayPal->getDef('cfg_dp_cards_title');
    $this->description = $OSCOM_PayPal->getDef('cfg_dp_cards_desc');
  }

  public function getSetField() {
    $active = explode(';', OSCOM_APP_PAYPAL_DP_CARDS);

    $input = '';

    foreach ($this->cards as $key => $value) {
      $input .= '<input type="checkbox" id="cardsSelection' . ucfirst($key) . '" name="card_types[]" value="' . $key . '"' . (in_array($key, $active) ? ' checked="checked"' : '') . '><label for="cardsSelection' . ucfirst($key) . '">' . $value . '</label>';
    }

    $result = <<<EOT
<div>
  <p>
    <label>{$this->title}</label>

    {$this->description}
  </p>

  <div id="cardsSelection">
    {$input}
    <input type="hidden" name="cards" value="" />
  </div>
</div>

<script>
$(function() {
  $('#cardsSelection').buttonset();

  $('form[name="paypalConfigure"]').submit(function() {
    $('input[name="cards"]').val($('input[name="card_types[]"]:checked').map(function() {
      return this.value;
    }).get().join(';'));
  });
});
</script>
EOT;

    return $result;
  }
}
