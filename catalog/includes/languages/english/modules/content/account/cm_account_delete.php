<?php
/*
$Id$

osCommerce, Open Source E-Commerce Solutions
http://www.oscommerce.com

Copyright (c) 2020 osCommerce

Released under the GNU General Public License
*/

define('MODULE_CONTENT_ACCOUNT_DELETE_TITLE', 'Delete Account');
define('MODULE_CONTENT_ACCOUNT_DELETE_DESCRIPTION', 'Show the delete account link on the account page');
define('MODULE_CONTENT_ACCOUNT_DELETE_LINK_TITLE', 'Delete my account');

define('MODULE_CONTENT_ACCOUNT_DELETE_NAVBAR_TITLE_1', 'My Account');
define('MODULE_CONTENT_ACCOUNT_DELETE_NAVBAR_TITLE_2', 'Delete My Account');
define('MODULE_CONTENT_ACCOUNT_DELETE_HEADING_TITLE', 'Delete My Account');

define('MODULE_CONTENT_ACCOUNT_DELETE_TEXT_NO_DELETE_LINK_FOUND', 'Error: The account delete link was not found in our records, please try again by generating a new link.');
define('MODULE_CONTENT_ACCOUNT_DELETE_TEXT_INITIATED', 'Please check your registered email address to confirm and delete your account permanently.');

define('MODULE_CONTENT_ACCOUNT_DELETE_TEXT_INFORMATION', 'Deleting your account will remove all the purchase history, discounts, orders, invoices and all other information that might be related to your account or your purchases.<br />All your orders and similar information will be lost.<br />You will not be able to restore access to your account after we approve your removal request.');
define('MODULE_CONTENT_ACCOUNT_DELETE_TEXT_CONSENT', 'I understand and I want to delete my account ');

define('MODULE_CONTENT_ACCOUNT_DELETE_EMAIL_SUBJECT', 'Delete account request confirmation from' . STORE_NAME);
define('MODULE_CONTENT_ACCOUNT_DELETE_EMAIL_BODY', 'Hello %s,' . "\n\n" . 'You\'ve received this email because we\'ve have been notified to delete your account from ' . STORE_NAME . '. After deleting your account, you will permanently loose all your account information stored in our ' . STORE_NAME . '.' . "\n\n" . 'I understand this and confirm to delete my account, please follow this link' . "\n\n" . '%s');
define('MODULE_CONTENT_ACCOUNT_DELETE_TEXT_SUCCESS_ACCOUNT_DELETE', 'Your account was successfully deleted!');
