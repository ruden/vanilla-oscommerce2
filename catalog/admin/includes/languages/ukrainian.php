<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales..
// on RedHat6.0 I used 'en_US'
// on FreeBSD 4.0 I use 'en_US.ISO_8859-1'
// this may not work under win32 environments..
setlocale(LC_ALL, array('uk_UA.UTF-8'));
define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%d %B %Y р.'); // this is used for strftime()
define('DATE_FORMAT', 'd.m.Y'); // this is used for date()
define('PHP_DATE_TIME_FORMAT', 'd.m.Y H:i:s'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('JQUERY_DATEPICKER_I18N_CODE', 'uk'); // leave empty for en_US; see http://jqueryui.com/demos/datepicker/#localization
define('JQUERY_DATEPICKER_FORMAT', 'dd.mm.yy'); // see http://docs.jquery.com/UI/Datepicker/formatDate

////
// Return date in raw format
// $date should be in format dd/mm/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false) {
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="uk"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

// page title
define('TITLE', 'Адміністрування');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Адміністрування');
define('HEADER_TITLE_SUPPORT_SITE', 'Сайт підтримки');
define('HEADER_TITLE_ONLINE_CATALOG', 'Каталог');
define('HEADER_TITLE_ADMINISTRATION', 'Адміністрація');

// text for gender
define('MALE', 'Чоловік');
define('FEMALE', 'Жінка');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd.mm.yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Налаштування');
define('BOX_CONFIGURATION_MYSTORE', 'Магазин');
define('BOX_CONFIGURATION_LOGGING', 'Логи');
define('BOX_CONFIGURATION_CACHE', 'Кеш');
define('BOX_CONFIGURATION_ADMINISTRATORS', 'Адміністратори');
define('BOX_CONFIGURATION_STORE_LOGO', 'Логотип магазину');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Модулі');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Каталог');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Категорії / Товари');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Опції товарів');
define('BOX_CATALOG_MANUFACTURERS', 'Виробники');
define('BOX_CATALOG_REVIEWS', 'Відгуки');
define('BOX_CATALOG_SPECIALS', 'Знижки');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Очікувані товари');

// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Покупці');
define('BOX_CUSTOMERS_CUSTOMERS', 'Покупці');

// orders box text in includes/boxes/orders.php
define('BOX_HEADING_ORDERS', 'Замовлення');
define('BOX_ORDERS_ORDERS', 'Замовлення');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Місця / Податки');
define('BOX_TAXES_COUNTRIES', 'Країни');
define('BOX_TAXES_ZONES', 'Області');
define('BOX_TAXES_GEO_ZONES', 'Податкові зони');
define('BOX_TAXES_TAX_CLASSES', 'Типи податків');
define('BOX_TAXES_TAX_RATES', 'Cтавки податків');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Звіти');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Переглянуті товари');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Замовлені товари');
define('BOX_REPORTS_ORDERS_TOTAL', 'Кращі покупці');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Інструменти');
define('BOX_TOOLS_ACTION_RECORDER', 'Запис дій');
define('BOX_TOOLS_BACKUP', 'Резервне копіювання БД');
define('BOX_TOOLS_BANNER_MANAGER', 'Менеджер банерів');
define('BOX_TOOLS_CACHE', 'Контроль кешу');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Мовні файли');
define('BOX_TOOLS_MAIL', 'Надіслати E-Mail');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Менеджер поштових розсилок');
define('BOX_TOOLS_SEC_DIR_PERMISSIONS', 'Безпека прав директорій');
define('BOX_TOOLS_SERVER_INFO', 'Інформація про сервер');
define('BOX_TOOLS_VERSION_CHECK', 'Перевірка версії');
define('BOX_TOOLS_WHOS_ONLINE', 'Хто в онлайні');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Локалізація');
define('BOX_LOCALIZATION_CURRENCIES', 'Валюти');
define('BOX_LOCALIZATION_LANGUAGES', 'Мови');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Статуси замовлень');

