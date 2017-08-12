<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Замовлення');
define('HEADING_TITLE_SEARCH', 'Пошук по ID замовлення');
define('HEADING_TITLE_STATUS', 'Стан:');

define('TABLE_HEADING_COMMENTS', 'Коментар');
define('TABLE_HEADING_CUSTOMERS', 'Покупці');
define('TABLE_HEADING_ORDER_TOTAL', 'Замовлення разом');
define('TABLE_HEADING_DATE_PURCHASED', 'Дата купівлі');
define('TABLE_HEADING_STATUS', 'Стан');
define('TABLE_HEADING_ACTION', 'Дія');
define('TABLE_HEADING_QUANTITY', 'Кількість');
define('TABLE_HEADING_PRODUCTS_MODEL', 'Код товару');
define('TABLE_HEADING_PRODUCTS', 'Товари');
define('TABLE_HEADING_TAX', 'Податок');
define('TABLE_HEADING_TOTAL', 'Всього');
define('TABLE_HEADING_PRICE_EXCLUDING_TAX', 'Ціна (без податку)');
define('TABLE_HEADING_PRICE_INCLUDING_TAX', 'Ціна (з податком)');
define('TABLE_HEADING_TOTAL_EXCLUDING_TAX', 'Загальна (без податку)');
define('TABLE_HEADING_TOTAL_INCLUDING_TAX', 'Загальна (з податком)');

define('TABLE_HEADING_CUSTOMER_NOTIFIED', 'Клієнт проінформований');
define('TABLE_HEADING_DATE_ADDED', 'Дата долучення');

define('ENTRY_CUSTOMER', 'Клієнт:');
define('ENTRY_SOLD_TO', 'ПОКУПЕЦЬ:');
define('ENTRY_DELIVERY_TO', 'Адреса:');
define('ENTRY_SHIP_TO', 'АДРЕСА ДОСТАВКИ:');
define('ENTRY_SHIPPING_ADDRESS', 'Адреса доставки:');
define('ENTRY_BILLING_ADDRESS', 'Адреса покупця:');
define('ENTRY_PAYMENT_METHOD', 'Спосіб оплати:');
define('ENTRY_CREDIT_CARD_TYPE', 'Тип Кредитною Картки:');
define('ENTRY_CREDIT_CARD_OWNER', 'Власник Кредитною Картки:');
define('ENTRY_CREDIT_CARD_NUMBER', 'Номер Кредитною Картки:');
define('ENTRY_CREDIT_CARD_EXPIRES', 'Термін закінчення дії кредитної картки:');
define('ENTRY_SUB_TOTAL', 'Попередній підсумок:');
define('ENTRY_TAX', 'Податок:');
define('ENTRY_SHIPPING', 'Доставка:');
define('ENTRY_TOTAL', 'Всього:');
define('ENTRY_DATE_PURCHASED', 'Дата Покупки:');
define('ENTRY_STATUS', 'Статус:');
define('ENTRY_DATE_LAST_UPDATED', 'Остання зміна:');
define('ENTRY_ADD_COMMENT', 'Коментар:');
define('ENTRY_NOTIFY_CUSTOMER', 'Повідомити Клієнта:');
define('ENTRY_NOTIFY_COMMENTS', 'Додати коментарі:');
define('ENTRY_PRINTABLE', 'Надрукувати рахунок');

define('TEXT_INFO_HEADING_DELETE_ORDER', 'Видалити замовлення');
define('TEXT_INFO_DELETE_INTRO', 'Ви впевнені, що хочете видалити це замовлення?');
define('TEXT_INFO_RESTOCK_PRODUCT_QUANTITY', 'Перерахувати кількість товару на складі');
define('TEXT_DATE_ORDER_CREATED', 'Дата створення:');
define('TEXT_DATE_ORDER_LAST_MODIFIED', 'Останні зміни:');
define('TEXT_INFO_PAYMENT_METHOD', 'Спосіб оплати:');

define('TEXT_ALL_ORDERS', 'Всі замовлення');
define('TEXT_NO_ORDER_HISTORY', 'Історія замовлення відсутня');

define('EMAIL_SEPARATOR','------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Статус Вашого замовлення змінено');
define('EMAIL_TEXT_ORDER_NUMBER', 'Номер замовлення:');
define('EMAIL_TEXT_INVOICE_URL', 'Інформація про замовлення:');
define('EMAIL_TEXT_DATE_ORDERED', 'Дата замовлення:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Статус Вашого замовлення змінено.'. "\n". 'Новий статус:%s'. "\n".' Якщо у Вас виникли питання, просто задайте нам їх у відповідному листі. '. "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Коментарі до Вашого замовлення'. "\n%s\n");

define('ERROR_ORDER_DOES_NOT_EXIST', 'Помилка: Замовлення не існує.');
define('SUCCESS_ORDER_UPDATED', 'Виконано: Замовлення успішно оновлено.');
define('WARNING_ORDER_NOT_UPDATED', 'Увага: Змінювати нема чого. Замовлення НЕ було оновлено.');
?>
