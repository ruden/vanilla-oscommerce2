<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2020 osCommerce

  Released under the GNU General Public License
*/

include(osc_realpath(dirname(__FILE__) . '/../../../includes') . '/configure.php');

$db_error = false;

if (strlen(DB_SERVER) > 1) {
  osc_db_connect(DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);

  if ($db_error == false) {
    osc_db_select_db(DB_DATABASE);
  }
}
?>

<div class="mainBlock">
  <div class="stepsBox">
    <ol>
      <li>Check Upgrade</li>
      <li style="font-weight: bold;">Database Settings</li>
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
    <h3>Step 2: Database Settings</h3>

    <div class="infoPaneContents">
      <p>The web server takes care of serving the pages of your online store to your guests and customers. The web server parameters make sure the links to the pages point to the correct location.</p>
    </div>
  </div>

  <div class="contentPane">
    <h2>Web Server</h2>

    <?php
    if ($db_error != false) {
      ?>

      <div class="noticeBox">

        <?php echo '[[0|' . $db_error . ']]'; ?>

        <p><?php echo osc_draw_button('Retry', 'arrowrefresh-1-e', 'upgrade.php?step=2', 'primary'); ?></p>

      </div>

      <?php
    } else {
      ?>



      <?php
    }
    ?>

  </div>
</div>
