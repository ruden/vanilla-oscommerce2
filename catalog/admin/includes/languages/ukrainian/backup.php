<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Резервне копіювання');

define('TABLE_HEADING_TITLE', 'Ім’я');
define('TABLE_HEADING_FILE_DATE', 'Дата');
define('TABLE_HEADING_FILE_SIZE', 'Розмір');
define('TABLE_HEADING_ACTION', 'Дія');

define('TEXT_INFO_HEADING_NEW_BACKUP', 'Зберегти Заново');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Відновити Локально');
define('TEXT_INFO_NEW_BACKUP', 'Не завершуйте процес, який може зайняти пару хвилин.');
define('TEXT_INFO_UNPACK', '<br /><br />(после распаковки файла из архива)');
define('TEXT_INFO_RESTORE', 'Не завершуйте процес відновлення.<br /><br />Велика база даних, більш довгий процес!<br /><br />Якщо можливо, використовуйте shell команди.<br /><br />Наприклад:<br /><br /><b>mysql -h' . DB_SERVER . ' -u' . DB_SERVER_USERNAME . ' -p ' . DB_DATABASE . ' < %s </b> %s');
define('TEXT_INFO_RESTORE_LOCAL', 'Не завершуйте процес відновлення<br /><br />Велика база даних, більш довгий процес.');
define('TEXT_INFO_RESTORE_LOCAL_RAW_FILE', 'Завантажений файл повинен бути текстовим файлом SQL .');
define('TEXT_INFO_DATE', 'Дата:') ;
define('TEXT_INFO_SIZE', 'Розмір:') ;
define('TEXT_INFO_COMPRESSION', 'Стиснення:');
define('TEXT_INFO_USE_GZIP', 'Використовувати GZIP');
define('TEXT_INFO_USE_ZIP', 'Використовувати ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'Без стиснення (Просто SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Тільки завантаження (Не завантажуйте на віддалений сервер)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Найкращий варіант - зв’язок через HTTPS');
define('TEXT_DELETE_INTRO', 'Ви дійсно хочете видалити цю копію?');
define('TEXT_NO_EXTENSION', 'Немає');
define('TEXT_BACKUP_DIRECTORY', 'Резервна Директорія:') ;
define('TEXT_LAST_RESTORATION', 'Останнє Відновлення:');
define('TEXT_FORGET', '(<u>забути</u>)');

define('ERROR_BACKUP_DIRECTORY_DOES_NOT_EXIST', 'Помилка: Директорія для резервного копіювання не існує.');
define('ERROR_BACKUP_DIRECTORY_NOT_WRITEABLE', 'Помилка: Директорія для резервного копіювання захищена від запису , встановіть вірні права доступу.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Помилка: Посилання для завантаження не прийнятна.');

define('SUCCESS_LAST_RESTORE_CLEARED', 'Виконано: Остання дата відновлення очищена.');
define('SUCCESS_DATABASE_SAVED', 'Виконано: База даних збережена.');
define('SUCCESS_DATABASE_RESTORED', 'Виконано: База даних відновлена.');
define('SUCCESS_BACKUP_DELETED', 'Виконано: Копія видалена.');
?>
