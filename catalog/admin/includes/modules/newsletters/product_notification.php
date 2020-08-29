<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

class product_notification {
  public $show_choose_audience;
  public $title;
  public $content;

  public function __construct($title, $content) {
    $this->show_choose_audience = true;
    $this->title = $title;
    $this->content = $content;
  }

  public function choose_audience() {
    global $languages_id;

    $products_array = array();
    $products_query = tep_db_query("select pd.products_id, pd.products_name from products p, products_description pd where pd.language_id = '" . (int)$languages_id . "' and pd.products_id = p.products_id and p.products_status = '1' order by pd.products_name");
    while ($products = tep_db_fetch_array($products_query)) {
      $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name']);
    }

    $choose_audience_string = '<script>
function mover(move) {
  if (move === \'remove\') {
    for (x=0; x<(document.notifications.products.length); x++) {
      if (document.notifications.products.options[x].selected) {
        with(document.notifications.elements[\'chosen[]\']) {
          options[options.length] = new Option(document.notifications.products.options[x].text,document.notifications.products.options[x].value);
        }
        document.notifications.products.options[x] = null;
        x = -1;
      }
    }
  }
  if (move === \'add\') {
    for (x=0; x<(document.notifications.elements[\'chosen[]\'].length); x++) {
      if (document.notifications.elements[\'chosen[]\'].options[x].selected) {
        with(document.notifications.products) {
          options[options.length] = new Option(document.notifications.elements[\'chosen[]\'].options[x].text,document.notifications.elements[\'chosen[]\'].options[x].value);
        }
        document.notifications.elements[\'chosen[]\'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}

function selectAll(FormName, SelectBox) {
  temp = "document." + FormName + ".elements[\'" + SelectBox + "\']";
  Source = eval(temp);

  for (x=0; x<(Source.length); x++) {
    Source.options[x].selected = "true";
  }

  if (x<1) {
    alert(\'' . JS_PLEASE_SELECT_PRODUCTS . '\');
    return false;
  } else {
    return true;
  }
}
</script>';

    $global_button = tep_draw_button(BUTTON_GLOBAL, 'circle-triangle-n', tep_href_link('newsletters.php', 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm&global=true'), 'primary');

    $cancel_button = tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('newsletters.php', 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']));

    $choose_audience_string .= '<form name="notifications" action="' . tep_href_link('newsletters.php', 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm') . '" method="post" onsubmit="return selectAll(\'notifications\', \'chosen[]\')"><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td align="center" class="smallText"><strong>' . TEXT_PRODUCTS . '</strong><br />' . tep_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                               '    <td align="center" class="smallText">&nbsp;<br />' . $global_button . '<br /><br /><br /><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover(\'remove\');"><br /><br /><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover(\'add\');"><br /><br /><br />' . tep_draw_button(IMAGE_SEND, 'mail-closed', null, 'primary') . '<br /><br />' . $cancel_button . '</td>' . "\n" .
                               '    <td align="center" class="smallText"><strong>' . TEXT_SELECTED_PRODUCTS . '</strong><br />' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '</table></form>';

    return $choose_audience_string;
  }

  public function confirm() {
    $audience = array();

    if (isset($_GET['global']) && ($_GET['global'] == 'true')) {
      $products_query = tep_db_query("SELECT DISTINCT customers_id FROM products_notifications");
      while ($products = tep_db_fetch_array($products_query)) {
        $audience[$products['customers_id']] = '1';
      }

      $customers_query = tep_db_query("SELECT customers_info_id FROM customers_info WHERE global_product_notifications = '1'");
      while ($customers = tep_db_fetch_array($customers_query)) {
        $audience[$customers['customers_info_id']] = '1';
      }
    } else {
      $chosen = $_POST['chosen'];

      $ids = implode(',', $chosen);

      $products_query = tep_db_query("select distinct customers_id from products_notifications where products_id in (" . $ids . ")");
      while ($products = tep_db_fetch_array($products_query)) {
        $audience[$products['customers_id']] = '1';
      }

      $customers_query = tep_db_query("SELECT customers_info_id FROM customers_info WHERE global_product_notifications = '1'");
      while ($customers = tep_db_fetch_array($customers_query)) {
        $audience[$customers['customers_info_id']] = '1';
      }
    }

    $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td class="main"><strong style="color:#ff0000">' . sprintf(TEXT_COUNT_CUSTOMERS, sizeof($audience)) . '</strong></td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td class="main"><strong>' . $this->title . '</strong></td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td class="main">' . nl2br($this->content) . '</td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr>' . tep_draw_form('confirm', 'newsletters.php', 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send') . "\n" .
                      '    <td class="smallText" align="right">';
    if (sizeof($audience) > 0) {
      if (isset($_GET['global']) && ($_GET['global'] == 'true')) {
        $confirm_string .= tep_draw_hidden_field('global', 'true');
      } else {
        for ($i = 0, $n = sizeof($chosen); $i < $n; $i++) {
          $confirm_string .= tep_draw_hidden_field('chosen[]', $chosen[$i]);
        }
      }
      $confirm_string .= tep_draw_button(IMAGE_SEND, 'mail-closed', null, 'primary');
    }
    $confirm_string .= tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('newsletters.php', 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=send')) . '</td>' . "\n" .
                       '  </tr>' . "\n" .
                       '</table>';

    return $confirm_string;
  }

  public function send($newsletter_id) {
    $audience = array();

    if (isset($_POST['global']) && ($_POST['global'] == 'true')) {
      $products_query = tep_db_query("SELECT DISTINCT pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address FROM customers c, products_notifications pn WHERE c.customers_id = pn.customers_id");
      while ($products = tep_db_fetch_array($products_query)) {
        $audience[$products['customers_email_address']] = $products['customers_firstname'] . ' ' . $products['customers_lastname'];
      }

      $customers_query = tep_db_query("SELECT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address FROM customers c, customers_info ci WHERE c.customers_id = ci.customers_info_id AND ci.global_product_notifications = '1'");
      while ($customers = tep_db_fetch_array($customers_query)) {
        $audience[$customers['customers_email_address']] = $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];;
      }
    } else {
      $chosen = $_POST['chosen'];

      $ids = implode(',', $chosen);

      $products_query = tep_db_query("select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from customers c, products_notifications pn where c.customers_id = pn.customers_id and pn.products_id in (" . $ids . ")");
      while ($products = tep_db_fetch_array($products_query)) {
        $audience[$products['customers_email_address']] = $products['customers_firstname'] . ' ' . $products['customers_lastname'];
      }

      $customers_query = tep_db_query("SELECT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address FROM customers c, customers_info ci WHERE c.customers_id = ci.customers_info_id AND ci.global_product_notifications = '1'");
      while ($customers = tep_db_fetch_array($customers_query)) {
        $audience[$customers['customers_email_address']] = $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];;
      }
    }

    tep_mail($audience, null, $this->title, $this->content, tep_extra_emails_array(EMAIL_FROM), null);
    
    tep_db_query("update newsletters set date_sent = now(), status = '1' where newsletters_id = '" . (int)$newsletter_id . "'");
  }
}
