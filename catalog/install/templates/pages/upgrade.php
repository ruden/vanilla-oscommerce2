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
  <div class="stepsBox">
    <ol>
      <li style="font-weight: bold;">Check Upgrade</li>
      <li>Database Settings</li>
      <li>Move Images</li>
      <li>Finished!</li>
    </ol>
  </div>

  <h1>Upgrade osCommerce</h1>

  <p>This web-based installation routine will correctly setup and configure osCommerce Online Merchant to run on this server.</p>
  <p>Please follow the on-screen instructions that will take you through the database server, web server, and store configuration options. If help is needed at any stage, please consult the documentation or seek help at the community support forums.</p>
</div>

<div class="contentBlock">
  <div class="infoPane">
    <h3>Step 1: Check Upgrade</h3>

    <div class="infoPaneContents">
      <p>Check Upgrade</p>
    </div>

  </div>

  <div class="contentPane">
    <div id="mBox">
      <div id="mBoxContents"></div>
    </div>

    <h2>Check Upgrade</h2>

    <?php
    $path_array = array('images/categories',
                        'images/manufacturers',
                        'images/products/originals',
                        'images/products/thumbs');

    $configfile_array = check_permissions($path_array);

    $warning_array = array(osc_realpath(dirname(__FILE__) . '/../../../includes') . '/configure.php',
                           osc_realpath(dirname(__FILE__) . '/../../../admin/includes') . '/configure.php');

    if (file_exists(osc_realpath(dirname(__FILE__) . '/../../../admin/includes/local') . '/configure.php')) {
      include(osc_realpath(dirname(__FILE__) . '/../../../admin/includes/local') . '/configure.php');
    } else {
      include($warning_array[1]);
    }

    if (strlen(DB_SERVER) > 1) {
      $warning_array = array();
    }

    if ((sizeof($configfile_array) > 0) || (sizeof($warning_array) > 0)) {
      ?>

      <div class="noticeBox">

        <?php
        if (sizeof($warning_array) > 0) {
          ?>

          <p>Upload configuration files.</p>
          <p>

            <?php
            for ($i = 0, $n = sizeof($warning_array); $i < $n; $i++) {
              echo $warning_array[$i];

              if (isset($warning_array[$i + 1])) {
                echo '<br />';
              }
            }
            ?>

          </p>

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

      <p>Please correct the above errors and retry the upgrade procedure with the changes in place.</p>

      <p><?php echo osc_draw_button('Retry', 'arrowrefresh-1-e', 'upgrade.php', 'primary'); ?></p>

      <?php
    } else {
      ?>

      <p>The webserver environment has been verified to proceed with a successful upgrade and configuration of your online store.</p>
      <p>Please continue to start the upgrade procedure.</p>

      <p><?php echo osc_draw_button('Continue', 'triangle-1-e', 'upgrade.php?step=2', 'primary'); ?></p>

      <?php
    }
    ?>

  </div>
</div>
