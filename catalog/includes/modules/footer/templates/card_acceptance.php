<div class="ui-widget infoBoxContainer">' .
  <div class="ui-widget-header infoBoxHeading"><?php echo MODULE_FOOTER_CARD_ACCEPTANCE_BOX_TITLE; ?></div>
  <div class="ui-widget-content infoBoxContents">

    <?php
    foreach (explode(';', MODULE_FOOTER_CARD_ACCEPTANCE_LOGOS) as $logo) {
      echo tep_image('images/card_acceptance/' . basename($logo));
    }
    ?>

  </div>
</div>