<div><?php echo MODULE_FOOTER_INFORMATION_TITLE; ?></div>

<?php
foreach ($information_array as $information) {
  ?>

  <a class="card-link" href="<?php echo tep_href_link('information.php', 'pages_id=' . $information['pages_id']); ?>"><?php echo $information['pages_name']; ?></a>
  <br>

  <?php
}
?>

<a class="card-link" href="<?php echo tep_href_link('contact_us.php'); ?>"><?php echo MODULE_FOOTER_INFORMATION_TEXT_CONTACT_US; ?></a>