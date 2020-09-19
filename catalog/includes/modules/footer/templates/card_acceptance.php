<div class="col-6 col-lg">
  <h5><?php echo MODULE_FOOTER_CARD_ACCEPTANCE_TEXT; ?></h5>

  <?php
  foreach (explode(';', MODULE_FOOTER_CARD_ACCEPTANCE_LOGOS) as $logo) {
    ?>

    <?php echo tep_image('images/card_acceptance/' . basename($logo)); ?>

    <?php
  }
  ?>

</div>