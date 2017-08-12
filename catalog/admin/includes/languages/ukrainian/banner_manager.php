<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Менеджер баннерів');

define('TABLE_HEADING_BANNERS', 'Банери');
define('TABLE_HEADING_GROUPS', 'Групи');
define('TABLE_HEADING_STATISTICS', 'Покази/Переходи');
define('TABLE_HEADING_STATUS', 'Статус');
define('TABLE_HEADING_ACTION', 'Дія');

define('TEXT_BANNERS_TITLE', 'Назва Баннера:');
define('TEXT_BANNERS_URL', 'URL Баннера:');
define('TEXT_BANNERS_GROUP', 'Група Баннера:');
define('TEXT_BANNERS_NEW_GROUP', ', виберіть групу або створити нову нижче');
define('TEXT_BANNERS_IMAGE', 'Банер:');
define('TEXT_BANNERS_IMAGE_LOCAL', ', або введіть локальний файл нижче');
define('TEXT_BANNERS_IMAGE_TARGET', 'Банер (Зберегти як):');
define('TEXT_BANNERS_HTML_TEXT', 'HTML Код:');
define('TEXT_BANNERS_EXPIRES_ON', 'Повинен показуватися до:');
define('TEXT_BANNERS_OR_AT', ', або ліміт');
define('TEXT_BANNERS_IMPRESSIONS', 'показів/кліків.');
define('TEXT_BANNERS_SCHEDULED_AT', 'Повинен показуватися з:');
define('TEXT_BANNERS_BANNER_NOTE', '<b>Примітка:</b><ul><li>Використовуйте для баннера лише зображення або HTML Код, але не одночасно обидва способи.</li> <li> HTML Код має пріоритет над зображенням</li></ul>');
define('TEXT_BANNERS_INSERT_NOTE', '<b>Інформація про завантаження банера:</b><ul><li> Директорія, в яку завантажуються банери повинна мати відповідні права доступу!</li><li>Не заповнюйте поле \'Зберегти Як\' якщо Ви не завантажуєте зображення на сервер (тобто, Ви використовуєте банер з локального диску).</li><li>Директорія, зазначена в полі \'Зберегти Як\' повинна бути створена на сервері і повинна закінчуватися косою рискою (наприклад, banners/).</li></ul>');
define('TEXT_BANNERS_EXPIRCY_NOTE', '<b>Інформація про показ банера:</b><ul><li> Тільки одне з полів "Повинен показуватися до" або "Повинен показуватися з" повинне бути заповнено, тобто 2 поля одночасно заповнені бути не можуть</li><li>Якщо банер повинен показуватися постійно, просто залиште ці поля порожніми</li></ul>');
define('TEXT_BANNERS_SCHEDULE_NOTE', '<b>Інформація про поле "Повинен показуватися з":</b><ul><li> Якщо Ви встановили дату в цьому полі, то банер буде показуватися з тієї дати, яку Ви вказали.</li><li> Всі банери, у яких заповнено поле "Повинен показуватися" за замовчуванням вимкнені, після того, як настане вказана дата, банер буде активовано.</li></ul>');

define('TEXT_BANNERS_DATE_ADDED', 'Дата долучення:');
define('TEXT_BANNERS_SCHEDULED_AT_DATE', 'Буде показано з: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_DATE', 'Показ до: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_IMPRESSIONS', 'Залишилося: <b>%s</b> показів');
define('TEXT_BANNERS_STATUS_CHANGE', 'Зміна Статусів: %s');

define('TEXT_BANNERS_DATA', 'Д<br />А<br />Т<br />А');
define('TEXT_BANNERS_LAST_3_DAYS', 'Останні 3 дня');
define('TEXT_BANNERS_BANNER_VIEWS', 'Покази');
define('TEXT_BANNERS_BANNER_CLICKS', 'Кліки');

define('TEXT_INFO_DELETE_INTRO', 'Ви дійсно хочете видалити цей банер?');
define('TEXT_INFO_DELETE_IMAGE', 'Видалити банер');

define('SUCCESS_BANNER_INSERTED', 'Виконано: Банер додано.');
define('SUCCESS_BANNER_UPDATED', 'Виконано: Банер змінено.');
define('SUCCESS_BANNER_REMOVED', 'Виконано: Банер був знищений.');
define('SUCCESS_BANNER_STATUS_UPDATED', 'Виконано: Статус банера змінено.');

define('ERROR_BANNER_TITLE_REQUIRED', 'Помилка: Введіть назву банера.');
define('ERROR_BANNER_GROUP_REQUIRED', 'Помилка: Введіть групу банера.');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Помилка: Вказана директорія відсутній: %s');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Помилка: Директорія має неправильні права доступу: %s');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Помилка: Банер відсутній.');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Помилка: Банер не може бути знищено.');
define('ERROR_UNKNOWN_STATUS_FLAG', 'Помилка: Невідомий статус.');

define('ERROR_GRAPHS_DIRECTORY_DOES_NOT_EXIST', 'Помилка: Директорія для банерів відсутній. Створіть папку \'graphs\' в директорії \'images\'.');
define('ERROR_GRAPHS_DIRECTORY_NOT_WRITEABLE', 'Помилка: Директорія має невірні права доступу.');
?>

