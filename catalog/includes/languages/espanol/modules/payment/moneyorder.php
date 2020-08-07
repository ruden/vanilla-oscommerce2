<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  define('MODULE_PAYMENT_MONEYORDER_TEXT_TITLE', 'Check/Money Order');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_DESCRIPTION', 'Hacer pagadero a:&nbsp;' . (defined('MODULE_PAYMENT_MONEYORDER_PAYTO') ? MODULE_PAYMENT_MONEYORDER_PAYTO : '') . '<br /><br />Enviar A:<br />' . STORE_NAME . '<br />' . nl2br(STORE_NAME_ADDRESS) . '<br /><br />' . 'Su pedido no se enviar&aacute; hasta que recibamos el pago.');
  define('MODULE_PAYMENT_MONEYORDER_TEXT_EMAIL_FOOTER', "Hacer pagadero a: ". (defined('MODULE_PAYMENT_MONEYORDER_PAYTO') ? MODULE_PAYMENT_MONEYORDER_PAYTO : '') . "\n\nEnviar A:\n" . "\n" . STORE_NAME . "\n" . STORE_NAME_ADDRESS . "\n\n" . 'Su pedido no se enviar&aacute; hasta que recibamos el pago.');
?>
