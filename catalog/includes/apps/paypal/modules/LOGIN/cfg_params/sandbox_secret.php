<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_PayPal_LOGIN_Cfg_sandbox_secret {
  public $default = '';
  public $sort_order = 500;

  public function __construct() {
    global $OSCOM_PayPal;

    $this->title = $OSCOM_PayPal->getDef('cfg_login_sandbox_secret_title');
    $this->description = $OSCOM_PayPal->getDef('cfg_login_sandbox_secret_desc');
  }

  public function getSetField() {
    $input = tep_draw_input_field('sandbox_secret', OSCOM_APP_PAYPAL_LOGIN_SANDBOX_SECRET, 'id="inputLogInSandboxSecret"');

    $result = <<<EOT
<div>
  <p>
    <label for="inputLogInSandboxSecret">{$this->title}</label>

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
