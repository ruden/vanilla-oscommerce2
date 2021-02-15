<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_PayPal_LOGIN_Cfg_live_secret {
  public $default = '';
  public $title;
  public $description;
  public $sort_order = 300;

  public function __construct() {
    global $OSCOM_PayPal;

    $this->title = $OSCOM_PayPal->getDef('cfg_login_live_secret_title');
    $this->description = $OSCOM_PayPal->getDef('cfg_login_live_secret_desc');
  }

  public function getSetField() {
    $input = tep_draw_input_field('live_secret', OSCOM_APP_PAYPAL_LOGIN_LIVE_SECRET, 'id="inputLogInLiveSecret"');

    $result = <<<EOT
<div>
  <p>
    <label for="inputLogInLiveSecret">{$this->title}</label>

    {$this->description}
  </p>

  <div>
    {$input}
  </div>
</div>
EOT;

    return $result;
  }
}
