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

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'setflag':
        if (isset($_GET['pID'], $_GET['flag']) && ($_GET['flag'] == 0 || $_GET['flag'] == 1)) {
          tep_db_query("update information_pages set pages_status = '" . (int)$_GET['flag']. "' where pages_id = '" . (int)$_GET['pID']. "'");
        }

        tep_redirect(tep_href_link('information.php', (isset($_GET['page']) ? 'page=' . $_GET['page'] . '&' : '') . 'pID=' . $_GET['pID']));
        break;
      case 'insert':
      case 'update':
        if (isset($_POST['pID'])) $pages_id = tep_db_prepare_input($_POST['pID']);

        $sql_data_array = array('pages_status' => (int)tep_db_prepare_input($_POST['pages_status']),
                                'sort_order' => (int)tep_db_prepare_input($_POST['sort_order']));

        if ($action == 'insert') {
          $insert_sql_data = array('pages_date_added' => 'now()');
  
          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
  
          tep_db_perform('information_pages', $sql_data_array);
          $pages_id = tep_db_insert_id();
        } elseif ($action == 'update') {
          $update_sql_data = array('pages_last_modified' => 'now()');
  
          $sql_data_array = array_merge($sql_data_array, $update_sql_data);
  
          tep_db_perform('information_pages', $sql_data_array, 'update', "pages_id = '" . (int)$pages_id . "'");
        }
      
        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];
  
          $sql_data_array = array('pages_name' => tep_db_prepare_input($_POST['pages_name'][$language_id]),
                                  'pages_content' => tep_db_prepare_input($_POST['pages_content'][$language_id]));
  
          if ($action == 'insert') {
            $insert_sql_data = array('pages_id' => $pages_id,
                                     'language_id' => $language_id);
  
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
  
            tep_db_perform('information_pages_content', $sql_data_array);
          } elseif ($action == 'update') {
            tep_db_perform('information_pages_content', $sql_data_array, 'update', "pages_id = '" . (int)$pages_id . "' and language_id = '" . (int)$language_id . "'");
          }
        }

        tep_redirect(tep_href_link('information.php', 'page=' . $_GET['page'] . '&pID=' . $pages_id));
        break;
      case 'deleteconfirm':
        $pages_id = tep_db_prepare_input($_GET['pID']);

        tep_db_query("delete from information_pages where pages_id = '" . (int)$pages_id . "'");
        tep_db_query("delete from information_pages_content where pages_id = '" . (int)$pages_id . "'");

        tep_redirect(tep_href_link('information.php', 'page=' . $_GET['page']));
        break;
    }
  }

  require('includes/template_top.php');

  $base_url = ($request_type == 'SSL') ? HTTPS_SERVER . DIR_WS_HTTPS_ADMIN : HTTP_SERVER . DIR_WS_ADMIN;
?>

    <h1 class="pageHeading"><?php echo HEADING_TITLE; ?></h1>

