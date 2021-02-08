<div class="text-center mb-3">

  <?php
  foreach ($information_array as $information) {
    ?>

    <a class="me-2" href="<?php echo tep_href_link('information.php', 'pages_id=' . $information['pages_id']); ?>"><?php echo $information['pages_name']; ?></a>

    <?php
  }
  ?>

  <a href="<?php echo tep_href_link('contact_us.php'); ?>"><?php echo MODULE_FOOTER_INFORMATION_TEXT_CONTACT_US; ?></a>

</div>
