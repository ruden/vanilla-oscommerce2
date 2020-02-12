<div class="ui-widget infoBoxContainer">
  <div class="ui-widget-header infoBoxHeading"><?php echo MODULE_BOXES_INFORMATION_BOX_TITLE; ?></div>
  <div class="ui-widget-content infoBoxContents">

    <?php
    foreach ($information_array as $information) {
      ?>

      <a href="<?php echo  tep_href_link('information.php', 'pages_id=' . $information['pages_id']); ?>"><?php echo $information['pages_name']; ?></a><br>

      <?php
    }
    ?>

    <a href="<?php echo  tep_href_link('contact_us.php'); ?>"><?php echo  MODULE_BOXES_INFORMATION_BOX_CONTACT ; ?></a>
  </div>
</div>
