<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');

function tep_sort_imgmodules($x, $y) {
  return strcasecmp($x['title'], $y['title']);
}

$module = (isset($_GET['module']) ? $_GET['module'] : '');

$modules = array();
$modules_array = array();

if ($imgdir = @dir(DIR_FS_ADMIN . 'includes/modules/images/')) {
  while ($file = $imgdir->read()) {
    if (!is_dir(DIR_FS_ADMIN . 'includes/modules/images/' . $file)) {
      if (substr($file, strrpos($file, '.')) == '.php') {
        $class = 'Images_' . substr($file, 0, strrpos($file, '.'));

        include(DIR_FS_ADMIN . 'includes/languages/' . $language . '/modules/images/' . $file);
        include(DIR_FS_ADMIN . 'includes/modules/images/' . $file);
        $$class = new $class();

        $modules[] = array('title' => $$class->title,
                           'directory' => $$class->directory,
                           'class' => $class);

        $modules_array[] = $class;
      }
    }
  }

  $imgdir->close();
}

usort($modules, 'tep_sort_imgmodules');

$action = (isset($_GET['action']) ? $_GET['action'] : '');

$counter = array('success' => null,
                 'warning' => null);

if (!empty($action)) {
  switch ($action) {
    case 'resize':
      if (isset($_POST['module'])) {
        foreach ($_POST['module'] as $module) {
          if (in_array($module, $modules_array)) {
            $class = ${$module};

            if (is_object($class) && $class->action == 'resize') {
              foreach ($class->getOutput() as $id => $images) {
                foreach ($images as $image) {
                  set_time_limit(0);

                  if (isset($_POST['overwrite']) && $_POST['overwrite'] == 'on') {
                    if (file_exists(DIR_FS_CATALOG . 'images/' . $class->origin_directory . $image)) {
                      tep_resize_image(DIR_FS_CATALOG . 'images/' . $class->origin_directory . $image, DIR_FS_CATALOG . 'images/' . $class->directory . $image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);

                      $counter['success']++;
                    } else {
                      $counter['warning']++;
                    }
                  } else {
                    if (!file_exists(DIR_FS_CATALOG . 'images/' . $class->directory . $image)) {
                      if (file_exists(DIR_FS_CATALOG . 'images/' . $class->origin_directory . $image)) {
                        tep_resize_image(DIR_FS_CATALOG . 'images/' . $class->origin_directory . $image, DIR_FS_CATALOG . 'images/' . $class->directory . $image, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);

                        $counter['success']++;
                      } else {
                        $counter['warning']++;
                      }
                    }
                  }
                }
              }

              if (!empty($counter)) {
                if (isset($counter['success'])) {
                  $messageStack->add_session(sprintf('Success %s thumbs images', $counter['success']), 'success');
                }

                if (isset($counter['warning'])) {
                  $messageStack->add_session(sprintf('Not origin %s images', $counter['warning']), 'warning');
                }
              } else {
                $messageStack->add_session('Not images for working', 'warning');
              }

              tep_redirect(tep_href_link('images.php'));
            }
          }
        }
      }
      break;
  }
}

require('includes/template_top.php');

if ($module == 'resize') {
  ?>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_1; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <form name="resize_images" <?php echo 'action="' . tep_href_link('images.php', 'action=resize') . '"'; ?> method="post">
          <table border="0" cellspacing="0" cellpadding="2">
            <tr>
              <td class="main" valign="top"><?php echo TEXT_IMAGES_GROUPS; ?>&nbsp;</td>
              <td class="main">
                <?php
                foreach ($modules as $module) {
                  $class = ${$module['class']};

                  if ($class->action == 'resize') {
                    echo tep_draw_checkbox_field('module[]', $module['class']) . ' ' . $module['title'] . '<br />';
                  }
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="main"><?php echo TEXT_OVERWRITE_IMAGES; ?>&nbsp</td>
              <td class="main"><?php echo tep_draw_checkbox_field('overwrite'); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="smallText" colspan="2" align="right">
                <?php echo tep_draw_button(IMAGE_RESIZE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('images.php')); ?>
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
  </table>

  <?php
} else {
  ?>

  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo tep_draw_button(TEXT_RESIZE_IMAGES, 'scissors', tep_href_link('images.php', 'module=resize'), 'primary'); ?></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_GROUPS; ?></td>
            <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DIRECTORY; ?></td>
            <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_EXISTING_TOTAL; ?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?></td>
          </tr>
          <?php
          foreach ($modules as $module) {
            $class = ${$module['class']};

            $counter = array('total' => 0,
                             'existing' => 0,
                             'item' => array());

            foreach ($class->getOutput() as $id => $images) {
              foreach ($images as $image) {
                $counter['total']++;

                if (file_exists(DIR_FS_CATALOG . 'images/' . $class->directory . $image)) {
                  $counter['existing']++;
                } else {
                  $counter['item'][$id][] = $image;
                }
              }
            }
            ?>
            <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
              <td class="dataTableContent"><?php echo tep_output_string_protected($module['title']); ?></td>
              <td class="dataTableContent"><?php echo tep_output_string_protected($module['directory']); ?></td>
              <td class="dataTableContent" align="center"><?php echo $counter['existing'] . '/' . $counter['total']; ?></td>
              <td class="dataTableContent" align="right">
                <?php
                if ($class->action == 'resize') {
                  echo '<a href="' . tep_href_link('images.php', 'module=resize') . '">' . tep_image('images/icon_reset.gif', TEXT_RESIZE_IMAGES) . '</a>';
                }
                ?>
              </td>
            </tr>
            <?php
            $item_name = null;
            $function_name = $class->function_name;

            foreach ($counter['item'] as $id => $images) {
              if ($class->action == 'check') {
                if ($function_name($id, $languages_id) != $item_name) {
                  $item_name = $function_name($id, $languages_id);
                  ?>
                  <tr class="dataTableRowSelected">
                    <td colspan="4">&nbsp;<b><?php echo $item_name; ?></b></td>
                  </tr>
                  <?php
                }

                foreach ($images as $image) {
                  ?>
                  <tr class="dataTableRowSelected">
                    <td colspan="4">&nbsp;<?php echo $image; ?></td>
                  </tr>
                  <?php
                }
              }
            }
          }
          ?>
          <tr>
            <td class="smallText" colspan="3"><?php echo TEXT_IMAGES_DIRECTORY . ' ' . DIR_FS_CATALOG_IMAGES; ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <?php
}

require('includes/template_bottom.php');
require('includes/application_bottom.php');
?>
