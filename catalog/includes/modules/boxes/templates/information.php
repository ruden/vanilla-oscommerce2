<div class="card mb-2">
  <div class="card-header"><?php echo MODULE_BOXES_INFORMATION_BOX_TITLE; ?></div>

  <div class="card-body">

    <?php
    foreach ($information_array as $information) {
      ?>

      <a class="card-link" href="<?php echo tep_href_link('information.php', 'pages_id=' . $information['pages_id']); ?>"><?php echo $information['pages_name']; ?></a><br>

      <?php
    }
    ?>

    <a class="card-link" href="<?php echo tep_href_link('contact_us.php'); ?>"><?php echo MODULE_BOXES_INFORMATION_BOX_CONTACT; ?></a>

  </div>

</div>