// javascript messages
define('JS_ERROR', 'При заповненні форми Ви допустили помилки!\nВиконайте, будь ласка, наступні виправлення:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* Новий атрибут товару повинен мати ціну\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* Новий атрибут товару повинен мати ціновий префікс\n');

define('JS_PRODUCTS_NAME', '* Для нового товару повинно бути зазначено найменування\n');
define('JS_PRODUCTS_DESCRIPTION', '* Для нового товару повинно бути вказано опис\n');
define('JS_PRODUCTS_PRICE', '* Для нового товару повинна бути вказана ціна\n');
define('JS_PRODUCTS_WEIGHT', '* Для нового товару повинен бути зазначений вага\n');
define('JS_PRODUCTS_QUANTITY', '* Для нового товару повинно бути зазначено кількість\n');
define('JS_PRODUCTS_MODEL', '* Для нового товару повинен бути зазначений код товару\n');
define('JS_PRODUCTS_IMAGE', '* Для нового товару повинна бути картинка\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* Для цього товару повинна бути встановлена нова ціна\n');

define('JS_GENDER', '* Поле \'Стать\' повинно бути вибрано.\n');
define('JS_FIRST_NAME', '* Поле \'Ім’я\' має містити не менше'. ENTRY_FIRST_NAME_MIN_LENGTH. 'символів.\n');
define('JS_LAST_NAME', '* Поле \'Прізвище\' має містити не менше'. ENTRY_LAST_NAME_MIN_LENGTH. 'символів.\n');
define('JS_DOB', '* Поле \'День народження\' повинно мати формат: xx/xx/xxxx (день/місяць/рік).\n');
define('JS_EMAIL_ADDRESS', '* Поле \'E-Mail адреса\' має містити не менше'. ENTRY_EMAIL_ADDRESS_MIN_LENGTH. 'символів.\n');
define('JS_ADDRESS', '* Поле \'Адреса\' має містити не менше'. ENTRY_STREET_ADDRESS_MIN_LENGTH. 'символів.\n');
define('JS_POST_CODE', '* Поле \'Індекс\' має містити не менше'. ENTRY_POSTCODE_MIN_LENGTH. 'символів.\n');
define('JS_CITY', '* Поле \'Місто\' має містити не менше'. ENTRY_CITY_MIN_LENGTH. 'символів.\n');
define('JS_STATE', '* Поле \'Регіон\' повинно бути вибрано.\n');
define('JS_STATE_SELECT', '- Виберіть вище --');
define('JS_ZONE', '* Поле \'Регіон\' повинно відповідати вибраной країні.');
define('JS_COUNTRY', '* Поле \'Країна\' дожно бути заповнено.\n');
define('JS_TELEPHONE', '* Поле \'Телефон\' має містити не менше'. ENTRY_TELEPHONE_MIN_LENGTH. 'символів.\n');
define('JS_PASSWORD', '* Поля \'Пароль\' та \'Підтвердження\' повинні збігатися і містити не менше'. ENTRY_PASSWORD_MIN_LENGTH. 'символів.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Замовлення номер %s не знайдено!');

define('CATEGORY_PERSONAL', 'Персональний');
define('CATEGORY_ADDRESS', 'Адреса');
define('CATEGORY_CONTACT', 'Для контакту');
define('CATEGORY_COMPANY', 'Компанія');
define('CATEGORY_OPTIONS', 'Розсилка');

