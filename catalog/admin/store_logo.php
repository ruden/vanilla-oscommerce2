<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    $error = false;

    switch ($action) {
      case 'save_logo':
        $store_logo = new upload('store_logo');
        $store_logo->set_extensions(array('png', 'jpg', 'jpeg', 'gif', 'svg'));
        $store_logo->set_destination(DIR_FS_CATALOG_IMAGES);

        if ($store_logo->parse()) {
          $store_logo->set_filename('store_logo.png');

          if ($store_logo->save()) {
            $messageStack->add_session(SUCCESS_LOGO_UPDATED, 'success');
          } else {
            $error = true;
          }
        } else {
          $error = true;
        }
        break;
      case 'save_favicon':
        $store_favicon = new upload('store_favicon');
        $store_favicon->set_extensions('ico');
        $store_favicon->set_destination(DIR_FS_CATALOG);

        if ($store_favicon->parse()) {
          $store_favicon->set_filename('favicon.ico');

          if ($store_favicon->save()) {
            $messageStack->add_session(SUCCESS_FAVICON_UPDATED, 'success');
          } else {
            $error = true;
          }
        } else {
          $error = true;
        }
        break;
    }

    if ($error == false) {
      tep_redirect(tep_href_link('store_logo.php'));
    }
  }

  if (!tep_is_writable(DIR_FS_CATALOG_IMAGES)) {
    $messageStack->add(sprintf(ERROR_IMAGES_DIRECTORY_NOT_WRITEABLE, tep_href_link('sec_dir_permissions.php')), 'error');
  }

  require('includes/template_top.php');
?>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_image(tep_catalog_href_link('images/store_logo.png')); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_form('logo', 'store_logo.php', 'action=save_logo', 'post', 'enctype="multipart/form-data"'); ?>
          <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main" valign="top"><?php echo TEXT_LOGO_IMAGE; ?></td>
              <td class="main"><?php echo tep_draw_file_field('store_logo'); ?></td>
              <td class="smallText"><?php echo tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary'); ?></td>
            </tr>
          </table>
        </form></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_FORMAT_AND_LOCATION; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo DIR_FS_CATALOG . 'images/' . 'store_logo.png'; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_image(tep_catalog_href_link('favicon.ico')); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_form('favicon', 'store_logo.php', 'action=save_favicon', 'post', 'enctype="multipart/form-data"'); ?>
          <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main" valign="top"><?php echo TEXT_FAVICON_IMAGE; ?></td>
              <td class="main"><?php echo tep_draw_file_field('store_favicon'); ?></td>
              <td class="smallText"><?php echo tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary'); ?></td>
            </tr>
          </table>
          </form></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo TEXT_FAVICON_FORMAT_AND_LOCATION; ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo DIR_FS_CATALOG . 'favicon.ico'; ?></td>
      </tr>
    </table>

<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
