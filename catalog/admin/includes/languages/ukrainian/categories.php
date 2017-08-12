<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Категорії / Товари');
define('HEADING_TITLE_SEARCH', 'Пошук:');
define('HEADING_TITLE_GOTO', 'Перейти в:');

define('TABLE_HEADING_ID', 'ID');
define('TABLE_HEADING_CATEGORIES_PRODUCTS', 'Категорії / Товари');
define('TABLE_HEADING_ACTION', 'Дія');
define('TABLE_HEADING_STATUS', 'Статус');

define('TEXT_NEW_PRODUCT', 'Новий Товар в &quot;%s&quot;');
define('TEXT_CATEGORIES', 'Категорії:');
define('TEXT_SUBCATEGORIES', 'Субкатегорії:');
define('TEXT_PRODUCTS', 'Товари:');
define('TEXT_PRODUCTS_PRICE_INFO', 'Ціна:');
define('TEXT_PRODUCTS_TAX_CLASS', 'Клас податків:');
define('TEXT_PRODUCTS_AVERAGE_RATING', 'Середня оцінка:');
define('TEXT_PRODUCTS_QUANTITY_INFO', 'Кількість:');
define('TEXT_DATE_ADDED', 'Дата додавання:');
define('TEXT_DATE_AVAILABLE', 'Доступно з:');
define('TEXT_LAST_MODIFIED', 'Остання зміна:');
define('TEXT_IMAGE_NONEXISTENT', 'Зображення не знайдено');
define('TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS', 'Додайте, будь-ласка, нову категорію або товар.');
define('TEXT_PRODUCT_MORE_INFORMATION', 'Більш детальна інформація про товар <a href="http://%s" target="blank"><u>на цій сторінці</u></a>.');
define('TEXT_PRODUCT_DATE_ADDED', 'Цей товар був доданий в каталог %s.');
define('TEXT_PRODUCT_DATE_AVAILABLE', 'Цей товар буде у продажу з %s.');

define('TEXT_EDIT_INTRO', 'Будь-ласка , внесіть необхідні зміни');
define('TEXT_EDIT_CATEGORIES_ID', 'ID категорії:');
define('TEXT_EDIT_CATEGORIES_NAME', 'Назва категорії:');
define('TEXT_EDIT_CATEGORIES_IMAGE', 'Зображення для категорії:');
define('TEXT_EDIT_SORT_ORDER', 'Порядок сортування:');

define('TEXT_INFO_COPY_TO_INTRO', 'Виберіть, будь-ласка, нову категорію в яку Ви хочете скопіювати цей товар');
define('TEXT_INFO_CURRENT_CATEGORIES', 'Поточні категорії:');

define('TEXT_INFO_HEADING_NEW_CATEGORY', 'Нова категорія');
define('TEXT_INFO_HEADING_EDIT_CATEGORY', 'Змінити категорію');
define('TEXT_INFO_HEADING_DELETE_CATEGORY', 'Видалити категорію');
define('TEXT_INFO_HEADING_MOVE_CATEGORY', 'Перенести категорію');
define('TEXT_INFO_HEADING_DELETE_PRODUCT', 'Видалити товар');
define('TEXT_INFO_HEADING_MOVE_PRODUCT', 'Перенести товар');
define('TEXT_INFO_HEADING_COPY_TO', 'Копіювати в');

define('TEXT_DELETE_CATEGORY_INTRO', 'Ви дійсно хочете видалити цю категорію?');
define('TEXT_DELETE_PRODUCT_INTRO', 'Ви дійсно хочете видалити цей товар?');

define('TEXT_DELETE_WARNING_CHILDS', '<strong>УВАГА:</strong> Є ще %s субкатегорій, пов’язаних з цією категорією!');
define('TEXT_DELETE_WARNING_PRODUCTS', '<strong>УВАГА:</strong> Є ще %s найменувань товару, пов’язаних з цією категорією!');

define('TEXT_MOVE_PRODUCTS_INTRO', 'Будь-ласка, виберіть категорію для переміщення <strong>%s</strong> в');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Будь-ласка, виберіть категорію для переміщення <strong>%s</strong> в');
define('TEXT_MOVE', 'Перемістити <strong>%s</strong> в:');

define('TEXT_NEW_CATEGORY_INTRO', 'Будь-ласка, заповніть наступну інформацію для нової категорії');
define('TEXT_CATEGORIES_NAME', 'Назва категорії:');
define('TEXT_CATEGORIES_IMAGE', 'Зображення категорії:');
define('TEXT_SORT_ORDER', 'Порядок сортування:');

define('TEXT_PRODUCTS_STATUS', 'Статус товару:');
define('TEXT_PRODUCTS_DATE_AVAILABLE', 'Дата надходження:');
define('TEXT_PRODUCT_AVAILABLE', 'В наявності');
define('TEXT_PRODUCT_NOT_AVAILABLE', 'Немає в наявності');
define('TEXT_PRODUCTS_MANUFACTURER', 'Виробник:');
define('TEXT_PRODUCTS_NAME', 'Назва:');
define('TEXT_PRODUCTS_DESCRIPTION', 'Опис товару:');
define('TEXT_PRODUCTS_QUANTITY', 'К-сть товару на складі:');
define('TEXT_PRODUCTS_MODEL', 'Код товару:');
define('TEXT_PRODUCTS_IMAGE', 'Зображення Товару:');
define('TEXT_PRODUCTS_MAIN_IMAGE', 'Основне зображення');
define('TEXT_PRODUCTS_LARGE_IMAGE', 'Додаткове зображення');
define('TEXT_PRODUCTS_LARGE_IMAGE_HTML_CONTENT', 'HTML-вміст (для popup)');
define('TEXT_PRODUCTS_ADD_LARGE_IMAGE', 'Додати додаткове зображення');
define('TEXT_PRODUCTS_LARGE_IMAGE_DELETE_TITLE', 'Видалити додаткове зображення?');
define('TEXT_PRODUCTS_LARGE_IMAGE_CONFIRM_DELETE', 'Підтвердіть видалення додаткового зображення товару.');
define('TEXT_PRODUCTS_URL', 'URL товару:');
define('TEXT_PRODUCTS_URL_WITHOUT_HTTP', '<small>(без http://)</small>');
define('TEXT_PRODUCTS_PRICE_NET', 'Ціна (Без податку):');
define('TEXT_PRODUCTS_PRICE_GROSS', 'Ціна (З податком):');
define('TEXT_PRODUCTS_WEIGHT', 'Вага товару:');

define('EMPTY_CATEGORY', 'Порожня категорія');

define('TEXT_HOW_TO_COPY', 'Метод копіювання:');
define('TEXT_COPY_AS_LINK', 'Посилання на товар');
define('TEXT_COPY_AS_DUPLICATE', 'Дублювати товар');

define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Помилка: Не можна робити посилання на товар в тій же категорії.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Помилка: Каталог із зображеннями має невірні права доступу:'. DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Помилка: Каталог із зображеннями відсутній:'. DIR_FS_CATALOG_IMAGES);
define('ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT', 'Помилка: Категорія не може бути перенесена.');

?>

