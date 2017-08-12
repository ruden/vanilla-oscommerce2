<?php
/*
  $Id: advanced_search.php,v 1.15 2003/07/08 16:45:35 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Розширений пошук');
define('NAVBAR_TITLE_2', 'Результати пошуку');

define('HEADING_TITLE_1', 'Розширений пошук');
define('HEADING_TITLE_2', 'Товари, відповідні до Вашого запиту');

define('HEADING_SEARCH_CRITERIA', 'Пошук товарів');

define('TEXT_SEARCH_IN_DESCRIPTION', 'Шукати в описі товарів');
define('ENTRY_CATEGORIES', 'Категорії:');
define('ENTRY_INCLUDE_SUBCATEGORIES', 'включаючи підкатегорії');
define('ENTRY_MANUFACTURERS', 'Виробники:');
define('ENTRY_PRICE_FROM', 'Ціна від:');
define('ENTRY_PRICE_TO', 'до:');
define('ENTRY_DATE_FROM', 'Дата додавання від:');
define('ENTRY_DATE_TO', 'до:');

define('TEXT_SEARCH_HELP_LINK', '<u>Рекомендації з пошуку</u> [?]');

define('TEXT_ALL_CATEGORIES', 'Всі категорії');
define('TEXT_ALL_MANUFACTURERS', 'Всі виробники');

define('HEADING_SEARCH_HELP', 'Рекомендації з пошуку');
define('TEXT_SEARCH_HELP', 'Система пошуку дозволяє Вам шукати продукти, назви, описи й виготовлювачів по ключовому слову.<br><br>При пошуку, Ви можете розділяти ключові слова й фрази приводами *AND*, *OR*. Наприклад, Ви можете ввести <u>Кишенькові комп’ютери AND аксесуари</u>. У результаті будуть виведені посилання, що містять обидва слова. Проте, якщо Ви заносите <u>Кишенькові комп’ютери OR аксесуари</u>, Ви одержите список, який містить обидва або одне зі слів, заданих у пошуку. Якщо слова не розділяються символами И або АБО, пошук буде працювати з визначенням АБО.<br><br>Ви можете також знайти точно задані слова, містячи їх у лапки. Наприклад, якщо Ви шукаєте <u>"Карти пам’яті"</u>, Ви одержите список продуктів, які містять цю фразу цілком.<br><br>Дужки можуть використовуватися, щоб управляти порядком логічних дій. Наприклад, Ви можете ввести <u>Комп’ютери (кишенькові or ноутбуки)</u>.');
define('TEXT_CLOSE_WINDOW', '<u>Закрити вікно</u> [x]');

define('TABLE_HEADING_IMAGE', '');
define('TABLE_HEADING_MODEL', 'Артикул');
define('TABLE_HEADING_PRODUCTS', 'Назва');
define('TABLE_HEADING_MANUFACTURER', 'Виробник');
define('TABLE_HEADING_QUANTITY', 'Кількість');
define('TABLE_HEADING_PRICE', 'Ціна');
define('TABLE_HEADING_WEIGHT', 'Вага');
define('TABLE_HEADING_BUY_NOW', 'Купити зараз');

define('TEXT_NO_PRODUCTS', 'Не знайдено товарів, відповідних до Вашого запиту.');

define('ERROR_AT_LEAST_ONE_INPUT', 'Ви не заповнили одне з необхідних полів форми.');
define('ERROR_INVALID_FROM_DATE', 'Неправильно заповнене поле Дата додавання від.');
define('ERROR_INVALID_TO_DATE', 'Неправильно заповнене поле Дата додавання до.');
define('ERROR_TO_DATE_LESS_THAN_FROM_DATE', 'Значення поля Дата додавання від повинне бути більше значення поля Дата додавання до.');
define('ERROR_PRICE_FROM_MUST_BE_NUM', 'Поле Ціна від повинне містити тільки цифри.');
define('ERROR_PRICE_TO_MUST_BE_NUM', 'Поле Ціна до повинне містити тільки цифри.');
define('ERROR_PRICE_TO_LESS_THAN_PRICE_FROM', 'Значення поля Ціна від повинне бути більше значення поля Ціна до.');
define('ERROR_INVALID_KEYWORDS', 'Пошуковий запит складений невірно.');

