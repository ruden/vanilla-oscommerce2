<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Búsqueda Avanzada');
define('NAVBAR_TITLE_2', 'Resultados de la Búsqueda');

define('HEADING_TITLE_1', 'Búsqueda Avanzada');
define('HEADING_TITLE_2', 'Productos que satisfacen los criterios de la búsqueda');

define('HEADING_SEARCH_CRITERIA', 'Criterios de Búsqueda');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Buscar también en la descripción');
define('ENTRY_CATEGORIES', 'Categorías:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'Incluir Subcategorías');
define('ENTRY_MANUFACTURERS', 'Fabricantes:');
define('ENTRY_PRICE_FROM', 'Precio Desde:');
define('ENTRY_PRICE_TO', 'Precia Hasta:');
define('ENTRY_DATE_FROM', 'Fecha Desde:');
define('ENTRY_DATE_TO', 'Fecha Hasta:');

define('TEXT_SEARCH_HELP_LINK', '<u>Ayuda para la Búsqueda</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Todas las categorías');
define('TEXT_ALL_MANUFACTURERS', 'Todos los fabricantes');

define('HEADING_SEARCH_HELP', 'Ayuda para la Búsqueda');
define('TEXT_SEARCH_HELP', 'Las palabras clave pueden ser separados por las declaraciones AND y/o OR para un mayor control de los resultados de la b&uacute;squeda.<br /><br />Por ejemplo, <u>Microsoft AND Rat&oacute;n</u> generar&iacute;a un conjunto de resultados que contengan ambas palabras. Sin embargo, para <u>Rat&oacute;n OR Teclado</u>, el conjunto de resultados retornado podr&iacute;an contener las dos o una sola palabra.<br /><br />Resultados exactos pueden buscarse encerrando las palabras entre comillas.<br /><br />Por ejemplo, <u>"computadora notebook"</u> la b&uacute;squeda dar&iacute;a como resultado el conjunto que tenga exactamente esa cadena.<br /><br />Los parentesis pueden ser utilizados para controlar el orden en el conjunto de resultados.<br /><br />Por ejemplo, <u>Microsoft and (keyboard or mouse or "visual basic")</u>.');
define('TEXT_CLOSE_WINDOW', '<u>Cerrar Ventana</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Modelo');
define('TABLE_HEADING_PRODUCTS', 'Nombre del Producto');
define('TABLE_HEADING_MANUFACTURER', 'Fabricante');
define('TABLE_HEADING_QUANTITY', 'Cantidad');
define('TABLE_HEADING_PRICE', 'Precio');
define('TABLE_HEADING_WEIGHT', 'Peso');
define('TABLE_HEADING_BUY_NOW', 'Comprar Ahora');

define('TEXT_NO_PRODUCTS', 'No hay productos que corresponden con los criterios de búsqueda.');

define('ERROR_AT_LEAST_ONE_INPUT', 'Al menos uno de los campos del formulario de búsqueda debe ser introducido.');
define('ERROR_INVALID_FROM_DATE', 'Fecha Desde no válido.');
define('ERROR_INVALID_TO_DATE', 'Fecha Hasta no válido.');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'Fecha Hasta debe ser mayor o igual a la Fecha Desde.');
define('ERROR_PRICE_FROM_MUST_BE_NUM', 'Precio Desde debe ser un n&uacute;mero.');
define('ERROR_PRICE_TO_MUST_BE_NUM', 'Precio Hasta debe ser un n&uacute;mero.');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', 'Precio Hasta debe ser mayor o igual al Precio Desde.');
define('ERROR_INVALID_KEYWORDS', 'Palabra(s) clave(s) no válida(s).');
?>
