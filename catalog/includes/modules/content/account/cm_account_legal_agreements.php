<?php
/*
$Id$

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2021 osCommerce

Released under the GNU General Public License
*/

class cm_account_legal_agreements {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_TITLE;
    $this->description = MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_SORT_ORDER;
      $this->enabled = (MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_STATUS == 'True');
      
      if (!defined('ACCOUNT_LEGAL_AGREEMENTS') || ACCOUNT_LEGAL_AGREEMENTS == "false") {
        $this->enabled = false;

        $this->description = '<div class="secWarning">' . MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_ERROR_CONFIGURATION . '<br /><br />' . MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_ENABLE_CONFIGURATION . '</div>' . $this->description;
      }
    }
  }

  public function execute() {
    global $oscTemplate;

    $oscTemplate->_data[$this->group]['privacy_settings']['title'] = PRIVACY_SETTINGS_TITLE;
    $oscTemplate->_data[$this->group]['privacy_settings']['links']['legal_agreements'] = array('title' => MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_LINK_TITLE,
                                                                                             'link' => tep_href_link('ext/modules/content/account/legal_agreements.php'),
                                                                                             'icon' => 'plus');
  }

  public function isEnabled() {
    return $this->enabled;
  }

  public function check() {
    return defined('MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', NOW())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', NOW())");
  }

  public function remove() {
    tep_db_query("DELETE FROM configuration WHERE configuration_key IN ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_STATUS', 'MODULE_CONTENT_ACCOUNT_LEGAL_AGREEMENTS_SORT_ORDER');
  }
}