define('ENTRY_GENDER', 'Стать');
define('ENTRY_GENDER_ERROR', '&nbsp;<span class="errorText">обов’язково</span>');
define('ENTRY_FIRST_NAME', 'Ім’я:');
define('ENTRY_FIRST_NAME_ERROR', '&nbsp;<span class="errorText">мінімум ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' символів</span>');
define('ENTRY_LAST_NAME', 'Прізвище:');
define('ENTRY_LAST_NAME_ERROR', '&nbsp;<span class="errorText">мінімум ' . ENTRY_LAST_NAME_MIN_LENGTH . ' символів</span>');
define('ENTRY_DATE_OF_BIRTH', 'Дата народження:');
define('ENTRY_DATE_OF_BIRTH_ERROR', '&nbsp;<span class="errorText">(наприклад 21/05/1970)</span>');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Адреса:');
define('ENTRY_EMAIL_ADDRESS_ERROR', '&nbsp;<span class="errorText">мінімум ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' символів</span>');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', '&nbsp;<span class="errorText">Ви ввели невірну email адресу!</span>');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', '&nbsp;<span class="errorText">Дана e-mail адреса вже зареєстрований!</span>');
define('ENTRY_COMPANY', 'Назва компанії:');
define('ENTRY_STREET_ADDRESS', 'Адрес:');
define('ENTRY_STREET_ADDRESS_ERROR', '&nbsp;<span class="errorText">мінімум ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' символів</span>');
define('ENTRY_SUBURB', 'Додаткова адреса:');
define('ENTRY_POST_CODE', 'Індекс:');
define('ENTRY_POST_CODE_ERROR', '&nbsp;<span class="errorText">мінімум ' . ENTRY_POSTCODE_MIN_LENGTH . ' символів</span>');
define('ENTRY_CITY', 'Місто:');
define('ENTRY_CITY_ERROR', '&nbsp;<span class="errorText">мінімум ' . ENTRY_CITY_MIN_LENGTH . ' символів</span>');
define('ENTRY_STATE', 'Регіон:');
define('ENTRY_STATE_ERROR', '&nbsp;<span class="errorText">обов’язково</span>');
define('ENTRY_COUNTRY', 'Країна:');
define('ENTRY_COUNTRY_ERROR', 'Ви повинні вибрати країну з випадаючого списку.');
define('ENTRY_TELEPHONE_NUMBER', 'Телефон:');
define('ENTRY_TELEPHONE_NUMBER_ERROR', '&nbsp;<span class="errorText">мінімум '. ENTRY_TELEPHONE_MIN_LENGTH. ' символів</span>');
define('ENTRY_FAX_NUMBER', 'Факс:');
define('ENTRY_NEWSLETTER', 'Отримувати розсилку:');
define('ENTRY_NEWSLETTER_YES', 'Підписано');
define('ENTRY_NEWSLETTER_NO', 'Не підписаний');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Надіслати E-Mail');
define('IMAGE_BACK', 'Назад');
define('IMAGE_BACKUP',  'Рез. копія');
define('IMAGE_CANCEL', 'Відмінити');
define('IMAGE_CONFIRM', 'Підтвердити');
define('IMAGE_COPY', 'Копіювати');
define('IMAGE_COPY_TO', 'Копіювати в');
define('IMAGE_DETAILS', 'Детальніше');
define('IMAGE_DELETE', 'Видалити');
define('IMAGE_EDIT', 'Редагувати');
define('IMAGE_EMAIL', 'Листи');
define('IMAGE_EXPORT', 'Експорт');
define('IMAGE_ICON_STATUS_GREEN', 'Активний');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Активувати');
define('IMAGE_ICON_STATUS_RED', 'Неактивний');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Зробити неактивним');
define('IMAGE_ICON_INFO', 'Інформація');
define('IMAGE_INSERT', 'Додати');
define('IMAGE_LOCK', 'Замок');
define('IMAGE_MODULE_INSTALL', 'Встановити модуль');
define('IMAGE_MODULE_REMOVE',  'Видалити модуль');
define('IMAGE_MOVE', 'Перемістити');
define('IMAGE_NEW_BANNER', 'Новий банер');
define('IMAGE_NEW_CATEGORY', 'Нова категорія');
define('IMAGE_NEW_COUNTRY',  'Нова країна');
define('IMAGE_NEW_CURRENCY', 'Нова валюта');
define('IMAGE_NEW_FILE', 'Новий файл' ) ;
define('IMAGE_NEW_FOLDER', 'Нова папка');
define('IMAGE_NEW_LANGUAGE', 'Нова мова');
define('IMAGE_NEW_NEWSLETTER', 'Новий лист новин');
define('IMAGE_NEW_PRODUCT', 'Новий товар');
define('IMAGE_NEW_TAX_CLASS', 'Новий податок');
define('IMAGE_NEW_TAX_RATE', 'Нова ставка податку');
define('IMAGE_NEW_TAX_ZONE', 'Нова податкова зона');
define('IMAGE_NEW_ZONE', 'Нова зона');
define('IMAGE_ORDERS', 'Замовлення');
define('IMAGE_ORDERS_INVOICE', 'Рахунок-фактура');
define('IMAGE_ORDERS_PACKINGSLIP',  'Накладна');
define('IMAGE_PREVIEW', 'Передпроглядання');
define('IMAGE_RESTORE', 'Відновити');
define('IMAGE_RESET', 'Скинути');
define('IMAGE_SAVE', 'Зберегти');
define('IMAGE_SEARCH', 'Шукати');
define('IMAGE_SELECT', 'Вибрати');
define('IMAGE_SEND', 'Відправити');
define('IMAGE_SEND_EMAIL',  'Надіслати E-Mail');
define('IMAGE_UNLOCK', 'Розблокувати');
define('IMAGE_UPDATE', 'Оновити');
define('IMAGE_UPDATE_CURRENCIES',  'Скоректувати курси валют');
define('IMAGE_UPLOAD', 'Завантажити');

define('ICON_CROSS', 'Недійсно');
define('ICON_CURRENT_FOLDER', 'Поточна директорія');
define('ICON_DELETE', 'Видалити');
define('ICON_ERROR', 'Помилка');
define('ICON_FILE', 'Файл');
define('ICON_FILE_DOWNLOAD', 'Завантаження');
define('ICON_FOLDER', 'Папка');
define('ICON_LOCKED', 'Заблокувати');
define('ICON_PREVIOUS_LEVEL',  'Попередній рівень');
define('ICON_PREVIEW', 'Передпроглядання');
define('ICON_STATISTICS', 'Статистика');
define('ICON_SUCCESS', 'Виконано');
define('ICON_TICK', 'Істина');
define('ICON_UNLOCKED', 'Розблокувати');
define('ICON_WARNING', 'УВАГА');

// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Сторінка %s із %d');
define('TEXT_DISPLAY_NUMBER_OF_BANNERS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> банерів)');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> країн)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> покупців)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> валют)');
define('TEXT_DISPLAY_NUMBER_OF_ENTRIES', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> записів)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> мов)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> виробників)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> розсилок)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> замовлень)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> статусів)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> позицій)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> очікуваних товарів)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> відгуків про товари)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> спеціальних пропозицій)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> типів податків)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> податкових зон)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> ставок податків)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Показано <strong>%d</strong> - <strong>%d</strong> (всього <strong>%d</strong> зон)');

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'за замовчуванням');
define('TEXT_SET_DEFAULT', 'Встановити за замовчуванням');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Обов’язково</span>');

define('TEXT_CACHE_CATEGORIES', 'Бокс категорій');
define('TEXT_CACHE_MANUFACTURERS', 'Бокс виробників');
define('TEXT_CACHE_ALSO_PURCHASED', 'Також модулі покупок');

define('TEXT_NONE', '--ні--');
define('TEXT_TOP', 'Початок');

define('ERROR_DESTINATION_DOES_NOT_EXIST', 'Помилка: Каталогу не існує.');
define('ERROR_DESTINATION_NOT_WRITEABLE', 'Помилка: Каталог захищений від запису, встановіть необхідні права доступу.');
define('ERROR_FILE_NOT_SAVED', 'Помилка: Файл не був завантажений.');
define('ERROR_FILETYPE_NOT_ALLOWED', 'Помилка: Не можна закачувати файли даного типу.');
define('SUCCESS_FILE_SAVED_SUCCESSFULLY', 'Виконано: Файл успішно завантажений.');
define('WARNING_NO_FILE_UPLOADED', 'Попередження: Нові файли не завантажені.');
?>
