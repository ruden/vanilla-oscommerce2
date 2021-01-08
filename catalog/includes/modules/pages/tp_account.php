<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  class tp_account {
    public $group = 'account';

    public function prepare() {
      global $oscTemplate;

      $oscTemplate->_data[$this->group] = array('account' => array('title' => MY_ACCOUNT_TITLE,
                                                                   'links' => array('edit' => array('title' => MY_ACCOUNT_INFORMATION,
                                                                                                    'link' => tep_href_link('account_edit.php'),
                                                                                                    'icon' => 'person'),
                                                                                    'address_book' => array('title' => MY_ACCOUNT_ADDRESS_BOOK,
                                                                                                            'link' => tep_href_link('address_book.php'),
                                                                                                            'icon' => 'home'),
                                                                                    'password' => array('title' => MY_ACCOUNT_PASSWORD,
                                                                                                        'link' => tep_href_link('account_password.php'),
                                                                                                        'icon' => 'key'))),
                                                'orders' => array('title' => MY_ORDERS_TITLE,
                                                                  'links' => array('history' => array('title' => MY_ORDERS_VIEW,
                                                                                                      'link' => tep_href_link('account_history.php'),
                                                                                                      'icon' => 'cart'))),
                                                'notifications' => array('title' => EMAIL_NOTIFICATIONS_TITLE,
                                                                         'links' => array('newsletters' => array('title' => EMAIL_NOTIFICATIONS_NEWSLETTERS,
                                                                                                                 'link' => tep_href_link('account_newsletters.php'),
                                                                                                                 'icon' => 'mail-closed'),
                                                                                          'products' => array('title' => EMAIL_NOTIFICATIONS_PRODUCTS,
                                                                                                              'link' => tep_href_link('account_notifications.php'),
                                                                                                              'icon' => 'heart'))));
    }

    public function build() {
      global $oscTemplate;

      $output = '';

      foreach ($oscTemplate->_data[$this->group] as $group) {
        $output .= '<h2>' . $group['title'] . '</h2>' .
                   '  <ul class="list-unstyled">';

        foreach ($group['links'] as $entry) {
          $output .= '    <li>';

          if (isset($entry['icon'])) {
            $output .= '<span class="ui-icon ui-icon-' . $entry['icon'] . '"></span>';
          }

          $output .= '<a href="' . $entry['link'] . '">' . $entry['title'] . '</a></li>';
        }

        $output .= '  </ul>';
      }

      $oscTemplate->addContent($output, $this->group);
    }
  }
