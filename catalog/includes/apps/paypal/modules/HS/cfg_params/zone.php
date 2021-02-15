<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_PayPal_HS_Cfg_zone {
  public $default = '0';
  public $title;
  public $description;
  public $sort_order = 500;

  public function __construct() {
    global $OSCOM_PayPal;

    $this->title = $OSCOM_PayPal->getDef('cfg_hs_zone_title');
    $this->description = $OSCOM_PayPal->getDef('cfg_hs_zone_desc');
  }

  public function getSetField() {
    global $OSCOM_PayPal;

    $zone_class_array = array(array('id' => '0', 'text' => $OSCOM_PayPal->getDef('cfg_hs_zone_global')));

    $zone_class_query = tep_db_query("SELECT geo_zone_id, geo_zone_name FROM geo_zones ORDER BY geo_zone_name");
    while ($zone_class = tep_db_fetch_array($zone_class_query)) {
      $zone_class_array[] = array('id' => $zone_class['geo_zone_id'],
                                  'text' => $zone_class['geo_zone_name']);
    }

    $input = tep_draw_pull_down_menu('zone', $zone_class_array, OSCOM_APP_PAYPAL_HS_ZONE, 'id="inputHsZone"');

    $result = <<<EOT
<div>
  <p>
    <label for="inputHsZone">{$this->title}</label>

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
