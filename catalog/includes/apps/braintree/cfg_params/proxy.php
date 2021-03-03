<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2021 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_Braintree_Cfg_proxy {
  public $default = '';
  public $title;
  public $description;
  public $sort_order = 400;

  public function __construct() {
    global $OSCOM_Braintree;

    $this->title = $OSCOM_Braintree->getDef('cfg_proxy_title');
    $this->description = $OSCOM_Braintree->getDef('cfg_proxy_desc');
  }

  public function getSetField() {
    $input = tep_draw_input_field('proxy', OSCOM_APP_PAYPAL_BRAINTREE_PROXY, 'id="inputProxy"');

    $result = <<<EOT
<div>
  <p>
    <label for="inputProxy">{$this->title}</label>

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
