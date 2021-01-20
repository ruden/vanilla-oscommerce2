<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

function quote_ecb_s1_currency($to, $from = DEFAULT_CURRENCY) {
  static $data;
  $rate = 1;

  if ($to == $from) {
    return $rate;
  }

  if (empty($data)) {
    $data = json_decode(file_get_contents('https://api.exchangeratesapi.io/latest?base=' . $from), true);
  }

  if (!empty($data)) {
    return isset($data['rates'][$to]) ? (string)$data['rates'][$to] : '';
  }

  return $rate;
}

function quote_ecb_s2_currency($to, $from = DEFAULT_CURRENCY) {
  static $data;
  $rate = 1;

  if ($to == $from) {
    return $rate;
  }

  if (empty($data)) {
    $data = json_decode(file_get_contents('https://api.exchangerate.host/latest?base=' . $from), true);
  }

  if (!empty($data) && $data['success'] === true) {
    return isset($data['rates'][$to]) ? (string)$data['rates'][$to] : '';
  }

  return $rate;
}
