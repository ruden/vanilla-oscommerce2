<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class OSCOM_PayPal_PS_Cfg_prepare_order_status_id {
  public $default = '0';
  public $title;
  public $description;
  public $sort_order = 400;

  public function __construct() {
    global $OSCOM_PayPal;

    $this->title = $OSCOM_PayPal->getDef('cfg_ps_prepare_order_status_id_title');
    $this->description = $OSCOM_PayPal->getDef('cfg_ps_prepare_order_status_id_desc');

    if (!defined('OSCOM_APP_PAYPAL_PS_PREPARE_ORDER_STATUS_ID')) {
      $check_query = tep_db_query("SELECT orders_status_id FROM orders_status WHERE orders_status_name = 'Preparing [PayPal Standard]' LIMIT 1");

      if (tep_db_num_rows($check_query) < 1) {
        $status_query = tep_db_query("SELECT MAX(orders_status_id) AS status_id FROM orders_status");
        $status = tep_db_fetch_array($status_query);

        $status_id = $status['status_id'] + 1;

        $languages = tep_get_languages();

        foreach ($languages as $lang) {
          tep_db_query("insert into orders_status (orders_status_id, language_id, orders_status_name) values ('" . $status_id . "', '" . $lang['id'] . "', 'Preparing [PayPal Standard]')");
        }

        $flags_query = tep_db_query("describe orders_status public_flag");
        if (tep_db_num_rows($flags_query) == 1) {
          tep_db_query("update orders_status set public_flag = 0 and downloads_flag = 0 where orders_status_id = '" . (int)$status_id . "'");
        }
      } else {
        $check = tep_db_fetch_array($check_query);

        $status_id = $check['orders_status_id'];
      }
    } else {
      $status_id = OSCOM_APP_PAYPAL_PS_PREPARE_ORDER_STATUS_ID;
    }

    $this->default = $status_id;
  }

  public function getSetField() {
    global $OSCOM_PayPal, $languages_id;

    $statuses_array = array(array('id' => '0', 'text' => $OSCOM_PayPal->getDef('cfg_ps_prepare_order_status_id_default')));

    $statuses_query = tep_db_query("select orders_status_id, orders_status_name from orders_status where language_id = '" . (int)$languages_id . "' order by orders_status_name");
    while ($statuses = tep_db_fetch_array($statuses_query)) {
      $statuses_array[] = array('id' => $statuses['orders_status_id'],
                                'text' => $statuses['orders_status_name']);
    }

    $input = tep_draw_pull_down_menu('prepare_order_status_id', $statuses_array, OSCOM_APP_PAYPAL_PS_PREPARE_ORDER_STATUS_ID, 'id="inputPsPrepareOrderStatusId"');

    $result = <<<EOT
<div>
  <p>
    <label for="inputPsPrepareOrderStatusId">{$this->title}</label>

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
