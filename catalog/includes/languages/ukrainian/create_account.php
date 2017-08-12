<?php
/*
  $Id: create_account.php,v 1.11 2003/07/05 13:58:31 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE', 'Реєстрація');

define('HEADING_TITLE', 'Мої дані');

define('TEXT_ORIGIN_LOGIN', '<span class="uk-text-danger">УВАГА:</span> Якщо Ви вже зареєстровані на нашому сайті, введіть, будь ласка, Ваш логін і пароль&nbsp;<a href="%s"><u>тут</u></a>.');

define('EMAIL_SUBJECT', 'Ласкаво просимо в ' . STORE_NAME);
define('EMAIL_GREET_MR', 'Шановний пан %s,' . "\n\n");
define('EMAIL_GREET_MS', 'Шановна пані %s,' . "\n\n");
define('EMAIL_GREET_NONE', 'Шановний %s' . "\n\n");
define('EMAIL_WELCOME', 'Ми раді запросити Вас в інтернет-магазин <strong>' . STORE_NAME . '</strong>.' . "\n\n");
define('EMAIL_TEXT', 'Зараз Ви можете скористатися <strong>додатковими послугами</strong>, які ми раді Вам запропонувати. Ці послуги включають:' . "\n\n" . '<li><strong>Постійний кошик</strong> - Будь-які товари, додані в кошик залишаються там доти, поки Ви не розв’яжете їх придбати або поки не вилучите їх з кошика.' . "\n" . '<li><strong>Адресна книга</strong> - Ми можемо доправити придбані Вами товари по зазначеній адресі, а не тільки на Вашу домашню адресу! Це - відмінна пропозиція, щоб посилати подарунки до дня народження або на свята, Вашій родичці й друзям, навіть якщо вони живуть в іншому місті.' . "\n" . '<li><strong>Історія Замовлень</strong> - Тут Ви можете подивитися історію замовлень, які Ви зробили в нашому магазині.' . "\n" . '<li><strong>Огляди продуктів</strong> - Тепер наші покупці можуть висловити своя думка про товари, придбані в нашому магазині. Ваша думка буде доступно широкої аудиторії покупців, які напевно потребують споживчої оцінки різних товарів.' . "\n");
define('EMAIL_CONTACT', 'Якщо у Вас виникли які-небудь питання, пишіть: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");
define('EMAIL_WARNING', '<strong>Увага:</strong> Ця E-Mail адреса була надана нам одним з наших клієнтів. Якщо Ви ще не зареєструвалися й не є членом нашого клубу споживачів, повідомите нас про це на ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n");
?>
