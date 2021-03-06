<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

if (!isset($process)) {
  $process = false;
}

if (ACCOUNT_GENDER == 'true') {
  $male = $female = false;
  if (isset($gender)) {
    $male = ($gender == 'm') ? true : false;
    $female = !$male;
  } elseif (isset($entry['entry_gender'])) {
    $male = ($entry['entry_gender'] == 'm') ? true : false;
    $female = !$male;
  }
  ?>

  <div class="mb-3">
    <label class="form-check-label me-2" for="gender"><?php echo ENTRY_GENDER . (!empty(ENTRY_GENDER_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_GENDER_TEXT . '</span>' : ''); ?></label>
    <div class="form-check-inline">
      <label class="form-check-label">
        <?php echo tep_draw_radio_field('gender', 'm', $male, 'class="form-check-input"') . ' ' . MALE; ?>
      </label>
    </div>
    <div class="form-check-inline">
      <label class="form-check-label">
        <?php echo tep_draw_radio_field('gender', 'f', $female, 'class="form-check-input"') . ' ' . FEMALE; ?>
      </label>
    </div>
  </div>

  <?php
}
?>

<div class="mb-3">
  <label class="form-label" for="firstname"><?php echo ENTRY_FIRST_NAME . (!empty(ENTRY_FIRST_NAME_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_FIRST_NAME_TEXT . '</span>' : ''); ?></label>
  <?php echo tep_draw_input_field('firstname', (isset($entry['entry_firstname']) ? $entry['entry_firstname'] : ''), 'id="firstname" class="form-control"'); ?>
</div>
<div class="mb-3">
  <label class="form-label" for="lastname"><?php echo ENTRY_LAST_NAME . (!empty(ENTRY_LAST_NAME_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_LAST_NAME_TEXT . '</span>' : ''); ?></label>
  <?php echo tep_draw_input_field('lastname', (isset($entry['entry_lastname']) ? $entry['entry_lastname'] : ''), 'id="lastname" class="form-control"'); ?>
</div>

<?php
if (ACCOUNT_COMPANY == 'true') {
  ?>

  <div class="mb-3">
    <label class="form-label" for="company"><?php echo ENTRY_COMPANY . (!empty(ENTRY_COMPANY_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_COMPANY_TEXT . '</span>' : ''); ?></label>
    <?php echo tep_draw_input_field('company', (isset($entry['entry_company']) ? $entry['entry_company'] : ''), 'id="company" class="form-control"'); ?>
  </div>

  <?php
}
?>

<div class="mb-3">
  <label class="form-label" for="street-address"><?php echo ENTRY_STREET_ADDRESS . (!empty(ENTRY_STREET_ADDRESS_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_STREET_ADDRESS_TEXT . '</span>' : ''); ?></label>
  <?php echo tep_draw_input_field('street_address', (isset($entry['entry_street_address']) ? $entry['entry_street_address'] : ''), 'id="street-address" class="form-control"'); ?>
</div>

<?php
if (ACCOUNT_SUBURB == 'true') {
  ?>

  <div class="mb-3">
    <label class="form-label" for="suburb"><?php echo ENTRY_SUBURB . (!empty(ENTRY_SUBURB_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_SUBURB_TEXT . '</span>' : ''); ?></label>
    <?php echo tep_draw_input_field('suburb', (isset($entry['entry_suburb']) ? $entry['entry_suburb'] : ''), 'id="suburb" class="form-control"'); ?>
  </div>

  <?php
}
?>

<div class="mb-3">
  <label class="form-label" for="postcode"><?php echo ENTRY_POST_CODE . (!empty(ENTRY_POST_CODE_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_POST_CODE_TEXT . '</span>' : ''); ?></label>
  <?php echo tep_draw_input_field('postcode', (isset($entry['entry_postcode']) ? $entry['entry_postcode'] : ''), 'id="postcode" class="form-control"'); ?>
</div>
<div class="mb-3">
  <label class="form-label" for="city"><?php echo ENTRY_CITY . (!empty(ENTRY_CITY_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_CITY_TEXT . '</span>' : ''); ?></label>
  <?php echo tep_draw_input_field('city', (isset($entry['entry_city']) ? $entry['entry_city'] : ''), 'id="city" class="form-control"'); ?>
</div>

<?php
if (ACCOUNT_STATE == 'true') {
  ?>

  <div class="mb-3">
    <label class="form-label" for="state"><?php echo ENTRY_STATE . (!empty(ENTRY_STATE_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_STATE_TEXT . '</span>' : ''); ?></label>

    <?php
    if ($process == true) {
      if ($entry_state_has_zones == true) {
        $zones_array = array();
        $zones_query = tep_db_query("select zone_name from zones where zone_country_id = '" . (int)$country . "' order by zone_name");
        while ($zones_values = tep_db_fetch_array($zones_query)) {
          $zones_array[] = array('id' => $zones_values['zone_name'], 'text' => $zones_values['zone_name']);
        }
        echo tep_draw_pull_down_menu('state', $zones_array, null, 'id="state" class="form-select"');
      } else {
        echo tep_draw_input_field('state', null, 'id="state" class="form-control"');
      }
    } else {
      echo tep_draw_input_field('state', (isset($entry['entry_country_id']) ? tep_get_zone_name($entry['entry_country_id'], $entry['entry_zone_id'], $entry['entry_state']) : ''), 'id="state" class="form-control"');
    }
    ?>

  </div>

  <?php
}
?>

<div class="mb-3">
  <label class="form-label" for="country"><?php echo ENTRY_COUNTRY . (!empty(ENTRY_COUNTRY_TEXT) ? '<span class="text-danger ms-1">' . ENTRY_COUNTRY_TEXT . '</span>' : ''); ?></label>
  <?php echo tep_get_country_list('country', (isset($entry['entry_country_id']) ? $entry['entry_country_id'] : STORE_COUNTRY), 'id="country" class="form-select"'); ?>
</div>
