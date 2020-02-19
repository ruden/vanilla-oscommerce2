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

    public function trail() {
      $trail_string = '<ol class="breadcrumb">';

      for ($i = 0, $n = sizeof($this->_trail); $i < $n; $i++) {
        if (isset($this->_trail[$i]['link']) && tep_not_null($this->_trail[$i]['link'])) {
          $trail_string .= '<li class="breadcrumb-item"><a href="' . $this->_trail[$i]['link'] . '">' . $this->_trail[$i]['title'] . '</a></li>';
        } else {
          $trail_string .= '<li class="breadcrumb-item active" aria-current="page">' . $this->_trail[$i]['title'] . '</li>';
        }
      }

      return $trail_string . '</nav>';
    }
  }