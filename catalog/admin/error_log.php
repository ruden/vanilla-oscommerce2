<?php
/**
 *   $Id$
 *
 *   osCommerce, Open Source E-Commerce Solutions
 *   http://www.oscommerce.com
 *
 *   Copyright (c) 2020 osCommerce
 *
 *   Released under the GNU General Public License
 */

  require('includes/application_top.php');

  $dir_error_logs = DIR_FS_CATALOG . 'includes/work/error_logs/';

  $log_files = (is_dir($dir_error_logs) ? glob($dir_error_logs . 'errors-*.txt') : []) ;

  $log_date = null;
  $log_date_array = array(array('id' => '', 'text' => TEXT_ALL_LOG));

  foreach ($log_files as $file) {
    preg_match('/errors-(\d+).txt/', $file, $matches);

    $log_date_array[] = array('id' => $matches[1], 'text' => date(DATE_FORMAT, strtotime($matches[1])));
  }

  if (isset($_GET['log_date']) && is_numeric($_GET['log_date'])) {
    $log_date = (int)$_GET['log_date'];

    $log_files = glob($dir_error_logs . 'errors-' . $log_date . '.txt');
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'remove':
        foreach ($log_files as $file) {
          if (file_exists($file)) {
            @unlink($file);
          }
        }

        $messageStack->add_session(SUCCESS_LOG_FILES_REMOVED, 'success');

        tep_redirect(tep_href_link('error_log.php'));

        break;
    }
  }

  require('includes/template_top.php');
?>

    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2" height="40">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="smallText" align="right">
<?php
                      echo tep_draw_form('filter', 'error_log.php', '', 'get');
                      echo TEXT_FILTER_DATE . ' ' . tep_draw_pull_down_menu('log_date', $log_date_array, $log_date, 'onchange="this.form.submit();"');
                      echo '</form>';
                      echo tep_draw_separator('pixel_trans.gif', '10', '1');
                      echo tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link('error_log.php', 'action=remove' . (isset($log_date) ? '&log_date=' . $log_date : '')), 'primary');
?>
                    </td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="dataTableHeadingRow">
                    <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MESSAGE; ?></td>
                  </tr>
<?php
                  foreach (array_reverse($log_files) as $file) {
                    $error_log = file($file);

                    foreach ($error_log as $error) {
?>
                      <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)">
                        <td class="dataTableContent"><?php echo str_replace(array(DIR_FS_CATALOG, ' ' . CFG_TIME_ZONE), '', $error); ?></td>
                      </tr>
<?php
                    }
                  }
?>
                  <tr>
                    <td colspan="2" class="smallText"><?php echo TEXT_LOG_DIRECTORY . ' ' . $dir_error_logs; ?></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
    </table>

<?php
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