<?php
  if (($action == 'new') || ($action == 'edit')) {
    $form_action = 'insert';

    $information = array('pages_id' => '',
                         'pages_name' => '',
                         'pages_content' => '',
                         'pages_status' => '',
                         'sort_order' => '');

    $iInfo = new objectInfo($information);

    if (($action == 'edit') && isset($_GET['pID'])) {
      $form_action = 'update';

      $information_query = tep_db_query("select ip.*, ipc.* from information_pages ip, information_pages_content ipc where ip.pages_id = '" . (int)$_GET['pID'] . "' and ip.pages_id = ipc.pages_id and ipc.language_id = '" . (int)$languages_id . "'");
      $information = tep_db_fetch_array($information_query);

      $iInfo = new objectInfo($information);
    }

    $languages = tep_get_languages();

    if (!isset($iInfo->pages_status)) {
      $iInfo->pages_status = '1';
    }
      
    switch ($iInfo->pages_status) {
      case '0': 
        $in_status = false; 
        $out_status = true;
        break;
      case '1':
      default: 
        $in_status = true; 
        $out_status = false;
    }
?>
      <form name="new_information_page" <?php echo 'action="' . tep_href_link('information.php', tep_get_all_get_params(array('action', 'info', 'pID')) . 'action=' . $form_action) . '"'; ?> method="post"><?php if ($form_action == 'update') echo tep_draw_hidden_field('pID', $_GET['pID']); ?>

        <div id="informationTabs" style="overflow: auto;">
          <ul id="informationTabsMain">
            <li><?php echo '<a href="' . substr(tep_href_link('information.php', tep_get_all_get_params()), strlen($base_url)) . '#section_general_content">' . SECTION_HEADING_GENERAL . '</a>'; ?></li>
            <li><?php echo '<a href="' . substr(tep_href_link('information.php', tep_get_all_get_params()), strlen($base_url)) . '#section_data_content">' . SECTION_HEADING_DATA . '</a>'; ?></li>
          </ul>

          <div id="section_general_content" style="padding: 10px;">
            <div id="informationLanguageTabs">
              <ul>

                <?php
                for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                  echo '<li><a href="' . substr(tep_href_link('information.php', tep_get_all_get_params()), strlen($base_url)) . '#section_general_content_' . $languages[$i]['directory'] . '">' . $languages[$i]['name'] . '</a></li>';
                }
                ?>

              </ul>

              <?php
              for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                $information_query = tep_db_query("select ip.*, ipc.* from information_pages ip, information_pages_content ipc where ip.pages_id = '" . (int)$iInfo->pages_id . "' and ip.pages_id = ipc.pages_id and ipc.language_id = '" . (int)$languages[$i]['id'] . "'");
                $information = tep_db_fetch_array($information_query);
                ?>

                <div id="section_general_content_<?php echo $languages[$i]['directory']; ?>">
                  <table border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main"><?php echo TEXT_PAGE_NAME; ?></td>
                      <td class="main"><?php echo tep_draw_input_field('pages_name[' . $languages[$i]['id'] . ']', (empty($iInfo->pages_id) ? '' : $information['pages_name'])); ?></td>
                    </tr>
                    <tr>
                      <td class="main" valign="top"><?php echo TEXT_PAGE_CONTENT; ?></td>
                      <td class="main"><?php echo tep_draw_textarea_field('pages_content[' . $languages[$i]['id'] . ']', null, '70', '15', (empty($iInfo->pages_id) ? '' : $information['pages_content'])); ?></td>
                    </tr>
                  </table>
                </div>

                <?php
              }
              ?>

            </div>
          </div>

          <div id="section_data_content" style="padding: 10px;">
            <table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><?php echo TEXT_PAGE_STATUS; ?></td>
                <td class="main"><?php echo tep_draw_radio_field('pages_status', '1', $in_status) . '&nbsp;' . TEXT_PAGE_ENABLED . '&nbsp;' . tep_draw_radio_field('pages_status', '0', $out_status) . '&nbsp;' . TEXT_PAGE_DISABLED; ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_PAGE_SORT_ORDER; ?></td>
                <td class="main"><?php echo tep_draw_input_field('sort_order', $iInfo->sort_order); ?></td>
              </tr>
            </table>
          </div>

        </div>

        <script>
          $(function() {
            $('#informationTabs').tabs();
            $('#informationLanguageTabs').tabs();
          });
        </script>

        <div style="padding-top: 15px; text-align: right;">
          <?php echo tep_draw_button(IMAGE_SAVE, 'disk', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('information.php', 'page=' . $_GET['page'] . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : ''))); ?>
        </div>

      </form>
