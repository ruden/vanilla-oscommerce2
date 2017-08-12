<?php
/*
  $Id: create_account_success.php,v 1.9 2002/11/19 01:48:08 dgw_ Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

use OSC\OM\OSCOM;

define('NAVBAR_TITLE_1', 'Реєстрація');
define('NAVBAR_TITLE_2', 'Успішно');
define('HEADING_TITLE', 'Ваш аккаунт успішно створений!');
define('TEXT_ACCOUNT_CREATED', 'Вітаємо! Ваша реєстрація успішно завершена! Ви отримуєте всі привілеї зареєстрованого користувача нашого магазину. Якщо у Вас виникли питання, будь ласка, <a href="' . OSCOM::link('contact_us.php') . '">напишіть нам</a>.<br><br>Підтвердження було вислано електронною поштою, за вказаною у реєстрації адресою. Якщо Ви не отримаєте підтвердження протягом години, будь ласка, <a href="' . OSCOM::link('contact_us.php') . '">зв’яжіться з нами</a>.');
?>