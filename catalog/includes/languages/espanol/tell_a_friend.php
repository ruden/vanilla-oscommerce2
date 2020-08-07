<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Enviar a un Amigo');

define('HEADING_TITLE', 'Enviar información sobre \'%s\' a un amigo');

define('FORM_TITLE_CUSTOMER_DETAILS', 'Tus datos');
define('FORM_TITLE_FRIEND_DETAILS', 'Los datos de tu amigo');
define('FORM_TITLE_FRIEND_MESSAGE', 'Tu Mensaje');

define('FORM_FIELD_CUSTOMER_NAME', 'Tu Nombre:');
define('FORM_FIELD_CUSTOMER_EMAIL', 'Tu Correo Electrónico:');
define('FORM_FIELD_FRIEND_NAME', 'El Nombre de tu amigo:');
define('FORM_FIELD_FRIEND_EMAIL', 'El Correo Electrónico de tu amigo:');

define('TEXT_EMAIL_SUCCESSFUL_SENT', 'Tu Correo Electrónico sobre <strong>%s</strong> ha sido enviado con éxito a <strong>%s</strong>.');

define('TEXT_EMAIL_SUBJECT', 'Tu amigo %s ha recomendado este gran producto de %s');
define('TEXT_EMAIL_INTRO', '¡Hola %s!' . "\n\n" . 'Tu amigo %s, pensó que estarías interesado en %s de %s.');
define('TEXT_EMAIL_LINK', 'Para ver el producto haga click en el siguiente enlace o copie y pegue el enlace en su navegador:' . "\n\n" . '%s');
define('TEXT_EMAIL_SIGNATURE', 'Atentamente,' . "\n\n" . '%s');

define('ERROR_TO_NAME', 'Error: El Nombre de su amigo no puede estar vacío.');
define('ERROR_TO_ADDRESS', 'Error: El Correo Electrónico de tu amigo debe ser una dirección válida de correo electrónico.');
define('ERROR_FROM_NAME', 'Error: Su nombre no puede estar vacío.');
define('ERROR_FROM_ADDRESS', 'Error: Su dirección de correo electrónico debe ser una dirección v&aacute;lida de correo electrónico.');
define('ERROR_ACTION_RECORDER', 'Error: Un correo electrónico ya ha sido enviado. Por favor intente de nuevo en %s minutos.');
?>
