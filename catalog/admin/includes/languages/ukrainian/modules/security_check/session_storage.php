<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2014 osCommerce

  Released under the GNU General Public License
*/

define('WARNING_SESSION_DIRECTORY_NON_EXISTENT', 'Директория сохранения сессий не существует: ' . tep_session_save_path() . '. Сессии не будут работать до тех пор, пока эта директория не будет создана.');
define('WARNING_SESSION_DIRECTORY_NOT_WRITEABLE', 'Директория сессий не доступна на запись: ' . tep_session_save_path() . '. Сессии не будут работать до тех пор, пока не будут установлены правильные права доступа.');
?>