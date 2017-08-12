<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Адміністратори');

define('TABLE_HEADING_ADMINISTRATORS', 'Адміністратори');
define('TABLE_HEADING_HTPASSWD', 'Захист Htpasswd');
define('TABLE_HEADING_ACTION', 'Дія');

define('TEXT_INFO_INSERT_INTRO', 'Введіть дані нового адміністратора');
define('TEXT_INFO_EDIT_INTRO', 'Зробіть необхідні зміни');
define('TEXT_INFO_DELETE_INTRO', 'Ви впевнені, що хочете видалити цього адміністратора?');
define('TEXT_INFO_HEADING_NEW_ADMINISTRATOR', 'Новий адміністратор');
define('TEXT_INFO_USERNAME', 'Ім’я:');
define('TEXT_INFO_NEW_PASSWORD', 'Новий пароль:');
define('TEXT_INFO_PASSWORD', 'Пароль:');
define('TEXT_INFO_PROTECT_WITH_HTPASSWD', 'Захист htaccess/htpasswd');

define('ERROR_ADMINISTRATOR_EXISTS', 'Помилка: Адміністратор вже існує.');

define('HTPASSWD_INFO', '<strong>Додатковий захист htaccess/htpasswd</strong><p>Ця адмін-панель магазину додатково не захищена htaccess/htpasswd.</p><p>При використанні додаткового рівня захисту htaccess/htpasswd ім’я адміністратора і його пароль будуть автоматично зберігатися у файлі htpasswd при оновленні записів адміністратора.</p><p><strong>Будь ласка пам’ятайте</strong>, якщо використовується додатковий рівень захисту і Ви не можете потрапити в адміністративну панель, будь ласка виконайте наступне і проконсультуйтеся з Вашим хостером для використання захисту htaccess/htpasswd:</p><p><u><strong>1. Відредагуйте файл:</strong></u><br /><br />' . DIR_FS_ADMIN . '.htaccess</p><p>Видаліть следующии рядки, якщо вони є:</p><p><i>%s</i></p><p><u><strong>2. Видаліть файл:</strong></u><br /><br />' . DIR_FS_ADMIN . '.htpasswd_oscommerce</p>');
define('HTPASSWD_SECURED', '<strong>Додатковий захист htaccess/htpasswd</strong><p>Ця адмін-панель магазину додатково захищена htaccess/htpasswd.</p>');
define('HTPASSWD_PERMISSIONS', '<strong>Додатковий захист htaccess/htpasswd</strong><p>Ця адмін-панель магазину додатково не захищена htaccess/htpasswd.</p><p>Для додаткового захисту htaccess/htpasswd необхідно зробити доступними на запис наступні файли:</p><ul><li>' . DIR_FS_ADMIN . '.htaccess</li><li>' . DIR_FS_ADMIN . '.htpasswd_oscommerce</li></ul><p>Перезавантажте цю сторінку для підтвердження, якщо у файлів встановлені правильні параметри доступу.</p>');
?>
