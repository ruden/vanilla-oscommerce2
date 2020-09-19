<div class="mb-3">

  <?php
  if (isset($product_info['manufacturers_id'])) {
    ?>

    <a href="<?php echo tep_href_link('index.php', 'manufacturers_id=' . $product_info['manufacturers_id']); ?>"><?php echo tep_image('images/manufacturers/' . $product_info['manufacturers_image'], $product_info['manufacturers_name']); ?></a>

    <?php
  }
  ?>

</div>
