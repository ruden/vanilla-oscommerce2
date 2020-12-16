<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;

function tep_validate_email($email) {
  $validator = new EmailValidator();

  if (ENTRY_EMAIL_ADDRESS_CHECK == 'true') {
    $multipleValidations = new MultipleValidationWithAnd([
      new RFCValidation(),
      new DNSCheckValidation()
    ]);
  } else {
    $multipleValidations = new MultipleValidationWithAnd([
      new RFCValidation()
    ]);
  }

  return $validator->isValid(trim($email), $multipleValidations);;
}
