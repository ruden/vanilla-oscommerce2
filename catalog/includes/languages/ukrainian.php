<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

// look in your $PATH_LOCALE/locale directory for available locales
// or type locale -a on the server.
// Examples:
// on RedHat try 'en_US'
// on FreeBSD try 'en_US.ISO_8859-1'
// on Windows try 'en', or 'English'
@setlocale(LC_ALL, array('uk_UA.UTF-8'));

define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd/m/Y'); // this is used for date()
define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
define('JQUERY_DATEPICKER_I18N_CODE', 'uk'); // leave empty for en_US; see http://jqueryui.com/demos/datepicker/#localization
define('JQUERY_DATEPICKER_FORMAT', 'dd/mm/yy'); // see http://docs.jquery.com/UI/Datepicker/formatDate

////
// Return date in raw format
// $date should be in format dd/mm/yyyy
// raw date is in format YYYYMMDD, or DDMMYYYY
function tep_date_raw($date, $reverse = false){
  if ($reverse) {
    return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
  } else {
    return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
  }
}

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'UAH');

// Global entries for the <html> tag
define('HTML_PARAMS', 'dir="ltr" lang="uk"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

// page title
define('TITLE', STORE_NAME);

// header text in includes/header.php
  define('HEADER_TITLE_CREATE_ACCOUNT', 'Реєстрація');
  define('HEADER_TITLE_MY_ACCOUNT', 'Мій кабінет');
  define('HEADER_TITLE_CART_CONTENTS', 'Кошик');
  define('HEADER_TITLE_CHECKOUT', 'Оформити замовлення');
  define('HEADER_TITLE_TOP', 'Головна');
  define('HEADER_TITLE_CATALOG', 'Каталог');
  define('HEADER_TITLE_LOGOFF', 'Вихід');
  define('HEADER_TITLE_LOGIN', 'Мої дані');

// footer text in includes/footer.php
  define('FOOTER_TEXT_REQUESTS_SINCE', 'переглянутих сторінок з');

// text for gender
define('MALE', 'Чоловічий');
define('FEMALE', 'Жіночий');
define('MALE_ADDRESS', 'Пан');
define('FEMALE_ADDRESS', 'Пані');

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd/mm/yyyy');

// checkout procedure text
define('CHECKOUT_BAR_DELIVERY', 'Адреса доставки');
define('CHECKOUT_BAR_PAYMENT', 'Спосіб оплати');
define('CHECKOUT_BAR_CONFIRMATION', 'Підтвердження');
define('CHECKOUT_BAR_FINISHED', 'Замовлення оформлене!');

// pull down default text
define('PULL_DOWN_DEFAULT', 'Виберіть');
define('TYPE_BELOW', 'Вибір нижче');

// javascript messages
define('JS_ERROR', 'Помилки при заповненні форми!\n\nВиправте будь ласка:\n\n');

define('JS_REVIEW_TEXT', '* Поле \'Ваш відгук\' повинне містити не менш ' . REVIEW_TEXT_MIN_LENGTH . ' символів.\n');
define('JS_REVIEW_RATING', '* Оцінить, будь ласка, продукт по пятибальній шкалі.\n');

define('JS_ERROR_NO_PAYMENT_MODULE_SELECTED', '* Виберіть метод оплати для Вашого замовлення.\n');

define('JS_ERROR_SUBMITTED', 'Ця форма вже заповнена. Натискайте Ok.');

define('ERROR_NO_PAYMENT_MODULE_SELECTED', 'Виберіть, будь ласка, метод оплати для Вашого замовлення.');

define('CATEGORY_COMPANY', 'Організація');
define('CATEGORY_PERSONAL', 'Ваші персональні дані');
define('CATEGORY_ADDRESS', 'Ваша адреса');
define('CATEGORY_CONTACT', 'Контактна інформація');
define('CATEGORY_OPTIONS', 'Розсилання');
define('CATEGORY_PASSWORD', 'Ваш пароль');

define('ENTRY_COMPANY', 'Назва компанії');
define('ENTRY_COMPANY_TEXT', '');
define('ENTRY_GENDER', 'Стать');
define('ENTRY_GENDER_ERROR', 'Ви повинні вказати свою стать.');
define('ENTRY_GENDER_TEXT', '*');
define('ENTRY_FIRST_NAME', 'Ім’я');
define('ENTRY_FIRST_NAME_ERROR', 'Поле Ім’я повинне містити як мінімум ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' символів.');
define('ENTRY_FIRST_NAME_TEXT', '*');
define('ENTRY_LAST_NAME', 'Прізвище');
define('ENTRY_LAST_NAME_ERROR', 'Поле Прізвище повинне містити як мінімум ' . ENTRY_LAST_NAME_MIN_LENGTH . ' символів.');
define('ENTRY_LAST_NAME_TEXT', '*');
define('ENTRY_DATE_OF_BIRTH', 'Дата народження');
define('ENTRY_DATE_OF_BIRTH_ERROR', 'Дату народження необхідно вводити в наступному форматі: MM/DD/YYYY (приклад 05/21/1970)');
define('ENTRY_DATE_OF_BIRTH_TEXT', '(приклад 05/21/1970)');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail');
define('ENTRY_EMAIL_ADDRESS_ERROR', 'Your E-Mail Address does not appear to be formatted correctly.');
define('ENTRY_EMAIL_ADDRESS_CHECK_ERROR', 'Ваш E-Mail адреса зазначена неправильно, спробуйте ще раз.');
define('ENTRY_EMAIL_ADDRESS_ERROR_EXISTS', 'Введений Вами E-Mail уже зареєстрований у нашому магазині, спробуйте вказати інший E-Mail адреса.');
define('ENTRY_EMAIL_ADDRESS_TEXT', '*');
define('ENTRY_STREET_ADDRESS', 'Вулиця й номер будинку');
define('ENTRY_STREET_ADDRESS_ERROR', 'Поле Вулиця й номер будинку повинне містити як мінімум ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' символів.');
define('ENTRY_STREET_ADDRESS_TEXT', '*');
define('ENTRY_SUBURB', 'Район');
define('ENTRY_SUBURB_TEXT', '');
define('ENTRY_POST_CODE', 'Поштовий індекс');
define('ENTRY_POST_CODE_ERROR', 'Поле Поштовий індекс повинне містити як мінімум ' . ENTRY_POSTCODE_MIN_LENGTH . ' символів.');
define('ENTRY_POST_CODE_TEXT', '*');
define('ENTRY_CITY', 'Місто');
define('ENTRY_CITY_ERROR', 'Поле Місто повинне містити як мінімум ' . ENTRY_CITY_MIN_LENGTH . ' символів.');
define('ENTRY_CITY_TEXT', '*');
define('ENTRY_STATE', 'Область');
define('ENTRY_STATE_ERROR', 'State does not appear to be formatted correctly.');
define('ENTRY_STATE_ERROR_SELECT', 'Виберіть область.');
define('ENTRY_STATE_TEXT', '*');
define('ENTRY_COUNTRY', 'Країна');
define('ENTRY_COUNTRY_ERROR', 'Виберіть країну.');
define('ENTRY_COUNTRY_TEXT', '*');
define('ENTRY_TELEPHONE_NUMBER', 'Телефон');
define('ENTRY_TELEPHONE_NUMBER_ERROR', 'Поле Телефон повинне містити як мінімум ' . ENTRY_TELEPHONE_MIN_LENGTH . ' символів.');
define('ENTRY_TELEPHONE_NUMBER_TEXT', '*');
define('ENTRY_FAX_NUMBER', 'Факс');
define('ENTRY_FAX_NUMBER_TEXT', '');
define('ENTRY_NEWSLETTER', 'Новини магазину');
define('ENTRY_NEWSLETTER_TEXT', '');
define('ENTRY_NEWSLETTER_YES', 'Підписатися');
define('ENTRY_NEWSLETTER_NO', 'Відмовитися від підписки');
define('ENTRY_PASSWORD', 'Пароль');
define('ENTRY_PASSWORD_ERROR', 'Ваш пароль повинен містити як мінімум ' . ENTRY_PASSWORD_MIN_LENGTH . ' символів.');
define('ENTRY_PASSWORD_ERROR_NOT_MATCHING', 'Поле Підтвердите пароль повинне збігатися з полем Пароль.');
define('ENTRY_PASSWORD_TEXT', '*');
define('ENTRY_PASSWORD_CONFIRMATION', 'Підтвердіть пароль');
define('ENTRY_PASSWORD_CONFIRMATION_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT', 'Поточний пароль');
define('ENTRY_PASSWORD_CURRENT_TEXT', '*');
define('ENTRY_PASSWORD_CURRENT_ERROR', 'Поле Пароль повинне містити як мінімум ' . ENTRY_PASSWORD_MIN_LENGTH . ' символів.');
define('ENTRY_PASSWORD_NEW', 'Новий пароль');
  define('ENTRY_PASSWORD_NEW_TEXT', '*');
  define('ENTRY_PASSWORD_NEW_ERROR', 'Ваш Новий пароль повинен містити як мінімум ' . ENTRY_PASSWORD_MIN_LENGTH . ' символів.');
  define('ENTRY_PASSWORD_NEW_ERROR_NOT_MATCHING', 'Поля Підтвердить пароль і Новий пароль повинні збігатися.');
  define('PASSWORD_HIDDEN', '--СХОВАНИЙ--');

  define('FORM_REQUIRED_INFORMATION', '* Обов&acute;язково для заповнення');
  
// constants for use in tep_prev_next_display function
define('TEXT_RESULT_PAGE', 'Сторінки:');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Показано <strong>%d</strong> - <strong>%d</strong> (усього <strong>%d</strong> позицій)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Показано <strong>%d</strong> - <strong>%d</strong> (усього <strong>%d</strong> замовлень)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Показано <strong>%d</strong> - <strong>%d</strong> (усього <strong>%d</strong> відгуків)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_NEW', 'Показано <strong>%d</strong> - <strong>%d</strong> (всего <strong>%d</strong> новинок)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Показано <strong>%d</strong> - <strong>%d</strong> (усього <strong>%d</strong> спеціальних пропозицій)');

define('PREVNEXT_TITLE_FIRST_PAGE', 'Перша сторінка');
define('PREVNEXT_TITLE_PREVIOUS_PAGE', 'попередня');
define('PREVNEXT_TITLE_NEXT_PAGE', 'Наступна сторінка');
define('PREVNEXT_TITLE_LAST_PAGE', 'Остання сторінка');
define('PREVNEXT_TITLE_PAGE_NO', 'Сторінка %d');
define('PREVNEXT_TITLE_PREV_SET_OF_NO_PAGE', 'Попередні %d сторінок');
define('PREVNEXT_TITLE_NEXT_SET_OF_NO_PAGE', 'Наступні %d сторінок');
define('PREVNEXT_BUTTON_FIRST', 'ПЕРША');
define('PREVNEXT_BUTTON_PREV', 'Попередня');
define('PREVNEXT_BUTTON_NEXT', 'Наступна');
define('PREVNEXT_BUTTON_LAST', 'Остання');

define('IMAGE_BUTTON_ADD_ADDRESS', 'Додати адресу');
define('IMAGE_BUTTON_ADDRESS_BOOK', 'Адресна книга');
define('IMAGE_BUTTON_BACK', 'Назад');
define('IMAGE_BUTTON_BUY_NOW', 'Купити зараз');
define('IMAGE_BUTTON_CHANGE_ADDRESS', 'Змінити адресу');
define('IMAGE_BUTTON_CHECKOUT', 'Оформити замовлення');
define('IMAGE_BUTTON_CONFIRM_ORDER', 'Підтвердити замовлення');
define('IMAGE_BUTTON_CONTINUE', 'Продовжити');
define('IMAGE_BUTTON_CONTINUE_SHOPPING', 'Повернутися в магазин');
define('IMAGE_BUTTON_DELETE', 'Вилучити');
define('IMAGE_BUTTON_EDIT_ACCOUNT', 'Редагувати облікові дані');
define('IMAGE_BUTTON_HISTORY', 'Історія замовлень');
define('IMAGE_BUTTON_LOGIN', 'Увійти');
define('IMAGE_BUTTON_IN_CART', 'Додати в Кошик');
define('IMAGE_BUTTON_NOTIFICATIONS', 'Повідомлення');
define('IMAGE_BUTTON_QUICK_FIND', 'Швидкий пошук');
define('IMAGE_BUTTON_REMOVE_NOTIFICATIONS', 'Вилучити повідомлення');
define('IMAGE_BUTTON_REVIEWS', 'Відгуки');
define('IMAGE_BUTTON_SEARCH', 'Шукати');
define('IMAGE_BUTTON_SHIPPING_OPTIONS', 'Способи доставки');
define('IMAGE_BUTTON_TELL_A_FRIEND', 'Написати другові');
define('IMAGE_BUTTON_UPDATE', 'Оновити');
define('IMAGE_BUTTON_UPDATE_CART', 'Перерахувати');
define('IMAGE_BUTTON_WRITE_REVIEW', 'Написати відгук');

define('SMALL_IMAGE_BUTTON_DELETE', 'Вилучити');
define('SMALL_IMAGE_BUTTON_EDIT', 'Змінити');
define('SMALL_IMAGE_BUTTON_VIEW', 'Дивитись');

define('ICON_ARROW_RIGHT', 'Перейти');
define('ICON_CART', 'У кошик');
define('ICON_ERROR', 'Помилка');
define('ICON_SUCCESS', 'Виконано');
define('ICON_WARNING', 'Увага');

define('TEXT_GREETING_PERSONAL', 'Ласкаво просимо <span class="greetUser">%s!</span> Ви прагнете подивитися які <a href="%s"><u>нові товари</u></a> потрапили у наш магазин?');
define('TEXT_GREETING_PERSONAL_RELOGON', '<small>Якщо Ви не %s, будь ласка <a href="%s"><u>зареєструйтеся </u></a> і введіть Вашу особисту інформацію.</small>');
define('TEXT_GREETING_GUEST', 'Ласкаво просимо <span class="greetUser">шановний гість</span>!<br> Якщо Ви наш постійний клієнт, <a href="%s"><u>введіть Ваші персональні дані</u></a> для входу. Якщо Ви в нас уперше й прагнете зробити покупки, Вам необхідно <a href="%s"><u>зареєструватися</u></a>.');

define('TEXT_SORT_PRODUCTS', 'Сортування товарів ');
define('TEXT_DESCENDINGLY', 'по убуванню');
define('TEXT_ASCENDINGLY', 'по зростанню');
define('TEXT_BY', ' по ');

define('TEXT_REVIEW_BY', 'від %s');
define('TEXT_REVIEW_WORD_COUNT', '%s слова');
define('TEXT_REVIEW_RATING', 'Рейтинг: %s [%s]');
define('TEXT_REVIEW_DATE_ADDED', 'Дата додавання: %s');
define('TEXT_NO_REVIEWS', 'До теперішнього часу немає відгуків, Ви можете стати першим.');

define('TEXT_NO_NEW_PRODUCTS', 'Сьогодні немає нових товарів.');

define('TEXT_UNKNOWN_TAX_RATE', 'Податкова ставка невідома');

define('TEXT_REQUIRED', '<span class="errorText">Обов’язково</span>');

define('ERROR_TEP_MAIL', '<font face="Verdana, Arial" size="2" color="#ff0000"><b><small>ПОМИЛКА:</small> Неможливо відправити email через сервер SMTP. Перевірте, будь ласка, Ваші установки php.ini і якщо необхідно, скорегуйте сервер SMTP.</b></font>');

define('TEXT_CCVAL_ERROR_INVALID_DATE', 'Ви вказали невірну дату витікання терміну дії кредитної картки. Спробуйте ще раз.');
define('TEXT_CCVAL_ERROR_INVALID_NUMBER', 'Ви вказали невірний номер кредитної картки. Спробуйте ще раз.');
define('TEXT_CCVAL_ERROR_UNKNOWN_CARD', 'Перші цифри Вашої кредитної картки: %s. Якщо Ви вказали номер своєї кредитної картки правильно, повідомляємо Вас, що ми не ухвалюємо до оплати даний тип кредитних карток. Якщо Ви вказали номер кредитної картки невірно, спробуйте ще раз.');

define('FOOTER_TEXT_BODY', 'Copyright &copy; ' . date('Y') . ' <a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . STORE_NAME . '</a><br />Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');
?>