<?php
  } else {
?>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PAGES_NAME; ?></td>
              <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
              <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
              <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
            </tr>
<?php
    $information_query_raw = "select ip.*, ipc.* from information_pages ip, information_pages_content ipc where ip.pages_id = ipc.pages_id and ipc.language_id = '" . (int)$languages_id . "' order by ipc.pages_name";
    $information_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $information_query_raw, $information_query_numrows);
    $information_query = tep_db_query($information_query_raw);
    while ($information = tep_db_fetch_array($information_query)) {
      if ((!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $information['pages_id']))) && !isset($iInfo)) {
        $iInfo = new objectInfo($information);
      }

      if (isset($iInfo) && is_object($iInfo) && ($information['pages_id'] == $iInfo->pages_id)) {
        echo '                  <tr id="defaultSelected" class="dataTableRowSelected" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('information.php', 'page=' . $_GET['page'] . '&pID=' . $iInfo->pages_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href=\'' . tep_href_link('information.php', 'page=' . $_GET['page'] . '&pID=' . $information['pages_id']) . '\'">' . "\n";
      }
?>
              <td  class="dataTableContent"><?php echo $information['pages_name']; ?></td>
              <td  class="dataTableContent" align="center">
<?php
      if ($information['pages_status'] == '1') {
        echo tep_image('images/icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '&nbsp;&nbsp;<a href="' . tep_href_link('information.php', 'action=setflag&flag=0&pID=' . $information['pages_id']) . '">' . tep_image('images/icon_status_red_light.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . tep_href_link('information.php', 'action=setflag&flag=1&pID=' . $information['pages_id']) . '">' . tep_image('images/icon_status_green_light.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>&nbsp;&nbsp;' . tep_image('images/icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10);
      }
?></td>
              <td class="dataTableContent" align="center"><?php echo $information['sort_order']; ?></td>
              <td class="dataTableContent" align="right"><?php if (isset($iInfo) && is_object($iInfo) && ($information['pages_id'] == $iInfo->pages_id)) { echo tep_image('images/icon_arrow_right.gif', ''); } else { echo '<a href="' . tep_href_link('information.php', 'page=' . $_GET['page'] . '&pID=' . $information['pages_id']) . '">' . tep_image('images/icon_info.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
      </tr>
<?php
    }
?>
            <tr>
              <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $information_split->display_count($information_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_INFORMATION_PAGES); ?></td>
                    <td class="smallText" align="right"><?php echo $information_split->display_links($information_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                  <tr>
                    <td class="smallText" colspan="2" align="right"><?php echo tep_draw_button(IMAGE_NEW_PAGE, 'plus', tep_href_link('information.php', 'page=' . $_GET['page'] . '&action=new')); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<strong>' . TEXT_INFO_HEADING_DELETE_PAGE . '</strong>');

      $contents = array('form' => tep_draw_form('information_pages', 'information.php', 'page=' . $_GET['page'] . '&pID=' . $iInfo->pages_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><strong>' . $iInfo->pages_name . '</strong>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . tep_draw_button(IMAGE_DELETE, 'trash', null, 'primary') . tep_draw_button(IMAGE_CANCEL, 'close', tep_href_link('information.php', 'page=' . $_GET['page'] . '&pID=' . $iInfo->pages_id)));
      break;
    default:
      if (isset($iInfo) && is_object($iInfo)) {
        $heading[] = array('text' => '<strong>' . $iInfo->pages_name . '</strong>');

        $contents[] = array('align' => 'center', 'text' => tep_draw_button(IMAGE_EDIT, 'document', tep_href_link('information.php', 'page=' . $_GET['page'] . '&pID=' . $iInfo->pages_id . '&action=edit')) . tep_draw_button(IMAGE_DELETE, 'trash', tep_href_link('information.php', 'page=' . $_GET['page'] . '&pID=' . $iInfo->pages_id . '&action=delete')));
        $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_ADDED . ' ' . tep_date_short($iInfo->pages_date_added));
        if (!empty($iInfo->pages_last_modified)) {
          $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . tep_date_short($iInfo->pages_last_modified));
        }
      }
      break;
  }
  if ( (!empty($heading)) && (!empty($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table>

<?php
}
  require('includes/template_bottom.php');
  require('includes/application_bottom.php');
?>
