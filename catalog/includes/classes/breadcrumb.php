<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

class breadcrumb {
  public $_trail;

  public function __construct() {
    $this->reset();
  }

  public function reset() {
    $this->_trail = array();
  }

  public function add($title, $link = '') {
    $this->_trail[] = array('title' => $title, 'link' => $link);
  }

  public function trail($separator = ' - ') {
    $trail_string = '';

    for ($i = 0, $n = sizeof($this->_trail); $i < $n; $i++) {
      if (isset($this->_trail[$i]['link']) && !empty($this->_trail[$i]['link'])) {
        if ($i == 0) {
          $trail_string .= '<li class="list-inline-item"><a href="' . $this->_trail[$i]['link'] . '">' . $this->_trail[$i]['title'] . '</a></li>';
        } else {
          $trail_string .= '<li class="list-inline-item mx-2"><a href="' . $this->_trail[$i]['link'] . '">' . $this->_trail[$i]['title'] . '</a></li>';
        }
      } else {
        $trail_string .= '<li class="list-inline-item mx-2">' . $this->_trail[$i]['title'] . '</li>';
      }

      if (($i + 1) < $n) {
        $trail_string .= $separator;
      }
    }

    return $trail_string;
  }
}