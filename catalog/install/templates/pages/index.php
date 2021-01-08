<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/
?>

<div class="mainBlock">
  <h1>Welcome to osCommerce Online Merchant v<?php echo osc_get_version(); ?>!</h1>

  <p>osCommerce Online Merchant helps you sell products worldwide with your own online store. Its Administration Tool manages products, customers, orders, newsletters, specials, and more to successfully build the success of your online business.</p>
  <p>osCommerce has attracted a large community of store owners and developers who support each other and have provided over 7,000 free add-ons that can extend the features and potential of your online store.</p>
</div>

<div class="contentBlock">
  <div class="infoPane">
    <h3>Server Capabilities</h3>

    <div class="infoPaneContents">
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td><strong>PHP Version</strong></td>
          <td align="right"><?php echo PHP_VERSION; ?></td>
          <td align="right" width="25">
            <img src="images/<?php echo((PHP_VERSION >= 4) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16"/>
          </td>
        </tr>
      </table>

      <?php
      if (function_exists('ini_get')) {
        ?>

        <br/>

        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"><strong>PHP Settings</strong></td>
          </tr>
          <tr>
            <td>file_uploads</td>
            <td align="right"><?php echo(((int)ini_get('file_uploads') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right">
              <img src="images/<?php echo(((int)ini_get('file_uploads') == 1) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16"/>
            </td>
          </tr>
          <tr>
            <td>session.auto_start</td>
            <td align="right"><?php echo(((int)ini_get('session.auto_start') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right">
              <img src="images/<?php echo(((int)ini_get('session.auto_start') == 0) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16"/>
            </td>
          </tr>
          <tr>
            <td>session.use_trans_sid</td>
            <td align="right"><?php echo(((int)ini_get('session.use_trans_sid') == 0) ? 'Off' : 'On'); ?></td>
            <td align="right">
              <img src="images/<?php echo(((int)ini_get('session.use_trans_sid') == 0) ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16"/>
            </td>
          </tr>
        </table>

        <br/>

        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><strong>Required PHP Extensions</strong></td>
          </tr>
          <tr>
            <td>MySQLi</td>
            <td align="right">
              <img src="images/<?php echo(extension_loaded('mysqli') ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16"/>
            </td>
          </tr>
        </table>

        <br/>

        <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><strong>Recommended PHP Extensions</strong></td>
          </tr>
          <tr>
            <td>GD</td>
            <td align="right">
              <img src="images/<?php echo(extension_loaded('gd') ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16"/>
            </td>
          </tr>
          <tr>
            <td>cURL</td>
            <td align="right">
              <img src="images/<?php echo(extension_loaded('curl') ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16"/>
            </td>
          </tr>
          <tr>
            <td>OpenSSL</td>
            <td align="right">
              <img src="images/<?php echo(extension_loaded('openssl') ? 'success.gif' : 'failed.gif'); ?>" width="16" height="16"/>
            </td>
          </tr>
        </table>

        <?php
      }
      ?>

    </div>
  </div>

  <div class="contentPane">
    <h2>New Installation</h2>

    <?php
    $path_array = array('includes' => 'configure.php',
                        'admin/includes' => 'configure.php');

    $configfile_array = check_permissions($path_array);

    $warning_array = array();

    if (!extension_loaded('mysqli')) {
      $warning_array['mysql'] = 'The MySQLi or older MySQL extension is required but is not installed. Please enable either to continue installation.';
    }

    if ((sizeof($configfile_array) > 0) || (sizeof($warning_array) > 0)) {
      ?>

      <div class="noticeBox">

        <?php
        if (sizeof($warning_array) > 0) {
          ?>

          <table border="0" width="100%" cellspacing="0" cellpadding="2" style="background: #fffbdf; border: 1px solid #ffc20b; padding: 2px;">

            <?php
            foreach ($warning_array as $key => $value) {
              echo '        <tr>' . "\n" .
                   '          <td valign="top"><strong>' . $key . '</strong></td>' . "\n" .
                   '          <td valign="top">' . $value . '</td>' . "\n" .
                   '        </tr>' . "\n";
            }
            ?>

          </table>
          <?php
        }

        if (sizeof($configfile_array) > 0) {
          ?>

          <p>The webserver is not able to save the installation parameters to its configuration files.</p>
          <p>The following files need to have their file permissions set to world-writeable (chmod 777):</p>
          <p>

            <?php
            for ($i = 0, $n = sizeof($configfile_array); $i < $n; $i++) {
              echo $configfile_array[$i];

              if (isset($configfile_array[$i + 1])) {
                echo '<br />';
              }
            }
            ?>

          </p>

          <?php
        }
        ?>

      </div>

      <?php
    }

    if ((sizeof($configfile_array) > 0) || (sizeof($warning_array) > 0)) {
      ?>

      <p>Please correct the above errors and retry the installation procedure with the changes in place.</p>

      <?php
      if (sizeof($warning_array) > 0) {
        echo '    <p><i>Changing webserver configuration parameters may require the webserver service to be restarted before the changes take affect.</i></p>' . "\n";
      }
      ?>

      <p><?php echo osc_draw_button('Retry', 'arrowrefresh-1-e', 'index.php', 'primary'); ?></p>

    <?php
    } else {
    ?>

      <p>The webserver environment has been verified to proceed with a successful installation and configuration of your online store.</p>

      <div id="jsOn" style="display: none;">
        <form name="install" id="installForm" action="install.php" method="post">
          <div class="mainBlock inputDescription">
            <p>In order to use HTTPS, you need to obtain an SSL certificate and bind it to the website that hosts. Each web server has its own procedure for loading a certificate and binding it to a website.</p>
            <p><?php echo osc_draw_input_field('HTTPS', 'on', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'checked' : ''), true, 'checkbox'); ?> Enable HTTPS</p>
          </div>
          <p>Please continue to start the installation procedure.</p>
          <p><?php echo osc_draw_button('Start', 'triangle-1-e', null, 'primary'); ?></p>
        </form>
      </div>

      <div id="jsOff">
        <p>Please enable Javascript in your browser to be able to start the installation procedure.</p>
        <p><?php echo osc_draw_button('Retry', 'arrowrefresh-1-e', 'index.php', 'primary'); ?></p>
      </div>

      <script>
        $(function () {
          $('#jsOff').hide();
          $('#jsOn').show();
        });
      </script>

      <?php
    }
    ?>

  </div>

  <!--<div class="contentPane">
    <h2>Upgrade osCommerce</h2>

    <p>The webserver environment has been verified to proceed with a successful installation and configuration of your online store.</p>
    <p>Please continue to start the installation procedure.</p>

    <p><?php /*echo osc_draw_button('Start', 'triangle-1-e', 'upgrade.php', 'primary'); */ ?></p>

  </div>-->
</div>
