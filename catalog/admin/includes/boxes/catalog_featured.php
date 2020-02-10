<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  foreach ( $cl_box_groups as &$group ) {
    if ( $group['heading'] == BOX_HEADING_CATALOG ) {
      $group['apps'][] = array('code' => 'featured.php',
                               'title' => BOX_CATALOG_FEATURED_PRODUCTS,
                               'link' => tep_href_link('featured.php'));

      break;
    }
  }