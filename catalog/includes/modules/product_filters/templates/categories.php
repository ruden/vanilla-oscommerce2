<div class="border-1 border-bottom mb-3">
  <h6><?php echo MODULE_PRODUCT_FILTERS_CATEGORIES_TITLE; ?></h6>

  <div class="mb-3">

    <?php
    foreach ($categories_array as $cPath) {
      ?>

      <a href="<?php echo tep_href_link('index.php', $cPath); ?>"><?php echo $cPath; ?></a><br/>

    <?php
    }
    ?>

  </div>
</div>