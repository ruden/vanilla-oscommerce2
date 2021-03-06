<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class fm_card_acceptance {
  public $code;
  public $group;
  public $title;
  public $description;
  public $sort_order;
  public $enabled = false;

  public function __construct() {
    $this->code = get_class($this);
    $this->group = basename(dirname(__FILE__));

    $this->title = MODULE_FOOTER_CARD_ACCEPTANCE_TITLE;
    $this->description = MODULE_FOOTER_CARD_ACCEPTANCE_DESCRIPTION;

    if ($this->check()) {
      $this->sort_order = MODULE_FOOTER_CARD_ACCEPTANCE_SORT_ORDER;
      $this->enabled = (MODULE_FOOTER_CARD_ACCEPTANCE_STATUS == 'True');
    }
  }

  public function execute() {
    global $oscTemplate;

    ob_start();
    include('includes/modules/' . $this->group . '/templates/card_acceptance.php');

    $oscTemplate->addBlock(ob_get_clean(), $this->group);
  }

  public function isEnabled() {
    if (defined('MODULE_FOOTER_CARD_ACCEPTANCE_LOGOS') && !empty(MODULE_FOOTER_CARD_ACCEPTANCE_LOGOS)) {
      return $this->enabled;
    }

    return false;
  }

  public function check() {
    return defined('MODULE_FOOTER_CARD_ACCEPTANCE_STATUS');
  }

  public function install() {
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) VALUES ('Enable Card Acceptance Module', 'MODULE_FOOTER_CARD_ACCEPTANCE_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('Logos', 'MODULE_FOOTER_CARD_ACCEPTANCE_LOGOS', 'paypal_horizontal_large.png;visa.png;mastercard_transparent.png;american_express.png;maestro_transparent.png', 'The card acceptance logos to show.', '6', '0', 'fm_card_acceptance_show_logos', 'fm_card_acceptance_edit_logos(', now())");
    tep_db_query("INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) VALUES ('Sort Order', 'MODULE_FOOTER_CARD_ACCEPTANCE_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
  }

  public function remove() {
    tep_db_query("delete from configuration where configuration_key in ('" . implode("', '", $this->keys()) . "')");
  }

  public function keys() {
    return array('MODULE_FOOTER_CARD_ACCEPTANCE_STATUS', 'MODULE_FOOTER_CARD_ACCEPTANCE_LOGOS', 'MODULE_FOOTER_CARD_ACCEPTANCE_CONTENT_PLACEMENT', 'MODULE_FOOTER_CARD_ACCEPTANCE_SORT_ORDER');
  }
}

function fm_card_acceptance_show_logos($text) {
  $output = '';

  if (!empty($text)) {
    $output = '<ul style="list-style-type: none; margin: 0; padding: 5px; margin-bottom: 10px;">';

    foreach (explode(';', $text) as $card) {
      $output .= '<li style="padding: 2px;">' . tep_image(DIR_WS_CATALOG . 'images/card_acceptance/' . basename($card), basename($card)) . '</li>';
    }

    $output .= '</ul>';
  }

  return $output;
}

function fm_card_acceptance_edit_logos($values, $key) {
  $files_array = array();

  if ($dir = @dir(DIR_FS_CATALOG . 'images/card_acceptance')) {
    while ($file = $dir->read()) {
      if (!is_dir(DIR_FS_CATALOG . 'images/card_acceptance/' . $file)) {
        if (in_array(substr($file, strrpos($file, '.') + 1), array('gif', 'jpg', 'png'))) {
          $files_array[] = $file;
        }
      }
    }

    sort($files_array);

    $dir->close();
  }

  $values_array = !empty($values) ? explode(';', $values) : array();

  $output = '<h3>' . MODULE_FOOTER_CARD_ACCEPTANCE_SHOWN_CARDS . '</h3>' .
            '<ul id="ca_logos" style="list-style-type: none; margin: 0; padding: 5px; margin-bottom: 10px;">';

  foreach ($values_array as $file) {
    $output .= '<li style="padding: 2px;">' . tep_image(DIR_WS_CATALOG . 'images/card_acceptance/' . $file, $file) . tep_draw_hidden_field('fm_card_acceptance_logos[]', $file) . '</li>';
  }

  $output .= '</ul>';

  $output .= '<h3>' . MODULE_FOOTER_CARD_ACCEPTANCE_NEW_CARDS . '</h3><ul id="new_ca_logos" style="list-style-type: none; margin: 0; padding: 5px; margin-bottom: 10px;">';

  foreach ($files_array as $file) {
    if (!in_array($file, $values_array)) {
      $output .= '<li style="padding: 2px;">' . tep_image(DIR_WS_CATALOG . 'images/card_acceptance/' . $file, $file) . tep_draw_hidden_field('fm_card_acceptance_logos[]', $file) . '</li>';
    }
  }

  $output .= '</ul>';

  $output .= tep_draw_hidden_field('configuration[' . $key . ']', '', 'id="ca_logo_cards"');

  $drag_here_li = '<li id="caLogoEmpty" style="background-color: #fcf8e3; border: 1px #faedd0 solid; color: #a67d57; padding: 5px;">' . addslashes(MODULE_FOOTER_CARD_ACCEPTANCE_DRAG_HERE) . '</li>';

  $output .= <<<EOD
  <script>
  $(function() {
    var drag_here_li = '{$drag_here_li}';
  
    if ( $('#ca_logos li').length < 1 ) {
      $('#ca_logos').append(drag_here_li);
    }
  
    $('#ca_logos').sortable({
      connectWith: '#new_ca_logos',
      items: 'li:not("#caLogoEmpty")',
      stop: function (event, ui) {
        if ( $('#ca_logos li').length < 1 ) {
          $('#ca_logos').append(drag_here_li);
        } else if ( $('#caLogoEmpty').length > 0 ) {
          $('#caLogoEmpty').remove();
        }
      }
    });
  
    $('#new_ca_logos').sortable({
      connectWith: '#ca_logos',
      stop: function (event, ui) {
        if ( $('#ca_logos li').length < 1 ) {
          $('#ca_logos').append(drag_here_li);
        } else if ( $('#caLogoEmpty').length > 0 ) {
          $('#caLogoEmpty').remove();
        }
      }
    });
  
    $('#ca_logos, #new_ca_logos').disableSelection();
  
    $('form[name="modules"]').submit(function(event) {
      var ca_selected_cards = '';
  
      if ( $('#ca_logos li').length > 0 ) {
        $('#ca_logos li input[name="fm_card_acceptance_logos[]"]').each(function() {
          ca_selected_cards += $(this).attr('value') + ';';
        });
      }
  
      if (ca_selected_cards.length > 0) {
        ca_selected_cards = ca_selected_cards.substring(0, ca_selected_cards.length - 1);
      }
  
      $('#ca_logo_cards').val(ca_selected_cards);
    });
  });
  </script>
EOD;

  return $output;
}
