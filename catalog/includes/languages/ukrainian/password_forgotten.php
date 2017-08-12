<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Вхід');
define('NAVBAR_TITLE_2', 'Відновлення пароля');

define('HEADING_TITLE', 'Я забув свій пароль!');

define('TEXT_MAIN', 'Якщо Ви забули свій пароль, введіть свою E-Mail адресу й ми відправимо Ваш парoль на E-Mail, який Ви вказали.');

define('TEXT_PASSWORD_RESET_INITIATED', 'Будь ласка, перевірте Ваш емейл - там повинна бути інструкція по зміні пароля. Інструкція містить посилання, скористатися яким можна тільки протягом 24 годин і тільки поки Ви не зміните пароль.');

define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<span class="uk-text-danger">Помилка:</span> E-Mail адреса не відповідає Вашому обліковому запису, спробуйте ще раз.');

define('EMAIL_PASSWORD_RESET_SUBJECT', STORE_NAME . ' - Новий пароль');
define('EMAIL_PASSWORD_RESET_BODY', 'Для Вашого облікового запису в ' . STORE_NAME . ' був запит на новий пароль.' . "\n\n" . 'Будь ласка пройдіть по цьому персональному посиланню для безпечної зміни пароля:' . "\n\n" . '%s' . "\n\n" . 'Це посилання буде автоматично видалено після 24 годин або після зміни пароля.' . "\n\n" . 'Якщо Вам потрібна допомога напишіть нам: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n");

define('ERROR_ACTION_RECORDER', 'Помилка: Посилання для зміни пароля вже вислано. Будь ласка спробуйте ще раз через %s хвилин.');
?>