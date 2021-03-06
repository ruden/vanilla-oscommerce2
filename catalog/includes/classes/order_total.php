<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class order_total {
  public $modules;

  // class constructor
  public function __construct() {
    global $language;

    if (defined('MODULE_ORDER_TOTAL_INSTALLED') && !empty(MODULE_ORDER_TOTAL_INSTALLED)) {
      $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED);

      foreach ($this->modules as $value) {
        include('includes/languages/' . $language . '/modules/order_total/' . $value);
        include('includes/modules/order_total/' . $value);

        $class = substr($value, 0, strrpos($value, '.'));
        $GLOBALS[$class] = new $class;
      }
    }
  }

  public function process() {
    $order_total_array = array();
    if (is_array($this->modules)) {
      foreach ($this->modules as $value) {
        $class = substr($value, 0, strrpos($value, '.'));
        if ($GLOBALS[$class]->enabled) {
          $GLOBALS[$class]->output = array();
          $GLOBALS[$class]->process();

          for ($i = 0, $n = sizeof($GLOBALS[$class]->output); $i < $n; $i++) {
            if (!empty($GLOBALS[$class]->output[$i]['title']) && !empty($GLOBALS[$class]->output[$i]['text'])) {
              $order_total_array[] = array('code' => $GLOBALS[$class]->code,
                                           'title' => $GLOBALS[$class]->output[$i]['title'],
                                           'text' => $GLOBALS[$class]->output[$i]['text'],
                                           'value' => $GLOBALS[$class]->output[$i]['value'],
                                           'sort_order' => $GLOBALS[$class]->sort_order);
            }
          }
        }
      }
    }

    return $order_total_array;
  }

  public function output() {
    $output_string = '';
    if (is_array($this->modules)) {
      foreach ($this->modules as $value) {
        $class = substr($value, 0, strrpos($value, '.'));
        if ($GLOBALS[$class]->enabled) {
          $size = sizeof($GLOBALS[$class]->output);
          for ($i = 0; $i < $size; $i++) {
            $output_string .= '<tr>
                                 <td class="text-end w-100"><span class="me-2">' . $GLOBALS[$class]->output[$i]['title'] . '</span></td>
                                 <td class="text-end">' . $GLOBALS[$class]->output[$i]['text'] . '</td>
                              </tr>';
          }
        }
      }
    }

    return $output_string;
  }
}
