<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Мовні визначення');

define('TABLE_HEADING_FILES', 'Файли');
define('TABLE_HEADING_WRITABLE', 'Доступний на запис');
define('TABLE_HEADING_LAST_MODIFIED', 'Дата модифікації');

define('TEXT_EDIT_NOTE', '<strong>Редагування визначень</strong><br /><br />Всі мовні константи визначаються використовуючи PHP функцію <a href="http://www.php.net/define" target="_blank">define()</a> наступним чином:<br /><br /><nobr>define(\'TEXT_MAIN \', \'<span style="background-color: #FFFF99;"> Цей текст можна редагувати. Це дійсно просто!</span>\');</nobr><br /><br />Виділений текст можна редагувати. Так як визначення вкладені в одинарні лапки то будь-які одинарні лапки всередині тексту необхідно екранувати зворотнім слешем (наприклад, It\\\'s).');

define('TEXT_FILE_DOES_NOT_EXIST', 'Файл відсутній.');

define('ERROR_FILE_NOT_WRITEABLE', 'Помилка: невірні права доступу до файлу, змініть права доступу до %s');
?>