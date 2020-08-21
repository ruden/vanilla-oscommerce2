<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Iniciar Sesión');
define('NAVBAR_TITLE_2', 'Contraseña Olvidada');

define('HEADING_TITLE', '¡He olvidado mi contraseña!');

define('TEXT_MAIN', 'Si ha olvidado su contraseña, introduzca su dirección de Correo Electrónico y le enviaremos instrucciones sobre cómo cambiar su contraseña de forma segura.');

define('TEXT_PASSWORD_RESET_INITIATED', 'Por favor, compruebe su Correo Electrónico para obtener instrucciones sobre cómo cambiar tu contraseña. Las instrucciones contienen un enlace que sólo es válido durante 24 horas o hasta que su contraseña haya sido actualizada.');

define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<strong>NOTA:</strong> El Correo Electrónico  no figura en nuestros registro, por favor inténtalo de nuevo.');

define('EMAIL_PASSWORD_RESET_SUBJECT', STORE_NAME . ' - Nueva Contraseña');
define('EMAIL_PASSWORD_RESET_BODY', 'Una nueva contraseña ha sido solicitada de su cuenta en ' . STORE_NAME . '.' . "\n\n" . 'Por favor, siga este enlace personal para cambiar la contraseña de forma segura:' . "\n\n" . '%s' . "\n\n" . 'Este enlace se descartar&aacute; de forma automática después de 24 horas o después de que su contraseña haya sido cambiada.' . "\n\n" . 'Para obtener ayuda con cualquiera de nuestros servicios, por favor escriba al Administrador de la Tienda: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");

define('ERROR_ACTION_RECORDER', 'Error: Un enlace de restablecimiento de contraseña ya ha sido enviado. Vuelva a intentarlo en %s minutos.');
?>
