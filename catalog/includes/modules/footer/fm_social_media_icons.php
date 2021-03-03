<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class fm_social_media_icons {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_FOOTER_SOCIAL_MEDIA_ICONS_TITLE;
    $this->description = MODULE_FOOTER_SOCIAL_MEDIA_ICONS_DESCRIPTION;

    if (defined('MODULE_FOOTER_SOCIAL_MEDIA_ICONS_STATUS')) {
      $this->sort_order = MODULE_FOOTER_SOCIAL_MEDIA_ICONS_SORT_ORDER;
      $this->enabled = (MODULE_FOOTER_SOCIAL_MEDIA_ICONS_STATUS == 'True');

      $this->description .= '<br><br><div align="center">' . tep_draw_button(MODULE_FOOTER_SOCIAL_MEDIA_ICONS_ADD_ICONS, 'document', tep_href_link('modules.php', 'set=social_media&list=new')) . '</div>';
    }
  }

  public function execute() {
    global $language, $oscTemplate;

    $sm_array = explode(';', MODULE_SOCIAL_MEDIA_INSTALLED);

    $social_media = array();

    foreach ($sm_array as $sm) {
      $class = substr($sm, 0, strrpos($sm, '.'));

      if (!class_exists($class)) {
        include('includes/languages/' . $language . '/modules/social_media/' . $sm);
        include('includes/modules/social_media/' . $class . '.php');
      }

      $smi = new $class();

      if ($smi->isEnabled()) {
        $social_media[] = $smi->getOutput();
      }
    }

    if (!empty($social_media)) {
      ob_start();
      include('includes/modules/' . $this->group . '/templates/social_media_icons.php');

      $oscTemplate->addBlock(ob_get_clean(), $this->group);
    }
  }

  public function isEnabled() {
    if (defined('MODULE_SOCIAL_MEDIA_INSTALLED') && !empty(MODULE_SOCIAL_MEDIA_INSTALLED)) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_FOOTER_SOCIAL_MEDIA_ICONS_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Module', 'MODULE_FOOTER_SOCIAL_MEDIA_ICONS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', NOW())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_FOOTER_SOCIAL_MEDIA_ICONS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', NOW())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_FOOTER_SOCIAL_MEDIA_ICONS_STATUS', 'MODULE_FOOTER_SOCIAL_MEDIA_ICONS_SORT_ORDER');
  }
}
