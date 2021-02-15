<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_PayPal_LOGIN_Cfg_live_client_id {
  public $default = '';
  public $title;
  public $description;
  public $sort_order = 200;

  public function __construct() {
    global $OSCOM_PayPal;

    $this->title = $OSCOM_PayPal->getDef('cfg_login_live_client_id_title');
    $this->description = $OSCOM_PayPal->getDef('cfg_login_live_client_id_desc');
  }

  public function getSetField() {
    $input = tep_draw_input_field('live_client_id', OSCOM_APP_PAYPAL_LOGIN_LIVE_CLIENT_ID, 'id="inputLogInLiveClientId"');

    $result = <<<EOT
<div>
  <p>
    <label for="inputLogInLiveClientId">{$this->title}</label>

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
